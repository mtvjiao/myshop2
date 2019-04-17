<?php
// +----------------------------------------------------------------------
// | test [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.zzstudio.net All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Byron Sampson <xiaobo.sun@gzzstudio.net>
// +----------------------------------------------------------------------
namespace addons\NsDiyView;

class NsDiyViewAddon extends \addons\Addons
{

    public $info = array(
        'name' => 'NsDiyView', // 插件名称标识
        'title' => '自定义模板', // 插件中文名
        'description' => '该插件 支持手机自定义模板功能', // 插件概述
        'status' => 1, // 状态 1启用 0禁用
        'author' => 'niushop', // 作者
        'version' => '1.0', // 版本号
        'has_addonslist' => 0, // 是否有下级插件 
        'content' => '', // 插件的详细介绍或使用方法
        'ico' => 'addons/NsDiyView/ico.png'
    );
    
    // 钩子名称（需要该钩子调用的页面）
    /**
     * 插件安装(non-PHPdoc)
     * @see \addons\Addons::install()
     */
   public function install(){
       return true;
   }
   
   /**
    * 插件卸载(non-PHPdoc)
    * @see \addons\Addons::uninstall()
    */
   public function uninstall(){
       return true;
   }
}