<?php

namespace Modules\Hello;

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
            'hello.hellos.index' => [
                'label' => 'hello列表',
                'tab' => 'hello'
            ],
        ];
    }

    public function registerNavigation()
    {

        return [

            'hellos' => [
                'label'       => 'hello',
                'url'         => Backend::url('hello/hellos'),
                'icon'        => 'icon-leaf',
                'permissions' => [],
                'order'       => 500,
                'context'=>'hellos',


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
            'Modules\Hello\FormWidgets\Name' => [
                'code' => 'hello-name',
            ]
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
            'hello' => [
                'label' => '导航1',
                'description' => 'rainlab.blog::lang.blog.settings_description',
                'category' => '分类1',
                'icon' => 'icon-pencil',
                'url'         => Backend::url('hello/hellos'),
                'order' => 500,
                'context'=>'modules.hello',//对应模块1的标识符
                'keywords' => 'blog post category',
                'permissions' => [],
            ],
            // 'users' => [
            //     'label' => '用户列表',
            //     'description' => 'rainlab.blog::lang.blog.settings_description',
            //     'category' => '分类2',
            //     'icon' => 'icon-pencil',
            //     'url'         => Backend::url('backend/users'),
            //     'order' => 500,
            //     // 'category' => '分类1',

            //     'context'=>'modules.hello',
            //     'keywords' => 'blog post category',
            //     'permissions' => []
            // ],
            'hello2' => [
                'label' => '导航3',
                'description' => 'rainlab.blog::lang.blog.settings_description',
                'category' => '分类3',
                'icon' => 'icon-pencil',
                'url'         => Backend::url('october/hello/worlds'),
                'order' => 500,
                'context'=>'modules.blog',
                'keywords' => 'blog post category',
                'permissions' => ['rainlab.blog.manage_settings']
            ],
        ];
    }

    public function boot()
    {

    }
}
