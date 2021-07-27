<?php

namespace Modules\Backend\View\Livewire\Widgets\Form;

use Livewire\Component;
use Route;
use Modules\LivewireCore\Html\Helper as HtmlHelper;

class Repeater extends Component
{
    public $fields = [];
    public $tabs = [];
    public $secondTabs = [];

    public $allFields = [];


    // public $field; //字段对象


    public $form = [];

    public $parentContext;
    public $context;
    public $modelId;

    public $field;


    public $relation_field;

    public $formAlias;

    protected $widget;


    public function mount()
    {
        $args = func_get_args();
        // dd($this->field);
        $widget = $args[1];
        $this->widget = $widget;
        // dd($this->widget, $this->relation_field);
        // $widget = $this->widget;
        $this->parentContext = $widget->form->getContext();
        $this->modelId = $widget->form->model->getKey();
        $this->formAlias = $this->widget->form->alias;


        // dd($this->modelId);
        $this->form[$this->field['alias'].'_loaded'] = 1;
        // $this->form['formExtraForm0PointEvidence'.'_loaded'] = 1;
        $ww = $widget->{$this->field['alias']};
        // dd($this->field);
        // if($this->field['alias']=='formExtraForm0PointEvidence'){
        // $ww = $widget->{$this->field['alias']};
        // // dd($widget,$ww,$ww->render());
        // }
        // dd($widget,$this->field);
        // $ww->render();
        // dd($ww);
        $formWidgets = $ww->vars['formWidgets'];
        // dd($ww,$widget,$widget->formExtraForm0PointEvidence->render());
        foreach ($formWidgets as $indexValue=>$formWidget){
            $this->init($formWidget,$indexValue);
        }
        // dd($this->field);
        // dd($args,$widget);
        // dd($this);
        // dd($this->allFields);
        $this->trigger();

    }

    public function init($form,$indexValue)
    {
        $form->render();



        $this->form['_session_key'] = $form->getSessionKey();


        $this->context = $form->context;
        $this->modelId = $form->model->getKey();



        $outsideTabs = $form->vars['outsideTabs'];
        $primaryTabs = $form->vars['primaryTabs'];
        $secondaryTabs = $form->vars['secondaryTabs'];

        // dd($outsideTabs,$secondaryTabs);
        foreach ($outsideTabs as $field) {
            $this->parseField($form, $field, 'fields','',$indexValue);
        }

        foreach ($primaryTabs as $tab=>$primaryTabFields) {
            foreach ($primaryTabFields as $primaryTabField) {
                $this->parseField($form, $primaryTabField, 'tabs', $tab,$indexValue);
            }
        }




        foreach ($secondaryTabs as $secondaryTab=>$secondaryTabFields) {
            foreach ($secondaryTabFields as $secondaryTabField) {
                $this->parseField($form, $secondaryTabField, 'secondTabs', $secondaryTab,$indexValue);
            }
        }


    }

    public function onAddItem()
    {
        // dd($this->modelId);
        request()->merge($this->form)->merge(['_relation_field'=>$this->relation_field]);
        // dd(request()->all());
//
        // dd($this->form,$this->relation_field);
        $c = find_controller_by_url(request()->input('fingerprint.path'));
        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        $c->asExtension('FormController')->create();//不需要父级id

        // if ($this->parentContext=='create') {
        //     $c->asExtension('FormController')->create();
        // } elseif ($this->parentContext=='update') {
        //     // dd($this->form);
        //     $c->asExtension('FormController')->update($this->modelId);
        // }
        // dd($c->widget,$this->relation_field);

        // dd($c->widget->{$this->field['alias']}->onAddItem());
        $vars = $c->widget->{$this->field['alias']}->onAddItem()->vars;
        $form = $vars['widget'];
        $form->render();
        // dd($form);
        $indexValue = $vars['indexValue'];

        // $this->widget = $widget;

        $this->form['_session_key'] = $form->getSessionKey();


        // $this->context = $form->context;
        // $this->modelId = $form->model->getKey();



        $outsideTabs = $form->vars['outsideTabs'];
        $primaryTabs = $form->vars['primaryTabs'];
        $secondaryTabs = $form->vars['secondaryTabs'];

        // dd($outsideTabs,$secondaryTabs);
        foreach ($outsideTabs as $field) {
            $this->parseField($form, $field, 'fields','',$indexValue);
        }

        foreach ($primaryTabs as $tab=>$primaryTabFields) {
            foreach ($primaryTabFields as $primaryTabField) {
                $this->parseField($form, $primaryTabField, 'tabs', $tab,$indexValue);
            }
        }




        foreach ($secondaryTabs as $secondaryTab=>$secondaryTabFields) {
            foreach ($secondaryTabFields as $secondaryTabField) {
                $this->parseField($form, $secondaryTabField, 'secondTabs', $secondaryTab,$indexValue);
            }
        }

        // $widgetObj = new class(){};
        // $widgetObj->form = $form;

        $this->widget = $c->widget;
        // dd($this->form);

        $this->trigger();

        if($this->relation_field){
            $this->emit('setRelationFormProperty', ['name'=>$this->getKeyName(),'value'=>\Arr::get($this->form, $this->getKeyName())]);
        }else{
            $this->emit('setFormProperty', ['name'=>$this->getKeyName(),'value'=>\Arr::get($this->form, $this->getKeyName())]);

        }

    }

