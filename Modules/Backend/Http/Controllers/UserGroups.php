<?php namespace Modules\Backend\Http\Controllers;

use BackendMenu;
use Modules\Backend\Classes\Controller;


/**
 * Backend user groups controller
 *
 * @package winter\wn-backend-module
 * @author Alexey Bobkov, Samuel Georges
 *
 */
class UserGroups extends Controller
{
    public $implement = [
        'Modules.Backend.Behaviors.ListController',
        'Modules.Backend.Behaviors.FormController',

    ];


    public $listConfig = [
        'list'=>'config_list.yaml',
    ];
    public $formConfig = 'config_form.yaml';

    /**
     * @var array Permissions required to view this page.
     */
    public $requiredPermissions = ['backend.manage_users'];



    public function index()
    {

        $this->asExtension('ListController')->index();


        return view('backend::usergroups.index',['widget'=>$this->widget]);

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->asExtension('FormController')->create();

        return view('backend::usergroups.create',['widget'=>$this->widget]);

    }


    public function update($id)
    {
        $this->asExtension('FormController')->update($id);

        // dd(view()->exists('backend::groups.update'));


        return view('backend::usergroups.update', ['widget'=>$this->widget,'cc'=>$this]);
    }
}
