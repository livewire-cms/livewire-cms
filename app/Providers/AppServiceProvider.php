<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Arr;
use Str;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Arr::macro('build', function(array $array, callable $callback){
            $results = [];

            foreach ($array as $key => $value) {
                list($innerKey, $innerValue) = call_user_func($callback, $key, $value);

                $results[$innerKey] = $innerValue;
            }

            return $results;
        });
        Str::macro('normalizeClassName', function ($name){
            if (is_object($name)) {
                $name = get_class($name);
            }

            $name = '\\'.ltrim($name, '\\');
            return $name;
        });
        Str::macro('getClassId', function ($name){
            if (is_object($name)) {
                $name = get_class($name);
            }

            $name = ltrim($name, '\\');
            $name = str_replace('\\', '_', $name);

            return strtolower($name);
        });
        Str::macro('getClassNamespace', function ($name){
            $name = Str::normalizeClassName($name);
            return substr($name, 0, strrpos($name, "\\"));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        //
        $this->configureComponents();

    }

            /**
     * Configure the Jetstream Blade components.
     *
     * @return void
     */
    protected function configureComponents()
    {
        // $this->callAfterResolving(BladeCompiler::class, function () {
            $this->registerComponent('action-message');
            $this->registerComponent('action-section');
            $this->registerComponent('application-logo');
            $this->registerComponent('application-mark');
            $this->registerComponent('authentication-card');
            $this->registerComponent('authentication-card-logo');
            $this->registerComponent('banner');
            $this->registerComponent('button');
            $this->registerComponent('confirmation-modal');
            $this->registerComponent('confirms-password');
            $this->registerComponent('danger-button');
            $this->registerComponent('dialog-modal');
            $this->registerComponent('dropdown');
            $this->registerComponent('dropdown-link');
            $this->registerComponent('form-section');
            $this->registerComponent('input');
            $this->registerComponent('checkbox');
            $this->registerComponent('input-error');
            $this->registerComponent('label');
            $this->registerComponent('modal');
            $this->registerComponent('nav-link');
            $this->registerComponent('responsive-nav-link');
            $this->registerComponent('responsive-switchable-team');
            $this->registerComponent('secondary-button');
            $this->registerComponent('section-border');
            $this->registerComponent('section-title');
            $this->registerComponent('switchable-team');
            $this->registerComponent('validation-errors');
            $this->registerComponent('welcome');
        // });
    }
    /**
     * Register the given component.
     *
     * @param  string  $component
     * @return void
     */
    protected function registerComponent(string $component)
    {
        Blade::component('components.'.$component, 'jet-'.$component);
    }
}
