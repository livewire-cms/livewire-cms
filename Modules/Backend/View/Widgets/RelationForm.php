<?php

namespace Modules\Backend\View\Widgets;

use Livewire\Component;
use Route;
use Modules\LivewireCore\Html\Helper as HtmlHelper;


class RelationForm extends Component
{
    // protected $widget;

    public $fields = [];
    public $tabs = [];
    public $secondTabs = [];

    public $form = [];

    public $parentContext;
    public $context;
    public $modelId;
    public $relation_field;
    public $manageId;
    public $parentSessionKey;//也就是relationsessionkey
    public $sessionKey;

    public $update;


    public $relationFormModal;

    protected $listeners = ['onRelationButtonCreate','onRelationClickViewList'];


    public function onRelationButtonCreate($data)
    {
        // dd($data);
        $this->resetData();
        $this->relationFormModal=true;

        $this->update = !$this->update;
        $this->relation_field = $data['_relation_field'];
        $this->context = 'create';
        $this->parentContext = $data['context'];
        $this->modelId = $data['modelId'];
        $this->parentSessionKey = $data['_relation_session_key'];
        // $data['_relation_mode'] = 'form';
        request()->merge($data);
        $pre = 'relation'.ucfirst(\Str::camel($this->relation_field));


        //todo 找到控制器((
        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if(!$c){
            throw new \RuntimeException('Could not find controller');
        }
        $c->setAction($this->parentContext);
        $c->setParams([$this->modelId]);
        // $c->asExtension('FormController')->update($this->modelId);//todo
        $c->onRelationButtonCreate();
        // dd($c);

        $c->widget->{$pre.'ManageForm'}->render();

        $this->sessionKey = $c->widget->{$pre.'ManageForm'}->getSessionKey();



        $this->prepareVars($c->widget, $pre);

    }

    public function onRelationClickViewList($data)
    {
        $this->relationFormModal=true;

        $this->relation_field = $data['_relation_field'];
        $this->context = 'update';
        $this->parentContext = $data['context'];
        $this->modelId = $data['modelId'];
        $this->manageId = $data['manage_id'];
        $this->parentSessionKey = $data['_relation_session_key'];

        request()->merge($data);
        $pre = 'relation'.ucfirst(\Str::camel($this->relation_field));

        //todo 找到控制器((
        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if(!$c){
            throw new \RuntimeException('Could not find controller');
        }
        $c->setAction($this->parentContext);
        $c->setParams([$this->modelId]);
        // $c->asExtension('FormController')->update($this->modelId);
        $c->onRelationClickViewList();
        // dd($c);

        $c->widget->{$pre.'ManageForm'}->render();
        $this->sessionKey = $c->widget->{$pre.'ManageForm'}->getSessionKey();

        $this->prepareVars($c->widget, $pre);
    }


    public function prepareVars($widget,$pre)
    {
        $widget->{$pre.'ManageForm'}->render();
        // $this->context = $widget->{$pre.'ManageForm'}->context;
        // $this->modelId = $widget->{$pre.'ManageForm'}->model->getKey();


        $outsideTabs = $widget->{$pre.'ManageForm'}->vars['outsideTabs'];
        $primaryTabs = $widget->{$pre.'ManageForm'}->vars['primaryTabs'];
        $secondaryTabs = $widget->{$pre.'ManageForm'}->vars['secondaryTabs'];


        foreach($outsideTabs as $field){
            $this->parseField($widget, $field, 'fields');
         }

         foreach($primaryTabs as $tab=>$primaryTabFields) {
             foreach ($primaryTabFields as $primaryTabField){
                 $this->parseField($widget, $primaryTabField, 'tabs',$tab);

             }
         }




         foreach($secondaryTabs as $secondaryTab=>$secondaryTabFields)
         {
             foreach ($secondaryTabFields as $secondaryTabField){
                 $this->parseField($widget, $secondaryTabField, 'secondTabs',$secondaryTab);
             }

         }

    }

