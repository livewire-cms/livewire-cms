<?php namespace Modules\Backend\Helpers;

use URL;
use File;
use Html;
use Config;
use Request;
use Redirect;
use Modules\System\Helpers\DateTime as DateTimeHelper;


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

     /**
     * Returns the HTML for a date formatted in the backend.
     * Supported for formatAlias:
     *   time             -> 6:28 AM
     *   timeLong         -> 6:28:01 AM
     *   date             -> 04/23/2016
     *   dateMin          -> 4/23/2016
     *   dateLong         -> April 23, 2016
     *   dateLongMin      -> Apr 23, 2016
     *   dateTime         -> April 23, 2016 6:28 AM
     *   dateTimeMin      -> Apr 23, 2016 6:28 AM
     *   dateTimeLong     -> Saturday, April 23, 2016 6:28 AM
     *   dateTimeLongMin  -> Sat, Apr 23, 2016 6:29 AM
     * @return string
     */
    public function dateTime($dateTime, $options = [])
    {


        extract(array_merge([
            'defaultValue' => '',
            'format' => null,
            'formatAlias' => null,
            'jsFormat' => null,
            'timeTense' => false,
            'timeSince' => false,
            'ignoreTimezone' => false,
        ], $options));

        if (!$dateTime) {
            return '';
        }

        $carbon = DateTimeHelper::makeCarbon($dateTime);

        if ($jsFormat !== null) {
            $format = $jsFormat;
        }
        else {
            $format = DateTimeHelper::momentFormat($format);
        }

        $attributes = [
            'datetime' => $carbon,
            'data-datetime-control' => 1,
        ];

        if ($ignoreTimezone) {
            $attributes['data-ignore-timezone'] = true;
        }

        if ($timeTense) {
            $attributes['data-time-tense'] = 1;
        }
        elseif ($timeSince) {
            $attributes['data-time-since'] = 1;
        }
        elseif ($format) {
            $attributes['data-format'] = $format;
        }
        elseif ($formatAlias) {
            $attributes['data-format-alias'] = $formatAlias;
        }
        return '<time'.Html::attributes($attributes).'>'.e($defaultValue).'</time>'.PHP_EOL;
    }

}