    public function onRemoveItem($index)
    {
        $value = \Arr::get($this->form, $this->getKeyName());
        if(!empty($value)){
            unset($value[$index]);
            $value = array_values($value);
            \Arr::set($this->form, $this->getKeyName(), $value);
        }
        unset($this->allFields[$index]);
        $this->allFields = array_values($this->allFields);

        $this->trigger();


        if($this->relation_field){
            $this->emit('setRelationFormProperty', ['name'=>$this->getKeyName(),'value'=>\Arr::get($this->form, $this->getKeyName())]);
        }else{
            $this->emit('setFormProperty', ['name'=>$this->getKeyName(),'value'=>\Arr::get($this->form, $this->getKeyName())]);

        }
    }



    public function getKeyName()
    {
        $keyName = $this->field['modelName'];
        if(\Str::startsWith($keyName, 'form.')){
            $keyName = substr_replace($keyName,'',strpos($keyName,'form.'),strlen('form.'));
        };
        return  $keyName;

    }
    protected function parseField($form, $field, $type, $tab='',$indexValue)
    {

        // if($field->fieldName=='groups'){
        //     dd($field);
        // }
        // dd($field);



        //解析自定义widget
        if ($field->type =='widget') {
            // dd($form->getFormWidgets()['avatars']->render());
            // dd($form,$primaryTabField->valueFrom);
            // dd($form->getFormWidgets()[$field->valueFrom]->render());

            $field = $form->getFormWidgets()[$field->valueFrom]->render()->vars['field'];
            // dd($field);
            // dd($primaryTabField);
            // $primaryTabs[$tab][$tabField] = $primaryTabField;
            // dd($primaryTabField->getId());
        }







        //设置partial
        if ($field->type=='partial') {
            $field->html = $form->getController()->makePartial($field->path ?: $field->fieldName, [
                'formModel' => $form->model,
                'formField' => $field,
                'formValue' => $field->value,
                'model'     => $form->model,
                'field'     => $field,
                'value'     => $field->value
            ]);
        }



        //设置options
        if ($field->type =='radio') {
            if (is_callable($field->options)) {
                $field->options = $field->options();
            }
            // dd($primaryTabField);
        } elseif ($field->type =='dropdown') {
            if (is_callable($field->options)) {
                $field->options = $field->options();
            }

        }elseif ($field->type =='checkboxlist') {
            if (is_callable($field->options)) {
                $field->options = $field->options();
            }
        }


        //设置值
        $names = HtmlHelper::nameToArray($field->arrayName);


        // if ($field->type=='password') {
        //     // $this->form[$field->arrayName][$field->fieldName] = '';
        //     $this->form[$names[0]][$names[1]][$names[2]][$field->fieldName] = $field->value;

        // }else if ($field->type=='checkboxlist') {
        //     // $this->form[$field->arrayName][$field->fieldName] = $field->value?:[];
        //     $this->form[$names[0]][$names[1]][$names[2]][$field->fieldName] = $field->value?:[];
        // } else {
        //     // $this->form[$field->arrayName][$field->fieldName] = $field->value;
        //     $this->form[$names[0]][$names[1]][$names[2]][$field->fieldName] = $field->value;
        // }

        //设置上传文件

        // if(!isset($field->config['type'])){
        //     dd($field);
        // }
        if (\Arr::get($field->config,'type')=='fileupload') {

            //todo 过滤$field->vars['fileList']
            $this->form['fileList'][$field->arrayName][$field->fieldName] = $field->vars['fileList']->map(function ($file) {
                return [
                    'id' => $file->id,
                    'thumb' => $file->thumbUrl,
                    'path' => $file->pathUrl
                ];
            })->toArray();
            // dd($form->getFormWidgets());
            // $field = $form->getFormWidgets()[$field->valueFrom]->render();
        }


        $names = HtmlHelper::nameToArray($field->getName());
        // dd($names);

        foreach ($names as &$name) {
            if (is_numeric($name)) {
                // $name = $name;
            }
        }
        // $field->modelName = 'form.'.implode('.', $names);
        // $field->modelName = str_replace(['.['], ['['], $field->modelName);
        $field->id = $field->getId();

        $keyName = implode('.', $names);
        //设置值
        if ($field->type=='password') {
            \Arr::set($this->form, $keyName,'');
            // $this->form[$field->arrayName][$field->fieldName] = '';
        }else if ($field->type=='checkboxlist') {
            \Arr::set($this->form, $keyName,$field->value?:[]);

        } else if ($field->type=='checkbox') {
            \Arr::set($this->form, $keyName,$field->value?:[]);

        }else {
            \Arr::set($this->form, $keyName,$field->value);

            // $this->form[$field->arrayName][$field->fieldName] = $field->value;
        }
        if ($tab) {
            $this->allFields[$indexValue][$type][$tab][] = (array)$field;
        } else {
            $this->allFields[$indexValue][$type][] = (array)$field;
        }
        // dd($field);



    }

