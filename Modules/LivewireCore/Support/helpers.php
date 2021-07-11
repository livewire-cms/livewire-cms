<?php

use Modules\LivewireCore\Filesystem\PathResolver;




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

            if((function($route,$path){

                $route->prepareForSerialization(request());
                $path = rtrim($path, '/') ?: '/';
                $path = '/'.ltrim($path, '/');
                return preg_match($route->getCompiled()->getRegex(), rawurldecode($path));
            })($r,$path)){
                return $r->getController();
            }


        }
        return false;
    }
}
