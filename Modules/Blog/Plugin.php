<?php

namespace Modules\Blog;

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
        return [];

        return [

            'blogs' => [
                'label'       => 'blog',
                'url'         => Backend::url('backend/blog/hellos'),
                'icon'        => 'icon-leaf',
                'permissions' => [],
                'order'       => 500,


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
            'hello' => [
                'label' => '导航blog1',
                'description' => 'rainlab.blog::lang.blog.settings_description',
                'category' => '分类1',
                'icon' => 'icon-pencil',
                'url'         => Backend::url('october/hello/worlds'),
                'order' => 500,
                'context'=>['modules.blog'],//对应模块1的标识符
                'keywords' => 'blog post category',
                'permissions' => [],
            ],
            'hellos' => [
                'label' => '导航2',
                'description' => 'rainlab.blog::lang.blog.settings_description',
                'category' => '分类2',
                'icon' => 'icon-pencil',
                'url'         => Backend::url('october/hello/worlds'),
                'order' => 500,
                // 'category' => '分类1',

                'context'=>'modules.blog',
                'keywords' => 'blog post category',
                'permissions' => []
            ],
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
