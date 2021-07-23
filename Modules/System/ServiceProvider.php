<?php

namespace Modules\System;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Modules\LivewireCore\Filesystem\PathResolver;

use Modules\System\Classes\PluginManager;
use Modules\System\Classes\SettingsManager;
use Modules\System\Classes\SideNavManager;

use BackendMenu;
use Backend;
use Event;
use Livewire\Livewire;

class ServiceProvider extends BaseServiceProvider
{
    public $pluginsPath;
    public $tempPath;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->instance('path.plugins', $this->pluginsPath());
        $this->app->instance('path.temp', $this->tempPath());

        $this->registerSingletons();

        PluginManager::instance()->registerAll();





        $this->registerBackendNavigation();

    }

        /**
     * Get the path to the public / web directory.
     *
     * @return string
     */
    public function tempPath()
    {
        return $this->tempPath ?: PathResolver::join(base_path(), '/storage/temp');
    }


    /*
     * Register navigation
     */
    protected function registerBackendNavigation()
    {
        BackendMenu::registerCallback(function ($manager) {
            $manager->registerMenuItems('Modules.System', [
                'system' => [
                    'label'       => 'system::lang.settings.menu_label',
                    'icon'        => 'icon-cog',
                    'iconSvg'     => 'modules/system/assets/images/cog-icon.svg',
                    'url'         => Backend::url('system/system'),
                    'permissions' => [],
                    'order'       => 1000
                ]
            ]);
            // $manager->registerOwnerAlias('Winter.System', 'October.System');
        });




        $this->booting(function () {
            /*
                    * Remove the Winter.System.system main menu item if there is no subpages to display
                    */
            // Event::listen('backend.menu.extendItems', function ($manager) {
            //     $systemSettingItems = SettingsManager::instance()->listItems('system');
            //     $systemMenuItems = $manager->listSideMenuItems('Modules.System', 'system');
            //     if (empty($systemSettingItems) && empty($systemMenuItems)) {
            //         $manager->removeMainMenuItem('Modules.System', 'system');
            //     }
            // }, -9999);
            Event::listen('backend.menu.extendItems', function ($manager) {
                // dd(32312);
                $systemSettingItems = SideNavManager::instance()->listItems('modules.system');
                $systemMenuItems = $manager->listSideMenuItems('Modules.System', 'system');

                if (empty($systemSettingItems) && empty($systemMenuItems)) {
                    $manager->removeMainMenuItem('Modules.System', 'system');
                }
            }, -9999);
        });
    }
    /**
     * Register singletons
     */
    protected function registerSingletons()
    {
        $this->app->singleton('backend.helper', function () {
            return new \Modules\Backend\Helpers\Backend;
        });

        $this->app->singleton('backend.menu', function () {
            return \Modules\Backend\Classes\NavigationManager::instance();
        });
        $this->app->singleton('backend.auth', function () {
            return \Modules\Backend\Classes\AuthManager::instance();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // dd(Arr::class);
    }

    public function pluginsPath()
    {
        return $this->pluginsPath ?: PathResolver::join(base_path(), '/Modules');
    }
    /**
    * Set the plugins path for the application.
    *
    * @param  string $path
    * @return $this
    */
    public function setPluginsPath($path)
    {
        $path = PathResolver::standardize($path);
        $this->pluginsPath = $path;
        $this->app->instance('path.plugins', $path);
        return $this;
    }
}
