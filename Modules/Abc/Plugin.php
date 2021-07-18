<?php namespace Modules\Abc;

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
            'backend.abc.index' => [
                'label' => 'Abc',
                'tab' => 'Abc'
            ],
        ];
    }

    //顶部菜单
    public function registerNavigation()
    {

        return [

            'abc' => [
                'label'       => 'Abc',
                'url'         => Backend::url('abc'),
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
            'abc' => [
                'label' => 'Abc',
                'description' => '',
                'category' => 'Abc',//侧边栏分组
                'icon' => 'icon-pencil',
                'url'         => Backend::url('abc'),
                'order' => 500,
                'context'=>['modules.abc'],//对应模块1的标识符
                'keywords' => 'abc',
                'permissions' => [],
            ],
            'hello' => [
                'label' => 'hello',
                'description' => '',
                'category' => 'Hello',//侧边栏分组
                'icon' => 'icon-pencil',
                'url'         => Backend::url('abc/hello'),
                'order' => 500,
                'context'=>['modules.abc'],//对应模块1的标识符
                'keywords' => 'abc',
                'permissions' => [],
            ],
        ];
    }

    public function boot()
    {

    }
}
