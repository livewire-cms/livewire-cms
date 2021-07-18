<?php namespace Modules\Hello\Http\Controllers;

// use Illuminate\Routing\Controller;
use Modules\Backend\Classes\Controller;
use Closure;
use Str;
use BackendAuth;
use BackendMenu;
use Modules\System\Classes\SideNavManager;
use Modules\Hello\Models\Phone;

class Hellos extends Controller
{


    public $implement = [
        'Modules.Backend.Behaviors.ListController',
        'Modules.Backend.Behaviors.FormController',
        \Modules\Backend\Behaviors\RelationController::class,
    ];

    public $relationConfig = 'config_relation.yaml';




    public $listConfig = [
        'list'=>'config_list.yaml',
        'list1'=>'config_list.yaml',
    ];
    public $formConfig = 'config_form.yaml';


    // public $requiredPermissions = ['hello.hellos.index'];


    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Modules.Hello', 'hellos');
        SideNavManager::setContext('Modules.Hello', 'hello');//选中侧边拦

    }


    public function index()
    {


        // dd(BackendAuth::listTabbedPermissions());
        // dd(SideNavManager::instance()->findSettingItem('modules.blog','hello'),SideNavManager::instance()->listItems('modules.hello'));
        // dd(BackendMenu::listSideMenuItems('Modules.Hello','hellos'),BackendMenu::listMainMenuItems());


        $this->asExtension('ListController')->index();

        // dd($this->widget->list->render()->vars);
        // dd($this->widget);
        return view('hello::hellos.index',['widget'=>$this->widget]);

    }

    public function create()
    {
        $this->asExtension('FormController')->create();
        // $this->formRender();
        // dd($this->formRender());
        // dd($this->widget->form);
        // dd($this->widget->list->render()->vars);
        // $this->widget->form->render();//设置vars数据
        // dd($this->widget->form->vars);//拿到vars 数据渲染
        // dd($this);
         [
            "sessionKey" => null,
            "outsideTabs" => 'Modules\Backend\Classes\FormTabs {#1584 ▶}',
            "primaryTabs" => 'Modules\Backend\Classes\FormTabs {#1603 ▶}',
            "secondaryTabs" => 'Modules\Backend\Classes\FormTabs {#1601 ▶}',
         ];
        return view('hello::hellos.create',['widget'=>$this->widget,'cc'=>$this]);

    }

    // public function index()
    // {
    //     return 'index';
    // }
    public function update($id)
    {
        $this->asExtension('FormController')->update($id);
        // dd($this->vars);
        // dd($this->widget);
        // dd($this);
        // return $id;
        // $this->widget->form->render();
        // $this->relationRender('worlds'); //$widget->
        // $this->relationRender('categories'); //$widget-> //ajax 请求不用加载
        // dd($a);
        // dd($a->vars['relationViewWidget']->render());
        // dd($a->vars['']);
        // dd($a);
        // dd($this->widget->form);
        // dd($this->widget,$this->widget->relationWorldsViewList->render(),$a);

        // dd($this);


        return view('hello::hellos.update', ['widget'=>$this->widget]);
    }

    public function formExtendModel($model)
    {
        /*
         * Init proxy field model if we are creating the model
         * and the context is proxy fields.
         */
        if (!$model->phone) {
            $model->phone = new Phone;
        }


        return $model;
    }
}
