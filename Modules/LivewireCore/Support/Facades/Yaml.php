<?php namespace Modules\LivewireCore\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array parse(string $contents)
 * @method static array parseFile(string $fileName)
 * @method static string render(array $vars = [], array $options = [])
 *
 * @see \Winter\Storm\Parse\Yaml
 */
class Yaml extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'parse.yaml';
    }
}
