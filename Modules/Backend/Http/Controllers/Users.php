<?php

namespace Modules\Backend\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Backend\Classes\Controller;
use BackendMenu;
use Modules\Backend\Models\UserGroup;

use Modules\System\Classes\SideNavManager;

class Users extends Controller
{
    public $implement = [
        'Modules.Backend.Behaviors.ListController',
        'Modules.Backend.Behaviors.FormController',

    ];


    public $listConfig = [
        'list'=>'config_list.yaml',
    ];
    public $formConfig = 'config_form.yaml';
    public $requiredPermissions = ['backend.manage_users'];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Modules.system', 'system');
        SideNavManager::setContext('Modules.Backend', 'users');//选中侧边拦

        // BackendMenu::setContext('Modules.Hello', 'hellos');
        // SideNavManager::setContext('Modules.Hello', 'users');//选中侧边拦

    }
    public function index()
    {

        $this->asExtension('ListController')->index();

        return view('backend::users.index',['widget'=>$this->widget]);

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->asExtension('FormController')->create();


        return view('backend::users.create',['widget'=>$this->widget]);

    }


    public function update($id)
    {
        $this->asExtension('FormController')->update($id);



        return view('backend::users.update', ['widget'=>$this->widget,'cc'=>$this]);
    }

     /**
     * Extends the form query to prevent non-superusers from accessing superusers at all
     */
    public function formExtendQuery($query)
    {



        if (!$this->user->isSuperUser()) {
            $query->where('is_superuser', false);
        }

        // Ensure soft-deleted records can still be managed
        $query->withTrashed();
    }

    /**
     * Add available permission fields to the User form.
     * Mark default groups as checked for new Users.
     */
    public function formExtendFields($form,$fields)
    {
        if ($form->getContext() == 'myaccount') {
            return;
        }
        if (!$this->user->isSuperUser()) {
            $form->removeField('is_superuser');
        }

        /*
         * Add permissions tab
         */
        $form->addTabFields($this->generatePermissionsField());

        /*
         * Mark default groups
         */
        if (!$form->model->exists) {
            $defaultGroupIds = UserGroup::where('is_new_user_default', true)->lists('id');

            $groupField = $form->getField('groups');
            if ($groupField) {
                $groupField->value = $defaultGroupIds;
            }
        }


        $fieldNames =  array_keys($fields);
        if(($editFields = post('edit_fields'))&&is_array($editFields)&&!empty($editFields)){
            foreach($fieldNames as $fieldName){
                if(!in_array($fieldName,$editFields)){
                    $form->removeField($fieldName);
                }
            }
        }
    }

        /**
     * Adds the permissions editor widget to the form.
     * @return array
     */
    protected function generatePermissionsField()
    {
        return [
            'permissions' => [
                'tab' => 'backend::lang.user.permissions',
                'type' => 'Modules\Backend\FormWidgets\PermissionEditor',
                'trigger' => [
                    'action' => 'disable',
                    'field' => 'is_superuser',
                    'condition' => 'checked'
                ]
            ]
        ];
    }


}
