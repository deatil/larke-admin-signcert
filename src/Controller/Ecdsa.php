<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use Larke\Admin\Annotation\RouteRule;
use Larke\Admin\Http\Controller as BaseController;

/**
 * Ecdsa
 *
 * @create 2020-12-25
 * @author deatil
 */
class Ecdsa extends BaseController
{
    /**
     * Ecdsa创建
     *
     * @param  Request  $request
     * @return Response
     */
    #[RouteRule(
        title:  "Ecdsa创建", 
        desc:   "Ecdsa证书创建",
        order:  1501,
        parent: "larke-admin.ext.sign-cert",
        auth:   true
    )]
    public function create(Request $request)
    {
        $type = $request->input('type');
        $cipher = $request->input('cipher');
        $privkeypass = $request->input('pass', null);

        // 方式
        $curveName = match($type) {
            'p224'  => 'secp224r1', 
            'p256'  => 'prime256v1', 
            'p384'  => 'secp384r1',
            'p521'  => 'secp521r1',
            's256'  => 'secp256k1', 
            default => 'prime256v1',
        };

        // 加密 cipher
        $encryptCipher = match($cipher) {
            'RC2_40'      => OPENSSL_CIPHER_RC2_40,
            'RC2_64'      => OPENSSL_CIPHER_RC2_64,
            'RC2_128'     => OPENSSL_CIPHER_RC2_128,
            'DES'         => OPENSSL_CIPHER_DES,
            '3DES'        => OPENSSL_CIPHER_3DES,
            'AES_128_CBC' => OPENSSL_CIPHER_AES_128_CBC,
            'AES_192_CBC' => OPENSSL_CIPHER_AES_192_CBC,
            'AES_256_CBC' => OPENSSL_CIPHER_AES_256_CBC,
            default       => OPENSSL_CIPHER_3DES,
        };
        
        $privkey = ""; // 私钥
        $pubkey = ""; // 公钥
        
        $opensslConfigPath = __DIR__ . "/../../resources/ssl/openssl.cnf";
        
        $config = [
            "encrypt_key_cipher" => $encryptCipher,
            "private_key_type"   => OPENSSL_KEYTYPE_EC, 
            "curve_name"         => $curveName, 
            "config"             => $opensslConfigPath,
        ];
        
        // 生成证书
        $res = openssl_pkey_new($config); 
        openssl_pkey_export($res, $privkey, $privkeypass, $config);
        
        $pubkeyDetails = openssl_pkey_get_details($res);
        $pubkey = $pubkeyDetails["key"];
        
        openssl_free_key($res);
        
        $data = [
            'private_key' => $privkey,
            'public_key'  => $pubkey,
        ];
        
        return $this->success(__('创建成功'), $data);
    }
}
