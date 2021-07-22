<?php namespace Modules\Backend\Http\Controllers;


use View;
use Response;
use BackendMenu;
use Modules\Backend\Classes\Controller;
// use System\Classes\SettingsManager;
use Modules\System\Classes\SideNavManager;

/**
 * Backend user groups controller
 *
 * @package winter\wn-backend-module
 * @author Alexey Bobkov, Samuel Georges
 *
 */
class UserRoles extends Controller
{
    /**
     * @var array Extensions implemented by this controller.
     */
    public $implement = [
        \Modules\Backend\Behaviors\FormController::class,
        \Modules\Backend\Behaviors\ListController::class
    ];

    /**
     * @var array `FormController` configuration.
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var array `ListController` configuration.
     */
    public $listConfig = 'config_list.yaml';

    /**
     * @var array Permissions required to view this page.
     */
    public $requiredPermissions = ['backend.manage_users'];

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Modules.System', 'system');
        SideNavManager::setContext('Modules.Backend', 'userroles');

    }

    public function index()
    {

        $this->asExtension('ListController')->index();


        return view('backend::userroles.index',['widget'=>$this->widget]);

    }
     /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->asExtension('FormController')->create();


        return view('backend::userroles.create',['widget'=>$this->widget]);

    }


    public function update($id)
    {
        $this->asExtension('FormController')->update($id);



        return view('backend::userroles.update', ['widget'=>$this->widget,'cc'=>$this]);
    }

    /**
     * Add available permission fields to the Role form.
     */
    public function formExtendFields($form)
    {
        /*
         * Add permissions tab
         */
        $form->addTabFields($this->generatePermissionsField());
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
                'mode' => 'checkbox'
            ]
        ];
    }
}
