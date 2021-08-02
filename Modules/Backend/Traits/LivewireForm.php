<?php namespace Modules\Backend\Traits;

use Modules\LivewireCore\Html\Helper as HtmlHelper;

//updated

//updatedForm

//makeForm

//onRefresh

trait LivewireForm
{

    public $fields = [];
    public $tabs = [];
    public $secondTabs = [];
    public $form = [];
    public $context;
    public $modelId;

    public $alias;
    protected $widget;




    public function updated($name, $value)
    {
        $this->seeDependsOn($name);
    }

    public function updatedForm()
    {

        $this->dependsOn();

        $this->trigger();


        $this->emit($this->alias.'_'.'setForm',$this->form);//同步数据到child component

    }
    protected function setSometing($form)
    {

    }

    protected function makeForm($widget)
    {

        $widget->form->render();
        $this->setSometing($widget->form);
        $this->widget = $widget;
        $this->alias = $widget->form->alias;
        $this->form['_session_key'] = $widget->form->getSessionKey();

        $this->context = $widget->form->context;
        $this->modelId = $widget->form->model->getKey();

        $outsideTabs = $widget->form->vars['outsideTabs'];
        $primaryTabs = $widget->form->vars['primaryTabs'];
        $secondaryTabs = $widget->form->vars['secondaryTabs'];

        foreach ($outsideTabs as $field) {
            $this->parseField($widget, $field, 'fields');
        }
        foreach ($primaryTabs as $tab=>$primaryTabFields) {
            foreach ($primaryTabFields as $primaryTabField) {
                $this->parseField($widget, $primaryTabField, 'tabs', $tab);
            }
        }
        foreach ($secondaryTabs as $secondaryTab=>$secondaryTabFields) {
            foreach ($secondaryTabFields as $secondaryTabField) {
                $this->parseField($widget, $secondaryTabField, 'secondTabs', $secondaryTab);
            }
        }
        $this->trigger();

    }

    protected function parseField($widget, $field, $type, $tab='')
    {
        //解析自定义widget
        if ($field->type =='widget') {
            $field = $widget->form->getFormWidgets()[$field->valueFrom]->render()->vars['field'];
        }

        //设置partial
        if ($field->type=='partial') {
            $field->html = $widget->form->getController()->makePartial($field->path ?: $field->fieldName, [
                'formModel' => $widget->form->model,
                'formField' => $field,
                'formValue' => $field->value,
                'model'     => $widget->form->model,
                'field'     => $field,
                'value'     => $field->value
            ]);
        }
        //设置options
        $field->options = $field->options();

        if (\Arr::get($field->config,'type')=='fileupload') {
            $this->form['fileList'][$field->arrayName][$field->fieldName] = $field->vars['fileList']->map(function ($file) {
                return [
                    'id' => $file->id,
                    'thumb' => $file->thumbUrl,
                    'path' => $file->pathUrl
                ];
            })->toArray();
        }

        $names = HtmlHelper::nameToArray($field->getName());

        $keyName  = implode('.', $names);
        $field->id = $field->getId();

        //设置值
        if ($field->type=='password') {
            \Arr::set($this->form, $keyName,'');
        }else if ($field->type=='checkboxlist') {
            \Arr::set($this->form, $keyName,$field->value?:[]);

        }else if ($field->type=='checkbox') {
            \Arr::set($this->form, $keyName,$field->value?:[]);

        } else {
            \Arr::set($this->form, $keyName,$field->value);
        }

        unset($field->config['form']);
        unset($field->vars['formWidgets']);
        if ($tab) {
            $this->{$type}[$tab][] = (array)$field;
        } else {
            $this->{$type}[] = (array)$field;
        }
    }

    protected function trigger()
    {


        $this->filterTriggerAttributes($this->fields);
        array_map(function($fields){
            $this->filterTriggerAttributes($fields);
        },$this->tabs);
        array_map(function($fields){
            $this->filterTriggerAttributes($fields);
        },$this->secondTabs);

    }

    protected function filterTriggerAttributes($fields)
    {

        foreach($fields as $field){
            $triggerAction = \Arr::get($field, 'trigger.action');
            $triggerField = \Arr::get($field, 'trigger.field');
            $triggerCondition = \Arr::get($field, 'trigger.condition');
            $triggerFieldModelName = \Arr::get($field, 'trigger.modelNameNotFirst');

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

    protected function dependsOn()
    {


        if(!empty(post('refresh_fields'))){
            $this->onRefresh([]);
        }

    }

    protected function seeDependsOn($name)
    {

        $this->dependsOnContainName($this->fields,$name);
        array_map(function($fields)use($name){
            $this->dependsOnContainName($fields,$name);
        },$this->tabs);
        array_map(function($fields)use($name){
            $this->dependsOnContainName($fields,$name);
        },$this->secondTabs);
    }

    protected function dependsOnContainName($fields,$name)
    {
        $refreshFields = post('refresh_fields');

        foreach ($fields as $field){
            if (in_array($name, $field['dependsFieldModelNames'])){
                $refreshFields[]=$field['fieldName'];
                request()->merge(['refresh_fields_'.$field['fieldName']=>!$field['update']]);
            }
        }
        if(!empty($refreshFields)){
            request()->merge(['refresh_fields'=>$refreshFields]);
        }
    }

}
