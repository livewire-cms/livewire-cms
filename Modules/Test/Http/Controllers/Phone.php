<?php

namespace Modules\Test\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
//use Illuminate\Routing\Controller;
use Modules\Backend\Classes\Controller;
use BackendMenu;
use Modules\System\Classes\SideNavManager;
class Phone extends Controller
{
    public $implement = [
        'Modules.Backend.Behaviors.ListController',
        'Modules.Backend.Behaviors.FormController',
        \Modules\Backend\Behaviors\RelationController::class,

    ];
    public $relationConfig = 'config_relation.yaml';
    public $listConfig = [
        'list'=>'config_list.yaml',
    ];
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Modules.Test', 'test');//选中顶部
        SideNavManager::setContext('Modules.Test', 'phone');//选中侧边拦

    }
    public function index()
    {
        $this->asExtension('ListController')->index();
        return view('test::phone.index',['widget'=>$this->widget]);

    }
    public function create()
    {
        $this->asExtension('FormController')->create();
        return view('test::phone.create',['widget'=>$this->widget]);

    }
    public function update($id)
    {
        $this->asExtension('FormController')->update($id);
        return view('test::phone.update', ['widget'=>$this->widget]);

    }
}
