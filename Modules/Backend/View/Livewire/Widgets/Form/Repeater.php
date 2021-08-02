<?php

namespace Modules\Backend\View\Livewire\Widgets\Form;

use Livewire\Component;
use Route;
use Modules\LivewireCore\Html\Helper as HtmlHelper;

class Repeater extends Component
{
    use \Livewire\WithFileUploads;

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

    public $needSetFields = [];

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
        // dd($ww->getController());
        // dd($ww);
        // dd($this->field);
        // if($this->field['alias']=='formExtraForm0PointEvidence'){
        // $ww = $widget->{$this->field['alias']};
        // // dd($widget,$ww,$ww->render());
        // }
        // dd($widget,$this->field);
        // $ww->render();
        $formWidgets = $ww->vars['formWidgets'];
        // dd($ww,$widget,$widget->formExtraForm0PointEvidence->render());
        foreach ($formWidgets as $indexValue=>$formWidget) {
            $this->init($formWidget, $indexValue);
        }
        // dd($this->field);
        // dd($args,$widget);
        // dd($this);
        // dd($this->allFields);
        $this->trigger();
    }

    protected function init($form, $indexValue)
    {
        unset($this->allFields[$indexValue]);
        $form->render();





        $this->form['_session_key'] = $form->getSessionKey();


        $this->context = $form->context;
        $this->modelId = $form->model->getKey();



        $outsideTabs = $form->vars['outsideTabs'];
        $primaryTabs = $form->vars['primaryTabs'];
        $secondaryTabs = $form->vars['secondaryTabs'];

        // dd($outsideTabs,$secondaryTabs);
        foreach ($outsideTabs as $field) {
            $this->parseField($form, $field, 'fields', '', $indexValue);
        }

        foreach ($primaryTabs as $tab=>$primaryTabFields) {
            foreach ($primaryTabFields as $primaryTabField) {
                $this->parseField($form, $primaryTabField, 'tabs', $tab, $indexValue);
            }
        }




        foreach ($secondaryTabs as $secondaryTab=>$secondaryTabFields) {
            foreach ($secondaryTabFields as $secondaryTabField) {
                $this->parseField($form, $secondaryTabField, 'secondTabs', $secondaryTab, $indexValue);
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
            $this->parseField($form, $field, 'fields', '', $indexValue);
        }

        foreach ($primaryTabs as $tab=>$primaryTabFields) {
            foreach ($primaryTabFields as $primaryTabField) {
                $this->parseField($form, $primaryTabField, 'tabs', $tab, $indexValue);
            }
        }




        foreach ($secondaryTabs as $secondaryTab=>$secondaryTabFields) {
            foreach ($secondaryTabFields as $secondaryTabField) {
                $this->parseField($form, $secondaryTabField, 'secondTabs', $secondaryTab, $indexValue);
            }
        }

        // $widgetObj = new class(){};
        // $widgetObj->form = $form;

        $this->widget = $c->widget;
        // dd($this->form);

        $this->trigger();

        if ($this->relation_field) {
            $this->emit('setRelationFormProperty', ['name'=>$this->getKeyName(),'value'=>\Arr::get($this->form, $this->getKeyName())]);
        } else {
            $this->emit('setFormProperty', ['name'=>$this->getKeyName(),'value'=>\Arr::get($this->form, $this->getKeyName())]);
        }
    }

    public function onRemoveItem($index)
    {
        $value = \Arr::get($this->form, $this->getKeyName());
        if (!empty($value)) {
            unset($value[$index]);
            $value = array_values($value);
            \Arr::set($this->form, $this->getKeyName(), $value);
        }
        unset($this->allFields[$index]);
        $this->allFields = array_values($this->allFields);

        $this->trigger();


        if ($this->relation_field) {
            $this->emit('setRelationFormProperty', ['name'=>$this->getKeyName(),'value'=>\Arr::get($this->form, $this->getKeyName())]);
        } else {
            $this->emit('setFormProperty', ['name'=>$this->getKeyName(),'value'=>\Arr::get($this->form, $this->getKeyName())]);
        }
    }



    public function getKeyName()
    {
        $keyName = $this->field['modelName'];
        if (\Str::startsWith($keyName, 'form.')) {
            $keyName = substr_replace($keyName, '', strpos($keyName, 'form.'), strlen('form.'));
        };
        return  $keyName;
    }
    protected function parseField($form, $field, $type, $tab='', $indexValue)
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
            $field = $form->getFormWidgets()[$field->fieldName]->render()->vars['field'];
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
        $field->options = $field->options();

        // if ($field->type =='radio') {
        //     if (is_callable($field->options)) {
        //         $field->options = $field->options();
        //     }
        //     // dd($primaryTabField);
        // } elseif ($field->type =='dropdown') {
        //     if (is_callable($field->options)) {
        //         $field->options = $field->options();
        //     }

        // }elseif ($field->type =='checkboxlist') {
        //     if (is_callable($field->options)) {
        //         $field->options = $field->options();
        //     }
        // }


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
        if ( in_array(\Arr::get($field->config,'type'),['fileupload','fieldfileupload'])) {

            \Arr::set($this->form['fileList'], $field->modelNameNotFirst,$field->vars['fileList']->map(function ($file) {
                return [
                    'id' => $file->id,
                    'thumb' => $file->thumbUrl,
                    'path' => $file->pathUrl,
                    'relative_path' => $file->getRelativePath()
                ];
            })->toArray());
            if(\Arr::get($field->config,'type')=='fieldfileupload'){
                $this->needSetFields[]=$field->modelNameNotFirst;
            }
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
            \Arr::set($this->form, $keyName, '');
        // $this->form[$field->arrayName][$field->fieldName] = '';
        } elseif ($field->type=='checkboxlist') {
            \Arr::set($this->form, $keyName, $field->value?:[]);
        } elseif ($field->type=='checkbox') {
            \Arr::set($this->form, $keyName, $field->value?:[]);
        } else {
            \Arr::set($this->form, $keyName, $field->value);

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

        $this->seeDependsOn($name);

        if ($this->relation_field) {
            $this->emit('setRelationFormProperty', ['name'=>$name,'value'=>\Arr::get($this->form, implode('.', $names))]);
        } else {
            $this->emit('setFormProperty', ['name'=>$name,'value'=>\Arr::get($this->form, implode('.', $names))]);
        }
    }

    public function updatedForm()
    {

        // dd(post('refresh_fields'));
        $this->dependsOn();

    }

    public function onRefresh($data)
    {
        request()->merge($data)->merge($this->form);
        $c = find_controller_by_url(request()->input('fingerprint.path'));
        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }

        if (!$this->modelId) {
            $c->create();
        } else {
            $c->update($this->modelId);
        }
        $ww = $c->widget->{$this->field['alias']};
        $ww->onRefresh();

        $formWidgets = $ww->getFormWidgets();


        foreach ($formWidgets as $indexValue=>$formWidget) {
            $this->init($formWidget, $indexValue);
        }

        $this->trigger();


    }





    public function setForm($form)
    {
        $this->form = $form;
        $this->trigger();
    }

    public function trigger()
    {
        foreach ($this->allFields as $allField) {
            $fields = $allField['fields']??[];
            $tabs = $allField['tabs']??[];
            $secondTabs = $allField['secondTabs']??[];
            $this->filterTriggerAttributes($fields);
            array_map(function ($fields) {
                $this->filterTriggerAttributes($fields);
            }, $tabs);
            array_map(function ($fields) {
                $this->filterTriggerAttributes($fields);
            }, $secondTabs);
        }
    }

    protected function filterTriggerAttributes($fields)
    {
        foreach ($fields as $field) {
            $triggerAction = \Arr::get($field, 'trigger.action');
            $triggerField = \Arr::get($field, 'trigger.field');
            $triggerCondition = \Arr::get($field, 'trigger.condition');
            $triggerFieldModelName = \Arr::get($field, 'trigger.modelNameNotFirst');

            $actions = explode('|', $triggerAction);

            foreach ($actions as $action) {
                if ($action=='empty') {
                    $triggerFieldValue = \Arr::get($this->form, $triggerFieldModelName);

                    $fieldValue = \Arr::get($this->form, $field['modelNameNotFirst']);

                    if ($this->onConditionChanged($triggerFieldValue, $triggerCondition)) {
                        if (!$fieldValue|| empty($fieldValue)) {
                        } else {
                            if (is_array($triggerFieldValue)) {
                                \Arr::set($this->form, $field['modelNameNotFirst'], []);
                            } else {
                                \Arr::set($this->form, $field['modelNameNotFirst'], '');
                            }
                        }
                    }
                }
            }
        }
    }
    protected function onConditionChanged($fieldValue, $triggerCondition)
    {
        if (\Str::contains($triggerCondition, 'value')) {
            preg_match_all('/[^[\]]+(?=])/', $triggerCondition, $matches);
            $triggerCondition = 'value';
            $triggerConditionValue = $matches[0]??[];
            if (!$triggerConditionValue) {
                $triggerConditionValue = [];
            }
        }
        if ($triggerCondition=='checked') {
            if ($fieldValue&&!empty($fieldValue)) {
                return true;
            }
        } elseif ($triggerCondition=='unchecked') {
            if (!$fieldValue||empty($fieldValue)) {
                return true;
            }
        } elseif ($triggerCondition=='value') {
            if (is_array($fieldValue)) {
                if (!empty(array_intersect($fieldValue, $triggerConditionValue))) {
                    return true;
                }
            } else {
                foreach ($triggerConditionValue as $val) {
                    if ($val==$fieldValue) {
                        return true;
                    }
                }
            }
        }
        return false;
    }


    protected function dependsOn()
    {
        if (!empty(post('refresh_fields'))) {
            $this->onRefresh([]);//todo
        }
    }


    protected function seeDependsOn($name)
    {
        foreach ($this->allFields as $allField) {
            $fields = $allField['fields']??[];
            $tabs = $allField['tabs']??[];
            $secondTabs = $allField['secondTabs']??[];
            $this->dependsOnContainName($fields, $name);
            array_map(function ($fields) use ($name) {
                $this->dependsOnContainName($fields, $name);
            }, $tabs);
            array_map(function ($fields) use ($name) {
                $this->dependsOnContainName($fields, $name);
            }, $secondTabs);
        }
    }

    protected function dependsOnContainName($fields, $name)
    {
        $refreshFields = post('refresh_fields');

        foreach ($fields as $field) {

            if (in_array($name, $field['dependsFieldModelNames'])) {
                $refreshFields[]=$field['fieldName'];
                request()->merge(['refresh_fields_'.$field['fieldName']=>!$field['update']]);

                request()->merge(['_repeater_index'=>explode('.',str_replace($this->field['modelName'], '', $name))[1]]);
            }
        }
        if (!empty($refreshFields)) {
            request()->merge(['refresh_fields'=>$refreshFields]);
        }
    }



    public function uploadFinished()
    {
        // $this->update = !$this->update;


        $params = func_get_args();
        $name = $params[0];

        $arrayName = explode('.', $name);

        array_shift($arrayName);

        request()->merge([
            '_session_key' => $this->form['_session_key']??''
        ]);



        $keyStr = '';
        foreach ($arrayName as $a) {
            if (\Str::startsWith($a, '[')) {
                $keyStr .='.'. str_replace(['[',']'], '', $a);
            } else {
                if (!$keyStr) {
                    $keyStr .= $a;
                } else {
                    $keyStr .='.'. $a;
                }
            }
        }
        $uplodaFiles = \Arr::get($this->form, $keyStr);



        $c = find_controller_by_url(request()->input('fingerprint.path'));
        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }


        if(!$this->modelId){
            $c->asExtension('FormController')->create($this->context);
        }else{
            $c->asExtension('FormController')->update($this->modelId,$this->context);
        }


        if (is_array($uplodaFiles)) {//多文件
            // $this->form['fileList'][$arrayName[0]][$arrayName[1]] = [];
            foreach ($uplodaFiles as $uplodaFile) {
                // dd($c->widget,'form'.ucfirst(\Str::camel($arrayName[1])));
                if (!is_string($uplodaFile)) {
                    request()->files->set('file_data', $uplodaFile);
                    request()->setConvertedFiles(request()->files->all());
                    $widgetName = $this->getFieldWidgetName($keyStr);
                    // dd($c->widget);
                    $file = $c->widget->{$widgetName}->onUpload();
                //    array_push($this->form['fileList'][$arrayName[0]][$arrayName[1]],$file);

                    $files = \Arr::get($this->form['fileList'], $keyStr,[]);
                    array_push($files,$file);
                    \Arr::set($this->form['fileList'], $keyStr, $files);
                    // \Arr::set($this->form, $keyStr, $files);

                }
            }
        } else {//单文件

            request()->files->set('file_data', $uplodaFiles);
            request()->setConvertedFiles(request()->files->all());
            $widgetName = $this->getFieldWidgetName($keyStr);

            $file = $c->widget->{$widgetName}->onUpload();
            // $this->form['fileList'][$arrayName[0]][$arrayName[1]]=[];
            \Arr::set($this->form['fileList'], $keyStr, [$file]);
            // \Arr::set($this->form, $keyStr, [$file]);
            // array_push($this->form['fileList'][$arrayName[0]][$arrayName[1]],$file);

            // dd(request()->hasFile('file_data'),3213);
        }
        if ($this->relation_field) {
            $this->emit('setRelationFormProperty', ['name'=>$name,'value'=>\Arr::get($this->form['fileList'], $keyStr)]);
        } else {
            $this->emit('setFormProperty', ['name'=>$name,'value'=>\Arr::get($this->form['fileList'], $keyStr)]);
        }
    }

    protected function getFieldWidgetName($modelNameNotFirst)
    {

        foreach ($this->allFields as $allField) {
            $fields = $allField['fields']??[];
            $tabs = $allField['tabs']??[];
            $secondTabs = $allField['secondTabs']??[];
            foreach ($fields as $field){
                if($field['modelNameNotFirst']==$modelNameNotFirst){
                    return $field['alias']??$field['fieldName'];
                }
            }

            foreach ($tabs as $tab=>$fields)
            {
                foreach ($fields as $field){
                    if($field['modelNameNotFirst']==$modelNameNotFirst){
                        return $field['alias']??$field['fieldName'];
                    }
                }
            }
            foreach ($secondTabs as $secondTab=>$fields)
            {
                foreach ($fields as $field){
                    if($field['modelNameNotFirst']==$modelNameNotFirst){
                        return $field['alias']??$field['fieldName'];
                    }
                }
            }
        }


    }

    //删除文件
    public function onRemoveAttachment($modelName,$id)
    {



        $arrayName = explode('.', $modelName);

        array_shift($arrayName);
        $c = find_controller_by_url(request()->input('fingerprint.path'));
        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        request()->merge([
            'file_id' => $id,
            '_session_key' => $this->form['_session_key']??'',
        ]);
        $keyStr = '';
        foreach ($arrayName as $a) {
            if (\Str::startsWith($a, '[')) {
                $keyStr .='.'. str_replace(['[',']'], '', $a);
            } else {
                if (!$keyStr) {
                    $keyStr .= $a;
                } else {
                    $keyStr .='.'. $a;
                }
            }
        }
        $widgetName = $this->getFieldWidgetName($keyStr);

        if(!$this->modelId){
            $c->asExtension('FormController')->create($this->context);
        }else{
            $c->asExtension('FormController')->update($this->modelId,$this->context);
        }
        // $c->asExtension('FormController')->update($this->modelId);

        // dd($this->form);


        $c->widget->{$widgetName}->onRemoveAttachment();

        $files = \Arr::get($this->form['fileList'],$keyStr,[]);

        \Arr::set($this->form['fileList'] ,$keyStr, array_filter($files,function($file)use($id){
            return $file['id']!=$id;
        }));

        if ($this->relation_field) {
            $this->emit('setRelationFormProperty', ['name'=>$modelName,'value'=>\Arr::get($this->form['fileList'], $keyStr)]);
        } else {
            $this->emit('setFormProperty', ['name'=>$modelName,'value'=>\Arr::get($this->form['fileList'], $keyStr)]);
        }


    }


    protected function getListeners()
    {
        return [
            $this->formAlias.'_setForm' =>'setForm',
            'upload:finished' => 'uploadFinished'
        ];
    }





    public function render()
    {
        // dd($this->form);
        return view('backend::widgets.form.repeater', ['widget'=>$this->widget]);
    }
}
