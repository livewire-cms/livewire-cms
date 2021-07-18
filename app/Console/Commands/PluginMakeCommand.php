<?php

namespace App\Console\Commands;

use Nwidart\Modules\Commands\GeneratorCommand;

use Illuminate\Support\Str;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Nwidart\Modules\Exceptions\FileAlreadyExistException;
use Nwidart\Modules\Generators\FileGenerator;

class PluginMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'model';


    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-back-plugin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Plugin.php for the specified module.';

    public function handle() : int
    {
        if (parent::handle() === E_ERROR) {
            return E_ERROR;
        }

        $this->overwriteIndexBalde();

        return 0;
    }
    public function overwriteIndexBalde()
    {
        $str = '<x-back-container>
    <div class="h-full flex justify-center items-center text-center dark:text-gray-400">
        <div>
            <h1>Hello World</h1>

            <p>
                This view is loaded from module: {!! config("test.name") !!}
            </p>
        </div>

    </div>


</x-back-container>';
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $file = $path.'/Resources/views/index.blade.php';
        if(file_exists($file)){
            $contents = file_get_contents($file);
            if(!\Str::contains($contents, 'x-back-container')){
                file_put_contents($file, $str);
            }
        }
    }



    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [


        ];
    }



    /**
     * @return mixed
     */
    protected function getTemplateContents($stubname='')
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($stubname?$stubname:'/plugin.stub', [
            // 'NAMESPACE'         => $this->getClassNamespace($module),
            // 'CLASS'             => $this->getClass(),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
        ]))->render();
    }


    /**
     * @return mixed
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());


        return $path . '/' . 'Plugin.php';
    }

}
