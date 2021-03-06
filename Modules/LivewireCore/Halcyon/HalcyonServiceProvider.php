<?php namespace Modules\LivewireCore\Halcyon;

use Modules\LivewireCore\Halcyon\Datasource\Resolver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\CacheManager;

/**
 * Service provider
 *
 * @author Alexey Bobkov, Samuel Georges
 */
class HalcyonServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        Model::setDatasourceResolver($this->app['halcyon']);

        Model::setEventDispatcher($this->app['events']);

        Model::setCacheManager($this->app['cache']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        Model::clearBootedModels();
        Model::clearExtendedClasses();
        Model::flushDuplicateCache();
        Model::flushEventListeners();

        // The halcyon resolver is used to resolve various datasources,
        // since multiple datasources might be managed.
        $this->app->singleton('halcyon', function ($app) {
            return new Resolver;
        });

        if (MemoryCacheManager::isEnabled()) {
            $this->app->extend(CacheManager::class, function ($cacheManager, $app) {
                return new MemoryCacheManager($app);
            });
        }
    }
}
