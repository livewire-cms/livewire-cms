<?php

use Modules\LivewireCore\Filesystem\PathResolver;

use Illuminate\Support\Facades\Route;



if (!function_exists('plugins_path')) {
    /**
     * Get the path to the plugins folder.
     *
     * @param  string  $path
     * @return string
     */
    function plugins_path($path = '')
    {
        return PathResolver::join(app('path.plugins'), $path);
    }
}


if (!function_exists('find_controller_by_url')) {

    function find_controller_by_url($path)
    {
        $routes = app('router')->getRoutes();
        foreach($routes as $r){
            // dd($r);
            if((function($route,$path){

                $route->prepareForSerialization(request());
                $path = rtrim($path, '/') ?: '/';
                $path = '/'.ltrim($path, '/');
                return preg_match($route->getCompiled()->getRegex(), rawurldecode($path));
            })($r,$path)){
                $c = explode('@', $r->action['controller'])[0];
                $c = new $c;
                $c->setUser();
                $c->verifyPermissions();

                return  $c;
                dd($r,$r->action);
                return $r->getController();
            }


        }
        return false;
    }
}

if (!function_exists('post')) {
    /**
     * Identical function to input(), however restricted to POST values.
     */
    function post($name = null, $default = null)
    {
        if ($name === null) {
            return Request::post();
        }

        /*
         * Array field name, eg: field[key][key2][key3]
         */

        if (class_exists('Modules\LivewireCore\Html\Helper')) {
            $name = implode('.', Modules\LivewireCore\Html\Helper::nameToArray($name));
        }

        return \Arr::get(Request::post(), $name, $default);
    }
}
