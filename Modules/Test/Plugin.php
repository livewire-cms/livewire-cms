<?php namespace Modules\Test;

use Backend;
use Modules\System\Classes\PluginBase;


/**
 * Test Plugin Information File
 */
class Plugin extends PluginBase
{


    public function registerPermissions()
    {
        return [
            'backend.test.index' => [
                'label' => 'Test',
                'comment' =>'23132',

                'tab' => 'Test'
            ],
        ];
    }

    //顶部菜单
    public function registerNavigation()
    {

        return [

            'test' => [
                'label'       => 'Test',
                'url'         => Backend::url('test'),
                'icon'        => 'icon-leaf',
                'permissions' => [],
                'order'       => 500,
            ],
        ];
    }

    public function registerFormWidgets()
    {
        return [

        ];
    }

    public function register()
    {



    }

    //侧边栏菜单
    public function registerSideNavSettings()
    {

        return [
            'test' => [
                'label' => 'Test',
                'description' => '',
                'category' => 'Test',//侧边栏分组
                'icon' => 'icon-pencil',
                'url'         => Backend::url('test'),
                'order' => 500,
                'context'=>['modules.test'],//对应模块1的标识符
                'keywords' => 'test',
                'permissions' => [],
            ],
            'foo' => [
                'label' => '基础编辑',
                'description' => '',
                'category' => 'Test',//侧边栏分组
                'icon' => 'icon-pencil',
                'url'         => Backend::url('test/foo'),
                'order' => 500,
                'context'=>['modules.test'],//对应模块1的标识符
                'keywords' => 'test foo',
                'permissions' => [],
            ],
            'post' => [
                'label' => '关联关系',
                'description' => '',
                'category' => 'Test',//侧边栏分组
                'icon' => 'icon-pencil',
                'url'         => Backend::url('test/post'),
                'order' => 500,
                'context'=>['modules.test'],//对应模块1的标识符
                'keywords' => 'test post',
                'permissions' => [],
            ],
        ];
    }

    public function boot()
    {

    }
}
