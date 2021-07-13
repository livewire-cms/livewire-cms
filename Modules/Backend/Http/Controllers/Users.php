<?php

namespace Modules\Backend\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Backend\Classes\Controller;
use BackendMenu;
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
}
