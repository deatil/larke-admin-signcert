<?php

declare (strict_types = 1);

namespace Larke\Admin\SignCert;

use Illuminate\Support\Facades\Artisan;

use Larke\Admin\Extension\Rule;
use Larke\Admin\Extension\Menu;
use Larke\Admin\Extension\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * composer
     */
    public $composer = __DIR__ . '/../composer.json';

    /**
     * 扩展图标
     */
    public $icon = __DIR__ . '/../icon.png';
    
    // 包名
    protected $pkgName = "larke/sign-cert";
    
    protected $slug = 'larke-admin.ext.sign-cert';
    
    /**
     * 初始化
     */
    public function boot()
    {
        // 扩展注册
        $this->addExtension(
            name:     __CLASS__, 
            icon:     $this->icon,
            composer: $this->composer,
        );
        
        // 事件
        $this->bootListeners();
    }
    
    /**
     * 运行中
     */
    public function start()
    {
        $this->exceptSlugs();
        
        $this->registerNamespaces();
        
        $this->loadRoutesFrom(__DIR__ . '/../resources/route/admin.php');
    }
    
    /**
     * 过滤slug
     */
    protected function exceptSlugs()
    {
        $this->addAuthenticateExcepts([
            'larke-admin.sign-cert.cert-download',
        ]);
        
        $this->addPermissionExcepts([
            'larke-admin.sign-cert.cert-download',
        ]);
    }
    
    /**
     * 注册命名空间
     */
    protected function registerNamespaces()
    {
        if (! class_exists('phpseclib\\Crypt\\RSA')) {
            $this->registerNamespace([
                'phpseclib\\' => __DIR__ . '/../lib/phpseclib',
            ]);
        }
    }
    
    /**
     * 推送
     */
    protected function assetsPublishes()
    {
        $this->publishes([
            __DIR__.'/../resources/assets/sign-cert' => public_path('extension/sign-cert'),
        ], 'larke-admin-sign-cert-assets');
        
        Artisan::call('vendor:publish', [
            '--tag' => 'larke-admin-sign-cert-assets',
            '--force' => true,
        ]);
    }
    
    /**
     * 监听器
     */
    public function bootListeners()
    {
        $thiz = $this;
        
        // 安装后
        $this->onInatll(function ($name, $info) use($thiz) {
            if ($name == $thiz->pkgName) {
                $thiz->install();
            }
        });
        
        // 卸载后
        $this->onUninstall(function ($name, $info) use($thiz) {
            if ($name == $thiz->pkgName) {
                $thiz->uninstall();
            }
        });
        
        // 更新后
        $this->onUpgrade(function ($name, $oldInfo, $newInfo) use($thiz) {
            if ($name == $thiz->pkgName) {
                $thiz->upgrade();
            }
        });
        
        // 启用后
        $this->onEnable(function ($name, $info) use($thiz) {
            if ($name == $thiz->pkgName) {
                $thiz->enable();
            }
        });
        
        // 禁用后
        $this->onDisable(function ($name, $info) use($thiz) {
            if ($name == $thiz->pkgName) {
                $thiz->disable();
            }
        });
    }
    
    /**
     * 安装后
     */
    protected function install()
    {
        $slug = $this->slug;
        $rules = include __DIR__ . '/../resources/rules/rules.php';
        
        // 添加权限
        Rule::create($rules);
        
        // 添加菜单
        Menu::create($rules);
        
        $this->assetsPublishes();
    }
    
    /**
     * 卸载后
     */
    protected function uninstall()
    {
        // 删除权限
        Rule::delete($this->slug);
        
        // 删除菜单
        Menu::delete($this->slug);
    }
    
    /**
     * 更新后
     */
    protected function upgrade()
    {}
    
    /**
     * 启用后
     */
    protected function enable()
    {
        // 启用权限
        Rule::enable($this->slug);
        
        // 启用菜单
        Menu::enable($this->slug);
    }
    
    /**
     * 禁用后
     */
    protected function disable()
    {
        // 禁用权限
        Rule::disable($this->slug);
        
        // 禁用菜单
        Menu::disable($this->slug);
    }
    
}