    public function updated($name, $value)
    {
        $this->trigger();


        $names = explode('.', $name);
        array_shift($names);

        if($this->relation_field){
            $this->emit('setRelationFormProperty', ['name'=>$name,'value'=>\Arr::get($this->form,implode('.', $names))]);
        }else{
            $this->emit('setFormProperty', ['name'=>$name,'value'=>\Arr::get($this->form,implode('.', $names))]);
        }
    }



    public function setForm($form)
    {
        $this->form = $form;
        $this->trigger();

    }

    public function trigger()
    {
        $this->filterTriggerAttributes($this->fields);
        array_map(function($tab){
            foreach($tab as $fields){
                $this->filterTriggerAttributes($fields);
            }
        },$this->tabs);
        array_map(function($tab){
            foreach($tab as $fields){
                $this->filterTriggerAttributes($fields);
            }
        },$this->secondTabs);
    }

    protected function filterTriggerAttributes($fields)
    {

        foreach($fields as $field){
            $triggerAction = \Arr::get($field, 'trigger.action');
            $triggerField = \Arr::get($field, 'trigger.field');
            $triggerCondition = \Arr::get($field, 'trigger.condition');
            $triggerFieldModelName = \Arr::get($field, 'trigger.modelName');

            $actions = explode('|', $triggerAction);

            foreach($actions as $action){
                if($action=='empty'){
                    $triggerFieldValue = \Arr::get($this->form, $triggerFieldModelName);

                    $fieldValue = \Arr::get($this->form, $field['modelNameNotFirst']);

                    if($this->onConditionChanged($triggerFieldValue,$triggerCondition)){
                        if(!$fieldValue|| empty($fieldValue)){
                        }else{
                            if(is_array($triggerFieldValue)){
                                \Arr::set($this->form, $field['modelNameNotFirst'], []);
                            }else{
                                \Arr::set($this->form, $field['modelNameNotFirst'], '');
                            }
                        }

                    }


                }
            }


        }

    }
    protected function onConditionChanged($fieldValue,$triggerCondition)
    {

        if(\Str::contains($triggerCondition, 'value')){
            preg_match_all('/[^[\]]+(?=])/',$triggerCondition,$matches);
            $triggerCondition = 'value';
            $triggerConditionValue = $matches[0]??[];
            if(!$triggerConditionValue){
                $triggerConditionValue = [];
            }
        }
        if($triggerCondition=='checked'){
            if($fieldValue&&!empty($fieldValue)){
                return true;
            }
        }elseif($triggerCondition=='unchecked'){
            if(!$fieldValue||empty($fieldValue)){
                return true;
            }
        }elseif($triggerCondition=='value'){
            if(is_array($fieldValue)){
                if(!empty(array_intersect($fieldValue,$triggerConditionValue))){
                    return true;
                }
            }else{
                foreach ($triggerConditionValue as $val){
                    if($val==$fieldValue){
                        return true;
                    }
                }
            }
        }
        return false;
    }

    protected function getListeners()
    {
        return [
            $this->formAlias.'_setForm' =>'setForm'
        ];
    }




    public function render()
    {
        // dd($this->form);
        return view('backend::widgets.form.repeater',['widget'=>$this->widget]);
    }
}
