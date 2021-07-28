<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Nwidart\Modules\Commands\GeneratorCommand;

use Illuminate\Support\Str;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Nwidart\Modules\Exceptions\FileAlreadyExistException;
use Nwidart\Modules\Generators\FileGenerator;

class ControllerMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected $argumentName = 'controller';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-back-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new restful controller for the specified module.';


        /**
     * Execute the console command.
     */
    public function handle() : int
    {
        // //first create controller
        if (parent::handle() === E_ERROR) {
            return E_ERROR;
        }

        // //second create controller config

        foreach (['config_list.yaml','config_form.yaml','config_relation.yaml','config_filter.yaml','_list_toolbar.htm','_form-header.htm','_form-footer.htm'] as $configFielName)
        {
            $path = str_replace('\\', '/', $this->getControllerConfigDestinationFilePath($configFielName));
            if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
                $this->laravel['files']->makeDirectory($dir, 0777, true);
            }
            $contents = $this->getTemplateContents('/controller/'.$configFielName);

            try {
                $overwriteFile = $this->hasOption('force') ? $this->option('force') : false;
                (new FileGenerator($path, $contents))->withFileOverwrite($overwriteFile)->generate();

                $this->info("Created : {$path}");
            } catch (FileAlreadyExistException $e) {
                $this->error("File : {$path} already exists.");

                return E_ERROR;
            }


        }

        // //third create view


        foreach(['index.blade.php','create.blade.php','update.blade.php'] as $configFielName){
            $path = str_replace('\\', '/', $this->getViewDestinationFilePath($configFielName));
            if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
                $this->laravel['files']->makeDirectory($dir, 0777, true);
            }
            $contents = $this->getTemplateContents('/controller/'.$configFielName);

            try {
                $overwriteFile = $this->hasOption('force') ? $this->option('force') : false;
                (new FileGenerator($path, $contents))->withFileOverwrite($overwriteFile)->generate();

                $this->info("Created : {$path}");
            } catch (FileAlreadyExistException $e) {
                $this->error("File : {$path} already exists.");

                return E_ERROR;
            }
        }

        $this->updateRoutes();



        return 0;
    }

    public function getViewDestinationFilePath($configFielName)
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $controllerPath = GenerateConfigReader::read('controller');

        return $path . '/Resources/views' . '/' . $this->getStrtolowerControllerName().'/' .$configFielName;
    }

    public function getControllerConfigDestinationFilePath($configFielName)
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $controllerPath = GenerateConfigReader::read('controller');

        return $path . $controllerPath->getPath() . '/' . $this->getStrtolowerControllerName().'/' .$configFielName;
    }

    /**
     * Get controller name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $controllerPath = GenerateConfigReader::read('controller');

        return $path . $controllerPath->getPath() . '/' . $this->getControllerName() . '.php';

    }

    public function getRoutePath($fileName)
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());
        return $path.'/Routes/'.$fileName;
    }


    /**
     * @return string
     */
    protected function getTemplateContents($stubName='')
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($stubName?$stubName:$this->getStubName(), [
            'MODULENAME'        => $module->getStudlyName(),
            'CONTROLLERNAME'    => $this->getControllerName(),
            'NAMESPACE'         => $module->getStudlyName(),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module),
            'CLASS'             => $this->getControllerNameWithoutNamespace(),

            //控制器小写
            'CLASSNOCONTROLLERL'             => $this->getStrtolowerControllerName(),

            //控制器驼峰的大些
            'CLASSNOCONTROLLERU'             => \Str::studly($this->getStrtolowerControllerName()),

            //模型名称的小些
            'MODELNAMEL'             => strtolower(\Str::singular(\Str::studly($this->getStrtolowerControllerName()))),
            //模型名称
            'MODELNAME'             => \Str::singular(\Str::studly($this->getStrtolowerControllerName())),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'NAME'              => $this->getModuleName(),
            'NAMEL'              => strtolower($this->getModuleName()),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),

        ]))->render();
    }

    public function getStrtolowerControllerName()
    {
        return strtolower(str_replace('Controller', '', $this->getControllerNameWithoutNamespace()));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['controller', InputArgument::REQUIRED, 'The name of the controller class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['plain', 'p', InputOption::VALUE_NONE, 'Generate a plain controller', null],
            ['api', null, InputOption::VALUE_NONE, 'Exclude the create and edit methods from the controller.'],
        ];
    }

    /**
     * @return array|string
     */
    protected function getControllerName()
    {
        $controller = Str::studly($this->argument('controller'));

        // if (Str::contains(strtolower($controller), 'controller') === false) {
        //     $controller .= 'Controller';
        // }

        return $controller;
    }

    /**
     * @return array|string
     */
    private function getControllerNameWithoutNamespace()
    {
        return class_basename($this->getControllerName());
    }

    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.controller.namespace') ?: $module->config('paths.generator.controller.path', 'Http/Controllers');
    }

    /**
     * Get the stub file name based on the options
     * @return string
     */
    protected function getStubName()
    {
        if ($this->option('plain') === true) {
            $stub = '/controller-plain.stub';
        } elseif ($this->option('api') === true) {
            $stub = '/controller-api.stub';
        } else {
            $stub = '/controller-back.stub';
        }

        return $stub;
    }


    protected function updateRoutes()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        $LOWER_NAME = $module->getLowerName();

        $l = $this->getStrtolowerControllerName();
        $u = \Str::studly($this->getStrtolowerControllerName());
        $str = "Route::group([
    'prefix' => 'backend/{$LOWER_NAME}',
    'middleware' => ['web','auth'],
    'as'=> 'backend.{$LOWER_NAME}.',
], function () {
    Route::group([
        'prefix'=>'{$l}',
        'as' =>'{$l}.',
        'middleware' =>[]
    ],function () {
        Route::get('', '{$u}@index')->name('index');
        Route::get('create/{context?}', '{$u}@create')->name('create');
        Route::get('update/{id}/{context?}', '{$u}@update')->name('update');
    });
});";

        file_put_contents(
            $this->getRoutePath('web.php'),
            $str,
            FILE_APPEND
        );
    }



}
