<?php

namespace Modules\Test\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
//use Illuminate\Routing\Controller;
use Modules\Backend\Classes\Controller;
use BackendMenu;
use Modules\System\Classes\SideNavManager;
use Modules\Test\Models\Foo as ModelsFoo;

class Foo extends Controller
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
        if(post('custome_form')){
            $this->formConfig = post('custome_form').'_config_form.yaml';
        }
        parent::__construct();
        BackendMenu::setContext('Modules.Test', 'test');//选中顶部
        SideNavManager::setContext('Modules.Test', 'foo');//选中侧边拦

    }
    public function index()
    {
        $this->asExtension('ListController')->index();
        return view('test::foo.index',['widget'=>$this->widget]);

    }
    public function create($context='')
    {
        $this->asExtension('FormController')->create($context);
        return view('test::foo.create',['widget'=>$this->widget]);

    }
    public function update($id,$context='')
    {
        $this->asExtension('FormController')->update($id,$context);
        return view('test::foo.update', ['widget'=>$this->widget]);

    }
    /**
     * Extends the list query to hide superusers if the current user is not a superuser themselves
     */
    public function listExtendQuery($query)
    {
        $query->withTrashed();

    }
      /**
     * Extends the form query to prevent non-superusers from accessing superusers at all
     */
    public function formExtendQuery($query)
    {


        // Ensure soft-deleted records can still be managed
        $query->withTrashed();
    }

    public function onTest($component,$params)
    {

        $component->notification()->success(
            $title = 'Success',
            $description = '我执行了'.__METHOD__.'传的参数是'.json_encode($params)
        );
        $component->dialog()->success(
            $title = 'Profile saved',
            $description = 'Your profile was successfull saved'
        );
        $component->dialog()->error(
            $title = 'Error !!!',
            $description = 'Your profile was not saved'
        );

        // or use a full syntax
        $component->dialog()->show([
            'title'       => 'Profile saved!',
            'description' => 'Your profile was successfull saved',
            'icon'        => 'success'
        ]);
        // dd($params);
    }
    public function onSuccess($component,$params)
    {

        $component->notification()->success(
            $title = 'Success',
            $description = '我执行了'.__METHOD__.'传的参数是'.json_encode($params)
        );
        $component->dialog()->success(
            $title = 'Profile saved',
            $description = 'Your profile was successfull saved'
        );

    }
    public function onError($component,$params)
    {

        $component->notification()->error(
            $title = 'Error',
            $description = '我执行了'.__METHOD__.'传的参数是'.json_encode($params)
        );

        $component->dialog()->error(
            $title = 'Error !!!',
            $description = 'Your profile was not saved'
        );
        // dd($params);
    }
    public function onInfo($component,$params)
    {

        $component->notification()->info(
            $title = 'Info',
            $description = '我执行了'.__METHOD__.'传的参数是'.json_encode($params)
        );

        $component->dialog()->info(
            $title = 'Info !!!',
            $description = 'Your profile was not saved'
        );
        // dd($params);
    }
    public function onConfirm($component,$params)
    {

        $agreeMethod = $params['agree_method'];
        $title = \Arr::get($params,'title','Are you Sure?');

        $description = \Arr::get($params,'description','Save the information?');
        $icon = \Arr::get($params,'icon','question');
        $confirm_label = \Arr::get($params,'confirm_label','Yes, save it');
        $cancel_label = \Arr::get($params,'cancel_label','No, Cancel');

        // unset($params['agree_method']);
        // unset($params['title']);
        // unset($params['description']);
        // unset($params['icon']);
        // unset($params['confirm_label']);
        // unset($params['cancel_label']);
        $params = \Arr::get($params, 'params', []);

        $component->notification()->info(
             'Info',
             '我执行了'.__METHOD__.'传的参数是'.json_encode($params)
        );

        $component->dialog()->confirm([
            'title'       => $title,
            'description' => $description,
            'icon'        => $icon,
            'accept'      => [
                'label'  => $confirm_label,
                'method' => 'onAction',
                'params' => [
                    $agreeMethod,
                    $params
                ],
            ],
            'reject' => [
                'label'  => $cancel_label,
                // 'method' => 'cancel',
            ],
        ]);
        // dd($params);
    }

    public function onDelete($component,$params)
    {
        $checked = \Arr::get($params,'checked');

        foreach($checked as $checkedId)
        {
            ModelsFoo::find($checkedId)->delete();
        }
        $component->notification()->info(
            $title = 'Info',
            $description = '删除成功'
        );
        $component->refresh();


    }

    public function formExtendFields($formWidget,$fields)
    {

        $fieldNames =  array_keys($fields);
        if(($editFields = post('edit_fields'))&&is_array($editFields)&&!empty($editFields)){
            foreach($fieldNames as $fieldName){
                if(!in_array($fieldName,$editFields)){
                    $formWidget->removeField($fieldName);
                }
            }
        }


    }


}
