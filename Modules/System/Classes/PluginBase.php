<?php namespace Modules\System\Classes;

use Str;
use File;
use Yaml;
use Backend;
use ReflectionClass;
use Modules\LivewireCore\Exception\SystemException;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider as ServiceProviderBase;

/**
 * Plugin base class
 *
 * @package winter\wn-system-module
 * @author Alexey Bobkov, Samuel Georges
 */
class PluginBase extends ServiceProviderBase
{
    /**
     * @var boolean
     */
    protected $loadedYamlConfiguration = false;

    /**
     * @var string The absolute path to this plugin's directory, access with getPluginPath()
     */
    protected $path;

    /**
     * @var string The version of this plugin as reported by updates/version.yaml, access with getPluginVersion()
     */
    protected $version;

    /**
     * @var array Plugin dependencies
     */
    public $require = [];

    /**
     * @var boolean Determine if this plugin should have elevated privileges.
     */
    public $elevated = false;

    /**
     * @var boolean Determine if this plugin should be loaded (false) or not (true).
     */
    public $disabled = false;

    /**
     * Returns information about this plugin, including plugin name and developer name.
     *
     * @return array
     * @throws SystemException
     */
    public function pluginDetails()
    {


        return [];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Registers CMS markup tags introduced by this plugin.
     *
     * @return array
     */
    public function registerMarkupTags()
    {
        return [];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
       return [];
    }

    /**
     * Registers back-end quick actions for this plugin.
     *
     * @return array
     */
    public function registerQuickActions()
    {
        return [];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [];
    }

    /**
     * Registers any back-end configuration links used by this plugin.
     *
     * @return array
     */
    public function registerSettings()
    {
        return [];
    }

    /**
     * Registers scheduled tasks that are executed on a regular basis.
     *
     * @param Schedule $schedule
     * @return void
     */
    public function registerSchedule($schedule)
    {
    }

    /**
     * Registers any report widgets provided by this plugin.
     * The widgets must be returned in the following format:
     *
     *     return [
     *         'className1'=>[
     *             'label'    => 'My widget 1',
     *             'context' => ['context-1', 'context-2'],
     *         ],
     *         'className2' => [
     *             'label'    => 'My widget 2',
     *             'context' => 'context-1'
     *         ]
     *     ];
     *
     * @return array
     */
    public function registerReportWidgets()
    {
        return [];
    }

    /**
     * Registers any form widgets implemented in this plugin.
     * The widgets must be returned in the following format:
     *
     *     return [
     *         ['className1' => 'alias'],
     *         ['className2' => 'anotherAlias']
     *     ];
     *
     * @return array
     */
    public function registerFormWidgets()
    {
        return [];
    }

    /**
     * Registers custom back-end list column types introduced by this plugin.
     *
     * @return array
     */
    public function registerListColumnTypes()
    {
        return [];
    }

    /**
     * Registers any mail layouts implemented by this plugin.
     * The layouts must be returned in the following format:
     *
     *     return [
     *         'marketing'    => 'acme.blog::layouts.marketing',
     *         'notification' => 'acme.blog::layouts.notification',
     *     ];
     *
     * @return array
     */
    public function registerMailLayouts()
    {
        return [];
    }

    /**
     * Registers any mail templates implemented by this plugin.
     * The templates must be returned in the following format:
     *
     *     return [
     *         'acme.blog::mail.welcome',
     *         'acme.blog::mail.forgot_password',
     *     ];
     *
     * @return array
     */
    public function registerMailTemplates()
    {
        return [];
    }

    /**
     * Registers any mail partials implemented by this plugin.
     * The partials must be returned in the following format:
     *
     *     return [
     *         'tracking'  => 'acme.blog::partials.tracking',
     *         'promotion' => 'acme.blog::partials.promotion',
     *     ];
     *
     * @return array
     */
    public function registerMailPartials()
    {
        return [];
    }

    /**
     * Registers a new console (artisan) command
     *
     * @param string $key The command name
     * @param string|\Closure $command The command class or closure
     * @return void
     */
    public function registerConsoleCommand($key, $command)
    {
        $key = 'command.'.$key;
        $this->app->singleton($key, $command);
        $this->commands($key);
    }




    /**
     * Gets the identifier for this plugin
     *
     * @return string Identifier in format of Author.Plugin
     */
    public function getPluginIdentifier(): string
    {
        $namespace = Str::normalizeClassName(get_class($this));
        if (strpos($namespace, '\\') === null) {
            return $namespace;
        }

        $parts = explode('\\', $namespace);
        $slice = array_slice($parts, 1, 2);
        $namespace = implode('.', $slice);

        return $namespace;
    }

    /**
     * Returns the absolute path to this plugin's directory
     *
     * @return string
     */
    public function getPluginPath(): string
    {
        if ($this->path) {
            return $this->path;
        }

        $reflection = new ReflectionClass($this);
        $this->path = dirname($reflection->getFileName());

        return $this->path;
    }

}
