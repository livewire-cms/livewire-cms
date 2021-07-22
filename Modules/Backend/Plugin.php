<?php

namespace Modules\Backend;

use Backend;
use Modules\System\Classes\PluginBase;
use BackendMenu;
use Modules\Backend\Models\UserRole;

/**
 * Test Plugin Information File
 */
class Plugin extends PluginBase
{


    public function registerPermissions()
    {
        return [
            'backend.manage_users' => [
                'label' => 'system::lang.permissions.manage_other_administrators',
                'tab'   => 'system::lang.permissions.name',
                'roles' => UserRole::CODE_DEVELOPER,
            ],
        ];
    }

    public function registerNavigation()
    {

        return [



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
                'label' => '后台用户列表',
                'description' => 'rainlab.blog::lang.blog.settings_description',
                'category' => '后台用户设置',
                'icon' => 'icon-pencil',
                'url'         => Backend::url('backend/users'),
                'order' => 800,
                'context'=>['modules.system'],//对应模块1的标识符
                'keywords' => 'system users',
                'permissions' => ['backend.manage_users'],
            ],
            'usergroups' => [
                'label' => 'Group',
                'description' => 'rainlab.blog::lang.blog.settings_description',
                'category' => '后台用户设置',
                'icon' => 'icon-pencil',
                'url'         => Backend::url('backend/usergroups'),
                'order' => 900,
                'context'=>['modules.system'],//对应模块1的标识符
                'keywords' => 'system groups',
                'permissions' => ['backend.manage_users'],
            ],
            'userroles' => [
                'label' => 'Role',
                'description' => 'rainlab.blog::lang.blog.settings_description',
                'category' => '后台用户设置',
                'icon' => 'icon-pencil',
                'url'         => Backend::url('backend/userroles'),
                'order' => 900,
                'context'=>['modules.system'],//对应模块1的标识符
                'keywords' => 'system roles',
                'permissions' => ['backend.manage_users'],
            ],

        ];
    }

    public function boot()
    {

    }
}
