<?php

namespace Modules\Backend;

use Backend;
use Modules\System\Classes\PluginBase;
use BackendMenu;

/**
 * Test Plugin Information File
 */
class Plugin extends PluginBase
{


    public function registerPermissions()
    {
        return [

        ];
    }

    public function registerNavigation()
    {

        return [

            'users' => [
                'label'       => '用户列表',
                'url'         => Backend::url('backend/users'),
                'icon'        => 'icon-leaf',
                'permissions' => [],
                'order'       => 800,


                'sideMenu' => [
                    'new_hello' => [
                        'label'       => '创建路线',
                        'icon'        => 'icon-plus',
                        'url'         => Backend::url('backend/hello/hellos/create'),
                        'permissions' => [],
                        'category'    => '分类1',

                    ],
                    'hellos' => [
                        'label'       => '路线',
                        'icon'        => 'icon-list-ul',
                        'url'         => Backend::url('backend/hello/hellos'),
                        'permissions' => [],
                    ],

                ]
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

        // dd(3213);
        // BackendMenu::registerContextSidenavPartial(
        //     'Modules.Hello',
        //     'Hello',
        //     '~/modules/system/partials/_system_sidebar.htm'
        // );

    }

    public function registerSideNavSettings()
    {
        // dd(232);
        return [
            'users' => [
                'label' => '用户列表',
                'description' => 'rainlab.blog::lang.blog.settings_description',
                'category' => '分类2',
                'icon' => 'icon-pencil',
                'url'         => Backend::url('backend/users'),
                'order' => 800,
                'context'=>['modules.backend','modules.hello'],//对应模块1的标识符
                'keywords' => 'blog post category',
                'permissions' => [],
            ],

        ];
    }

    public function boot()
    {

    }
}
