<?php namespace Modules\System\Traits;

use Illuminate\Support\Facades\File;
use Lang;
use Block;
use Modules\LivewireCore\Exception\SystemException;
use Exception;
use Throwable;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Config;
use ReflectionClass;
use DirectoryIterator;

/**
 * View Maker Trait
 * Adds view based methods to a class
 *
 * @package winter\wn-system-module
 * @author Alexey Bobkov, Samuel Georges
 */
trait ViewMaker
{
    /**
     * @var array A list of variables to pass to the page.
     */
    public $vars = [];

    /**
     * @var string|array Specifies a path to the views directory.
     */
    protected $viewPath;

    /**
     * @var string Specifies a path to the layout directory.
     */
    protected $layoutPath;

    /**
     * @var string Layout to use for the view.
     */
    public $layout;

    /**
     * @var bool Prevents the use of a layout.
     */
    public $suppressLayout = false;



    /**
     * Prepends a path on the available view path locations.
     * @param string|array $path
     * @return void
     */
    public function addViewPath($path)
    {
        $this->viewPath = (array) $this->viewPath;

        if (is_array($path)) {
            $this->viewPath = array_merge($path, $this->viewPath);
        } else {
            array_unshift($this->viewPath, $path);
        }
    }

    /**
     * Returns the active view path locations.
     * @return array
     */
    public function getViewPaths()
    {
        return (array) $this->viewPath;
    }


    /**
     * Guess the package path for the called class.
     * @param string $suffix An extra path to attach to the end
     * @param bool $isPublic Returns public path instead of an absolute one
     * @return string
     */
    public function guessViewPath($suffix = '', $isPublic = false)
    {
        $class = get_called_class();
        return $this->guessViewPathFrom($class, $suffix, $isPublic);
    }

    /**
     * Guess the package path from a specified class.
     * @param string $class Class to guess path from.
     * @param string $suffix An extra path to attach to the end
     * @param bool $isPublic Returns public path instead of an absolute one
     * @return string
     */
    public function guessViewPathFrom($class, $suffix = '', $isPublic = false)
    {
        $classFolder = strtolower(class_basename($class));
        $classFile = realpath(dirname((new ReflectionClass($class))->getFileName()));
        $guessedPath = $classFile ? $classFile . '/' . $classFolder . $suffix : null;
        return $guessedPath;
    }




    /**
     * Render a partial file contents located in the views folder.
     * @param string $partial The view to load.
     * @param array $params Parameter variables to pass to the view.
     * @param bool $throwException Throw an exception if the partial is not found.
     * @return mixed Partial contents or false if not throwing an exception.
     */
    public function makePartial($partial, $params = [], $throwException = true)
    {
        // $notRealPath = realpath($partial) === false || is_dir($partial) === true;
        // if (!File::isPathSymbol($partial) && $notRealPath) {
        //     $folder = strpos($partial, '/') !== false ? dirname($partial) . '/' : '';
        //     $partial = $folder . '_' . strtolower(basename($partial)).'.htm';
        // }
        $partial = '_' . strtolower(basename($partial)).'.htm';

        $partialPath = $this->getViewPath($partial);
        if (!File::exists($partialPath)) {
            if ($throwException) {
                throw new SystemException(Lang::get('backend::lang.partial.not_found_name', ['name' => $partialPath]));
            }

            return false;
        }
        // dd($partialPath);

        return $this->makeFileContents($partialPath, $params);
    }
    /**
     * Handle a view exception.
     *
     * @param  \Exception  $e
     * @param  int  $obLevel
     * @return void
     *
     */
    protected function handleViewException($e, $obLevel)
    {
        while (ob_get_level() > $obLevel) {
            ob_end_clean();
        }

        throw $e;
    }
  /**
     * Locates a file based on its definition. The file name can be prefixed with a
     * symbol (~|$) to return in context of the application or plugin base path,
     * otherwise it will be returned in context of this object view path.
     * @param string $fileName File to load.
     * @param mixed $viewPath Explicitly define a view path.
     * @return string Full path to the view file.
     */
    public function getViewPath($fileName, $viewPath = null)
    {
        
        if (!isset($this->viewPath)) {
            $this->viewPath = $this->guessViewPath();
        }

        if (!$viewPath) {
            $viewPath = $this->viewPath;
        }

        // $fileName = File::symbolizePath($fileName);

        // if (File::isLocalPath($fileName) ||
        //     (!Config::get('cms.restrictBaseDir', true) && realpath($fileName) !== false)
        // ) {
        //     return $fileName;
        // }

        if (!is_array($viewPath)) {
            $viewPath = [$viewPath];
        }

        foreach ($viewPath as $path) {
            // $_fileName = File::symbolizePath($path) . '/' . $fileName;
            $_fileName = $path . '/' . $fileName;
            if (File::isFile($_fileName)) {
                return $_fileName;
            }
        }

        return $fileName;
    }
        /**
     * Includes a file path using output buffering.
     * Ensures that vars are available.
     * @param string $filePath Absolute path to the view file.
     * @param array $extraParams Parameters that should be available to the view.
     * @return string
     */
    public function makeFileContents($filePath, $extraParams = [])
    {
        if (!strlen($filePath) ||
            !File::isFile($filePath)
            // || (!File::isLocalPath($filePath) && Config::get('cms.restrictBaseDir', true))
        ) {
            return '';
        }

        if (!is_array($extraParams)) {
            $extraParams = [];
        }

        $vars = array_merge($this->vars, $extraParams);

        $obLevel = ob_get_level();

        ob_start();

        extract($vars);

        // We'll evaluate the contents of the view inside a try/catch block so we can
        // flush out any stray output that might get out before an error occurs or
        // an exception is thrown. This prevents any partial views from leaking.
        try {
            include $filePath;
        }
        catch (Exception $e) {
            $this->handleViewException($e, $obLevel);
        }
        catch (Throwable $e) {
            $this->handleViewException($e,$obLevel);
        }

        return ob_get_clean();
    }





}
