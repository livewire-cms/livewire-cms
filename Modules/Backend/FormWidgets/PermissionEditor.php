<?php namespace Modules\Backend\FormWidgets;

use Modules\Backend\Classes\FormWidgetBase;
use BackendAuth;
use Modules\LivewireCore\Html\Helper as HtmlHelper;

/**
 * User/group permission editor
 * This widget is used by the system internally on the System / Administrators pages.
 *
 * Available Modes:
 * - radio: Default mode, used by user-level permissions.
 *   Provides three-state control over each available permission. States are
 *      -1: Explicitly deny the permission
 *      0: Inherit the permission's value from a parent source (User inherits from Role)
 *      1: Explicitly grant the permission
 * - checkbox: Used to define permissions for roles. Intended to define a base of what permissions are available
 *   Provides two state control over each available permission. States are
 *      1: Explicitly allow the permission
 *      null: If the checkbox is not ticked, the permission will not be sent to the server and will not be stored.
 *      This is interpreted as the permission not being present and thus not allowed
 * - switch: Used to define overriding permissions in a simpler UX than the radio.
 *   Provides two state control over each available permission. States are
 *      1: Explicitly allow the permission
 *      -1: Explicitly deny the permission
 *
 * Available permissions can be defined in the form of an array of permission codes to allow:
 * NOTE: Users are still not allowed to modify permissions that they themselves do not have access to
 *     availablePermissions: ['some.author.permission', 'some.other.permission', 'etc.some.system.permission']
 *
 * @package winter\wn-backend-module
 * @author Alexey Bobkov, Samuel Georges
 */
class PermissionEditor extends FormWidgetBase
{
    protected $user;

    /**
     * @var string Mode to display the permission editor with. Available options: radio, checkbox, switch
     */
    public $mode = 'radio';

    /**
     * @var array Permission codes to allow to be interacted with through this widget
     */
    public $availablePermissions;

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->fillFromConfig([
            'mode',
            'availablePermissions',
        ]);

        $this->user = BackendAuth::getUser();
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();

        return $this;
        return $this->makePartial('permissioneditor');
    }
    public function getModelName($end='')
    {




        $names = HtmlHelper::nameToArray($this->formField->getName());

        if($end){
            $end = str_replace('.', '___',$end);
            array_push($names, $end);
        }

        foreach ($names as &$name) {
            if (is_numeric($name)) {
                // $name = '['.$name.']';
                $name = ''.$name.'';
            }
        }

        return $this->formField->modelName = 'form.'.implode('.', $names);
    }

    /**
     * Prepares the list data
     */
    public function prepareVars()
    {
        if ($this->formField->disabled) {
            $this->previewMode = true;
        }

        $permissionsData = $this->formField->getValueFromData($this->model);
        if (!is_array($permissionsData)) {
            $permissionsData = [];
        }
        $newPermissions = [];
        foreach ($permissionsData as $kk=>$v){
            $newPermissions[str_replace('.', '___', $kk)]=$v;
        }


        $this->vars['mode'] = $this->mode;
        $this->vars['permissions'] = $this->getFilteredPermissions();
        $this->vars['baseFieldName'] = $this->getFieldName();

        foreach ($this->vars['permissions'] as $tab=>$ps){
            foreach ($ps as $p){
                if(!isset($newPermissions[str_replace('.', '___', $p->code)])){
                    $newPermissions[str_replace('.', '___', $p->code)]= 0;
                }
            }

        }

        $this->vars['permissionsData'] = $permissionsData;
        $this->vars['field'] = $this->formField;

        $this->formField->value = $newPermissions;
        $this->formField->html   = $this->makePartial('default');

        // dd($this);
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        if(is_array($value)){
            $newValue = [];
            foreach($value as $k=>$v){
                $newValue[str_replace('___', '.', $k)]= $v;
            }
            $value = $newValue;
        }

        if ($this->user->isSuperUser()) {
            return is_array($value) ? $value : [];
        }

        return $this->getSaveValueSecure($value);
    }
     /**
     * Returns the value for this form field,
     * supports nesting via HTML array.
     * @return string
     */
    public function getLoadValue()
    {

        if ($this->formField->value !== null) {
            return $this->formField->value;
        }

        $defaultValue = !$this->model->exists
            ? $this->formField->getDefaultFromData($this->data ?: $this->model)
            : null;
        return $this->formField->getValueFromData($this->data ?: $this->model, $defaultValue);
    }

    /**
     * @inheritDoc
     */
    protected function loadAssets()
    {
        // $this->addCss('css/permissioneditor.css', 'core');
        // $this->addJs('js/permissioneditor.js', 'core');
    }

    /**
     * Returns a safely parsed set of permissions, ensuring the user cannot elevate
     * their own permissions or permissions of another user above their own.
     *
     * @param string $value
     * @return array
     */
    protected function getSaveValueSecure($value)
    {
        $newPermissions = is_array($value) ? array_map('intval', $value) : [];

        if (!empty($newPermissions)) {
            $existingPermissions = $this->model->permissions ?: [];

            $allowedPermissions = array_map(function ($permissionObject) {
                return $permissionObject->code;
            }, \Arr::flatten($this->getFilteredPermissions()));

            foreach ($newPermissions as $permission => $code) {
                if (in_array($permission, $allowedPermissions)) {
                    $existingPermissions[$permission] = $code;
                }
            }

            $newPermissions = $existingPermissions;
        }

        return $newPermissions;
    }

    /**
     * Returns the available permissions; removing those that the logged-in user does not have access to
     *
     * @return array The permissions that the logged-in user does have access to ['permission-tab' => $arrayOfAllowedPermissionObjects]
     */
    protected function getFilteredPermissions()
    {
        // dd($this->user);
        $permissions = BackendAuth::listTabbedPermissions();

        foreach ($permissions as $tab => $permissionsArray) {
            foreach ($permissionsArray as $index => $permission) {
                if (!$this->user->hasAccess($permission->code) ||
                    (
                        is_array($this->availablePermissions) &&
                        !in_array($permission->code, $this->availablePermissions)
                    )) {
                    unset($permissionsArray[$index]);
                }
            }

            if (empty($permissionsArray)) {
                unset($permissions[$tab]);
            }
            else {
                $permissions[$tab] = $permissionsArray;
            }
        }
        // dd($permissions);


        return $permissions;
    }
}
