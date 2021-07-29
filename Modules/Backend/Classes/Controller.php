<?php namespace Modules\Backend\Classes;

use Illuminate\Routing\Controller as BaseController;

use Closure;
use Str;
use BackendAuth;
use Request;
class Controller extends BaseController
{

    use \Modules\LivewireCore\Extension\ExtendableTrait;
    use \Modules\System\Traits\ConfigMaker;
    use \Modules\System\Traits\ViewMaker;
    use \Modules\System\Traits\EventEmitter;

    public $implement = [];

    public $relationConfig;

    /**
     * @var array Default methods which cannot be called as actions.
     */
    public $hiddenActions = [];

    /**
     * @var array Defines a collection of actions available without authentication.
     */
    protected $publicActions = [];
    public $listConfig = [

    ];
    public $formConfig ;

    /**
     * @var object Reference the logged in admin user.
     */
    protected $user;
    /**
     * @var string Page title
     */
    public $pageTitle = 'none';
    /**
     * @var array Collection of WidgetBase objects used on this page.
     */
    public $widget;

    protected $action;
    protected $params;

     /**
     * @var array Permissions required to view this page.
     */
    protected $requiredPermissions = [];

    public function __construct()
    {


        $this->middleware(function ($request, $next) {

            $this->setUser();
            $this->verifyPermissions();

            return $next($request);
        });

        $this->extendableConstruct();
    }

    public function setUser()
    {
        // dd(321,BackendAuth::getUser());
        $this->user = BackendAuth::getUser();//todo 后台用户登录

    }

    public function verifyPermissions()
    {
        if ($this->requiredPermissions && !$this->user->hasAnyAccess($this->requiredPermissions)) {
            abort(403);
        }
    }
    /**
    * Extend this object properties upon construction.
    */
    public static function extend(Closure $callback)
    {
        self::extendableExtendCallback($callback);
    }

    public function __get($name)
    {
        return $this->extendableGet($name);
    }

    public function __set($name, $value)
    {
        $this->extendableSet($name, $value);
    }

    public function __call($name, $params)
    {
        return $this->extendableCall($name, $params);
    }

    public static function __callStatic($name, $params)
    {
        return self::extendableCallStatic($name, $params);
    }



        /**
     * Returns a unique ID for the controller and route. Useful in creating HTML markup.
     */
    public function getId($suffix = null)
    {
        $id = class_basename(get_called_class()) . '-' . $this->action;
        if ($suffix !== null) {
            $id .= '-' . $suffix;
        }

        return $id;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }
    public function setParams($params)
    {
        $this->params = $params;
    }
    public function pageAction()
    {
        if (!$this->action) {
            return;
        }


        $this->execPageAction($this->action, $this->params);

    }
    protected function execPageAction($actionName, $parameters)
    {
        $result = null;
        if (!$this->actionExists($actionName)) {
            abort(404);
        }

        // Execute the action
        $result = call_user_func_array([$this, $actionName], $parameters);
        // dd($result);
        return $result;
    }

    /**
     * This method is used internally.
     * Determines whether an action with the specified name exists.
     * Action must be a class public method. Action name can not be prefixed with the underscore character.
     * @param string $name Specifies the action name.
     * @param bool $internal Allow protected actions.
     * @return boolean
     */
    public function actionExists($name, $internal = false)
    {
        if (!strlen($name) || substr($name, 0, 1) == '_' || !$this->methodExists($name)) {
            return false;
        }

        foreach ($this->hiddenActions as $method) {
            if (strtolower($name) == strtolower($method)) {
                return false;
            }
        }

        $ownMethod = method_exists($this, $name);

        if ($ownMethod) {
            $methodInfo = new \ReflectionMethod($this, $name);
            $public = $methodInfo->isPublic();
            if ($public) {
                return true;
            }
        }

        if ($internal && (($ownMethod && $methodInfo->isProtected()) || !$ownMethod)) {
            return true;
        }

        if (!$ownMethod) {
            return true;
        }

        return false;
    }



    public function handleError($e)
    {
        throw $e;
    }
       /**
     * @return string The fatal error message
     */
    public function getFatalError()
    {
        return '';
    }

    public function getAjaxHandler()
    {
        if (!Request::ajax() || Request::method() != 'POST') {
            return null;
        }

        if ($handler = Request::header('X_WINTER_REQUEST_HANDLER')) {
            return trim($handler);
        }

        return null;
    }
}