    protected function parseField($widget,$field,$type,$tab='')
    {
        if($field->type =='widget'){
            // dd($widget->form,$primaryTabField->valueFrom);
            // dd($widget->form->getFormWidgets()[$field->valueFrom]->render());
            $field = $widget->form->getFormWidgets()[$field->valueFrom]->render()->vars['field'];

            // dd($primaryTabField);
            // $primaryTabs[$tab][$tabField] = $primaryTabField;
            // dd($primaryTabField->getId());
        }


         if($field->type =='radio'){

            if(is_callable($field->options)){
                $field->options = $field->options();

            }
            // dd($primaryTabField);

        }else if($field->type =='checkboxlist'){
            if(is_callable($field->options)){
                $field->options = $field->options();

            }

        }


        if($field->type=='password'){
            $this->form[$field->arrayName][$field->fieldName] = '';
        }else{
            $this->form[$field->arrayName][$field->fieldName] = $field->value;
        }


        $names = HtmlHelper::nameToArray($field->getName());

        foreach($names as &$name){
            if(is_numeric($name)){
                $name = '['.$name.']';
            }
        }
        $field->modelName = 'form.'.implode('.', $names);
        $field->id = $field->getId();



        if($tab){
            $this->{$type}[$tab][] = (array)$field;
        }else{
            $this->{$type}[] = (array)$field;
        }

    }

    public function mount()
    {


    }

    public function save()
    {
        // dd($this->sessionKey);

        $this->form['_relation_field'] = $this->relation_field;
        $this->form['_relation_mode'] = 'form';
        $this->form['manage_id'] = $this->manageId;
        $this->form['_session_key'] = $this->sessionKey;
        $this->form['_relation_session_key'] = $this->parentSessionKey;
        $this->form['_session_key'] = $this->sessionKey;

        if($this->context=='update'){
            // $this->form["relation{ucfirst($this->relation_field)}ManageFormBreakdown_loaded"] = $this->modelId;
        }
        // dd($this->form);

        // dd($this);

        request()->merge($this->form);

        // dd($this->context,$this->parentContext);
        if($this->context=='create'){
            // $c->asExtension('FormController')->create_onSave();
            $c = find_controller_by_url(request()->input('fingerprint.path'));
            if(!$c){
                throw new \RuntimeException('Could not find controller');
            }
            $c->setAction($this->parentContext);
            $c->setParams([$this->modelId]);
            // $c->asExtension('FormController')->update($this->modelId);
            // dd($this->modelId,$this->relation_field);
            $c->onRelationManageCreate();
            $this->emitTo('backend.widgets.relation_lists','search'.($this->relation_field?'_'.$this->relation_field:''),[//todo 多个list
                'search' =>'',
                'context' => $this->context,
                '_session_key' => $this->parentSessionKey
            ]);
            $this->resetData();

        // dd($c);

        }elseif($this->context=='update'){
            $c = find_controller_by_url(request()->input('fingerprint.path'));

            if(!$c){
                throw new \RuntimeException('Could not find controller');
            }
            // $c->asExtension('FormController')->update($this->modelId);
            $c->setAction($this->parentContext);
            $c->setParams([$this->modelId]);

            $c->onRelationManageUpdate();

            $this->emitTo('backend.widgets.relation_lists','search'.($this->relation_field?'_'.$this->relation_field:''),[//todo 多个list
                'search' =>'',
                'context' => $this->context,
                '_session_key' => $this->parentSessionKey

            ]);
            $this->resetData();



        }


    }

    public function updatedRelationFormModal($value)
    {
        if(!$value){
            $this->resetData();
        }

    }

    public function resetData()
    {
        $this->fields = [];
        $this->tabs = [];
        $this->secondTabs = [];
        $this->parentContext = null;
        $this->context = null;
        $this->modelId = null;
        $this->relation_field = null;
        $this->manageId = null;
        $this->parentSessionKey = null;
        $this->sessionKey = null;
        $this->relationFormModal = false;


    }







    public function render()
    {

        return view('backend::widgets.relation_form');
    }
}
