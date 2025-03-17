<template>
  <div class="app-container">
    <el-card>
      <div slot="header" class="clearfix">
          <span>ECDSA证书</span>
      </div>

      <el-alert
          type="warning"
          title="注意事项"
          description="生成 ECDSA 证书需要 php 开启 openssl 扩展。两个曲线是 prime256v1(NIST P-256) 和 secp384r1(NIST P-384)"
          style="margin-bottom:15px;"
          show-icon
          :closable="false"
      />        

      <div class="filter-container">
          <el-select v-model="setting.type" placeholder="秘钥长度" class="filter-item" style="width: 130px;margin-right: 10px;">
              <el-option v-for="type in typeOptions" :key="type.key" :label="type.display_name" :value="type.key" />
          </el-select>

          <el-select v-model="setting.cipher" placeholder="加密类型" class="filter-item" style="width: 150px;margin-right: 10px;">
              <el-option v-for="cipher in cipherOptions" :key="cipher.key" :label="cipher.display_name" :value="cipher.key" />
          </el-select>

          <el-input v-model="setting.pass" placeholder="秘钥密码，可不填" clearable style="width: 200px;margin-right: 10px;" class="filter-item" />

          <el-button v-waves class="filter-item" type="primary" :loading="submitLoading" @click="submit">
            生成证书
          </el-button>
      </div>

      <div class="sign-box">
          <div class="sign-setting-payload">
              <div class="sign-data-tip">
                私钥 
                <el-tag type="success" size="mini">
                  ecdsa_private_key.pem
                </el-tag>
                <el-button v-waves size="mini" style="margin-left:10px;" @click="handleClipboard(response.private_key, $event)">
                    复制
                </el-button>                      
              </div>
              <div class="sign-data-input">
                  <el-input v-model.trim="response.private_key" type="textarea" rows="6" placeholder="私钥" />
              </div>                  
          </div>

          <div class="sign-response-data">
              <div class="sign-data-tip">
                公钥
                <el-tag type="success" size="mini">
                  ecdsa_public_key.pem
                </el-tag>   
                <el-button v-waves size="mini" style="margin-left:10px;" @click="handleClipboard(response.public_key, $event)">
                    复制
                </el-button>                                       
              </div>
              <div class="sign-data-input">
                  <el-input v-model.trim="response.public_key" type="textarea" rows="6" placeholder="公钥" />
              </div>                
          </div>         
       </div>

    </el-card>
  </div>
</template>

<script>
import clipboard from '@/utils/clipboard'
import waves from '@/directive/waves'
import { ecdsa } from '../../api/signCert'

export default {
  name: 'ConfigIndex',
  components: {  },
  directives: { waves },
  filters: {

  },
  data() {
    return {
      submitLoading: false,
      setting: {
        type: 'p256',
        cipher: '3DES',
        pass: '',   
      },
      response: {
          private_key: '',
          public_key: '',
      },
      typeOptions: [
        { key: 'p224', display_name: 'P224' },
        { key: 'p256', display_name: 'P256' },
        { key: 'p384', display_name: 'P384' },
        { key: 'p521', display_name: 'P521' },
        { key: 's256', display_name: 'S256' },
      ],  
      cipherOptions: [
        { key: 'RC2_40', display_name: 'RC2_40' },
        { key: 'RC2_64', display_name: 'RC2_64' },
        { key: 'RC2_128', display_name: 'RC2_128' },
        { key: 'DES', display_name: 'DES' },
        { key: '3DES', display_name: '3DES' },
        { key: 'AES_128_CBC', display_name: 'AES_128_CBC' },
        { key: 'AES_192_CBC', display_name: 'AES_192_CBC' },
        { key: 'AES_256_CBC', display_name: 'AES_256_CBC' },
      ],  
    }
  },
  created() {
  },
  methods: {
    handleClipboard(text, event) {
        if (text == '') {
            this.errorTip('请先提交创建后复制')
            return 
        }

        clipboard(text, event)
    },     
    submit() {
        this.response.private_key = ''
        this.response.public_key = ''

        this.submitLoading = true
        
        ecdsa(this.setting).then(response => {
            this.response.private_key = response.data.private_key
            this.response.public_key = response.data.public_key

            this.submitLoading = false

            this.successTip('创建成功')
        }).catch(err => {
          this.submitLoading = false
        })
    }
  }
}
</script>

<style scoped>
.sign-setting-payload {
    margin-bottom: 15px;;
}
.sign-data {
    margin-top: 15px;
} 
.sign-data-tip {
    padding: 10px 0;
    font-size: 14px;
    color: #606266;
}
</style>
