<?php namespace Modules\Backend\Helpers;

use URL;
use File;
use Html;
use Config;
use Request;
use Redirect;


/**
 * Backend Helper
 *
 * @package winter\wn-backend-module
 * @see \Backend\Facades\Backend
 * @author Alexey Bobkov, Samuel Georges
 */
class Backend
{
    /**
     * Returns the backend URI segment.
     */
    public function uri()
    {
        //todo cms
        return Config::get('cms.backendUri', 'backend');
    }

    /**
     * Returns a URL in context of the Backend
     */
    public function URL($path = null, $parameters = [], $secure = null)
    {
        return URL::to($this->uri() . '/' . $path, $parameters, $secure);
    }



    /**
     * Create a new redirect response to a given backend path.
     */
    public function redirect($path, $status = 302, $headers = [], $secure = null)
    {
        return Redirect::to($this->uri() . '/' . $path, $status, $headers, $secure);
    }

    /**
     * Create a new backend redirect response, while putting the current URL in the session.
     */
    public function redirectGuest($path, $status = 302, $headers = [], $secure = null)
    {
        return Redirect::guest($this->uri() . '/' . $path, $status, $headers, $secure);
    }

    /**
     * Create a new redirect response to the previously intended backend location.
     */
    public function redirectIntended($path, $status = 302, $headers = [], $secure = null)
    {
        return Redirect::intended($this->uri() . '/' . $path, $status, $headers, $secure);
    }


}
