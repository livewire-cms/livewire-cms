<?php

namespace Modules\Backend\View\Livewire\Widgets;

use Livewire\Component;
use Route;
use Modules\LivewireCore\Html\Helper as HtmlHelper;
use Validator;
use Livewire\WithFileUploads;


class QuickForm extends Component
{
    use WithFileUploads;
    // protected $widget;
    use \WireUi\Traits\Actions;

    public $fields = [];
    public $tabs = [];
    public $secondTabs = [];

    public $form = [];




    public $context;
    public $modelId;



    public $loadRelations=[];


    public $update;

    public $modal;

    public $needSetFields = [];

    protected $widget;
    public $alias;


    public $customData = [];



    protected $listeners = [
        'upload:finished' => 'uploadFinished',
        'setFormProperty',
        'onQuickFormCreate',
        'onQuickFormUpdate',
    ];


    public function resetData()
    {
        $this->form = [];
        $this->fields = [];
        $this->tabs = [];
        $this->secondTabs = [];
        $this->customData = [];
    }


    public function mount($widget=null)
    {

        $this->resetData();
        if(!$widget){
            return ;
        }
        $widget->form->render();

        $this->widget = $widget;
        $this->alias = $widget->form->alias;

        $this->form['_session_key'] = $widget->form->getSessionKey();


        $this->context = $widget->form->context;
        $this->modelId = $widget->form->model->getKey();



        $outsideTabs = $widget->form->vars['outsideTabs'];
        $primaryTabs = $widget->form->vars['primaryTabs'];
        $secondaryTabs = $widget->form->vars['secondaryTabs'];

        // dd($outsideTabs,$secondaryTabs);
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

        // dd($this->form,$this->fields);
        // dd($this->form,$this->fields,$this->tabs,$this->secondTabs);
    }

    protected function parseField($widget, $field, $type, $tab='')
    {





        //解析自定义widget
        if ($field->type =='widget') {
            // dd($widget->form->getFormWidgets()['avatars']->render());
            // dd($widget->form,$primaryTabField->valueFrom);
            // dd($widget->form->getFormWidgets()[$field->valueFrom]->render());

            $field = $widget->form->getFormWidgets()[$field->fieldName]->render()->vars['field'];

            // dd($primaryTabField);
            // $primaryTabs[$tab][$tabField] = $primaryTabField;
            // dd($primaryTabField->getId());
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

        // if ($field->type =='radio') {
        //     if (is_callable($field->options)) {
        //         $field->options = $field->options();
        //     }
        //     // dd($primaryTabField);
        // } elseif ($field->type =='dropdown') {
        //     if (is_callable($field->options)) {
        //         $field->options = $field->options();
        //     }

        // } elseif ($field->type =='checkboxlist') {
        //     if (is_callable($field->options)) {
        //         $field->options = $field->options();
        //     }
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

        foreach ($names as &$name) {
            if (is_numeric($name)) {
                $name = ''.$name.'';
            }
        }

        $keyName  = implode('.', $names);
        $field->modelName = 'form.'.implode('.', $names);
        $field->id = $field->getId();
        // dd($field);

        //设置值
        if ($field->type=='password') {
            \Arr::set($this->form, $keyName,'');
            // $this->form[$field->arrayName][$field->fieldName] = '';
        }else if ($field->type=='checkboxlist') {
            \Arr::set($this->form, $keyName,$field->value?:[]);

        }else if ($field->type=='checkbox') {
            \Arr::set($this->form, $keyName,$field->value?:[]);

        } else {
            \Arr::set($this->form, $keyName,$field->value);

            // $this->form[$field->arrayName][$field->fieldName] = $field->value;
        }


        unset($field->config['form']);
        unset($field->vars['formWidgets']);

        if ($tab) {
            $this->{$type}[$tab][] = (array)$field;
        } else {
            $this->{$type}[] = (array)$field;
        }
    }

    public function save($data)
    {

        // dd(request());
        // dd($this->form['User']['avatar']);

        // dd($this->form);

        foreach($this->needSetFields as $modelNameNotFirst){
            $fieldValue = \Arr::get($this->form['fileList'], $modelNameNotFirst);
            \Arr::set($this->form, $modelNameNotFirst,$fieldValue);
        }

        request()->merge($data)->merge($this->customData)->merge($this->form);
        $c = find_controller_by_url(request()->input('fingerprint.path'));
        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        if (!$this->modelId) {
            $c->asExtension('FormController')->create_onSave($this->context);
        } else {
            // dd($this->form);
            $c->asExtension('FormController')->update_onSave($this->modelId,$this->context);
        }

        $this->emitTo('backend.livewire.widgets.lists', 'search', ['search'=>'']);

        $this->modal = false;

        $this->notification()->success(
            $title = 'Success',
            $description = 'Your data was successfull saved'
        );

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
    }

    protected function getFieldWidgetName($modelNameNotFirst)
    {
        foreach ($this->fields as $field){
            if($field['modelNameNotFirst']==$modelNameNotFirst){
                return $field['alias']??$field['fieldName'];
            }
        }

        foreach ($this->tabs as $tab=>$fields)
        {
            foreach ($fields as $field){
                if($field['modelNameNotFirst']==$modelNameNotFirst){
                    return $field['alias']??$field['fieldName'];
                }
            }
        }
        foreach ($this->secondTabs as $secondTab=>$fields)
        {
            foreach ($fields as $field){
                if($field['modelNameNotFirst']==$modelNameNotFirst){
                    return $field['alias']??$field['fieldName'];
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

        $c->asExtension('FormController')->create($this->context);
        // $c->asExtension('FormController')->update($this->modelId);

        // dd($this->form);


        $c->widget->{$widgetName}->onRemoveAttachment();

        $files = \Arr::get($this->form['fileList'],$keyStr,[]);

        \Arr::set($this->form['fileList'] ,$keyStr, array_filter($files,function($file)use($id){
            return $file['id']!=$id;
        }));


    }


    public function onRelationButtonCreate($data)
    {
        $this->emitTo(
            'backend.livewire.widgets.relation_form',
            'onRelationButtonCreate',
            [
                '_relation_field' => $data,
                'modelId' => $this->modelId,
                'context' => $this->context,
                '_relation_session_key' => $this->form['_session_key']??'',
            ]
        );
    }

    public function setFormProperty($data)
    {
        $name = $data['name'];
        $value = $data['value'];

        if(\Str::startsWith($name, 'form.')){
            $name = substr_replace($name,'',strpos($name,'form.'),strlen('form.'));
        };
        \Arr::set($this->form, $name,$value);


    }

    public function onQuickFormCreate($data)
    {

        $this->resetData();
        $this->customData = $data;

        $applyConfigValues = [
            'context',
        ];
        foreach ($applyConfigValues as $value) {
            if (array_key_exists($value, $data)) {
                $this->{$value} = $data[$value];
            }
        }

        $this->modal = true;
        request()->merge($data);
        $c = find_controller_by_url(request()->input('fingerprint.path'));
        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }

        $c->asExtension('FormController')->create($this->context);

        $this->mount($c->widget);

        // $this->context = 'create';

    }
    public function onQuickFormUpdate($data)
    {


        $this->resetData();

        $applyConfigValues = [
            'context',
        ];
        foreach ($applyConfigValues as $value) {
            if (array_key_exists($value, $data)) {
                $this->{$value} = $data[$value];
            }
        }

        $this->modal = true;
        $this->customData = $data;
        request()->merge($data);
        $c = find_controller_by_url(request()->input('fingerprint.path'));
        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        $c->asExtension('FormController')->update($data['record_id']??$data, $this->context);
        $c->update($data['record_id']??$data);

        $this->mount($c->widget);



    }


    public function onAction($method,$params=[])
    {

        $c = find_controller_by_url(request()->input('fingerprint.path'));
        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }

        if(!method_exists($c,$method)){
            throw new \RuntimeException($method.'do not exist');
        }

        if(empty($params)){
            $params = [];
        }

        if(!is_array($params)){
            $params = [$params];
        }



        call_user_func_array([$c,$method],[$this,$params]);

    }

    public function updated($name, $value)
    {
        $this->seeDependsOn($name);
    }

    public function updatedForm()
    {
        // dd($this->alias.'_'.'setForm');
        $this->dependsOn();

        $this->trigger();

        $this->emit($this->alias.'_'.'setForm',$this->form);
    }

    public function onRefresh($data)
    {
        // dd($this->modelId);
        request()->merge($data)->merge($this->form);
        $c = find_controller_by_url(request()->input('fingerprint.path'));
        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        if (!$this->modelId) {
            $c->asExtension('FormController')->create($this->context);
        } else {
            $c->asExtension('FormController')->update($this->modelId,$this->context);
        }


        $c->widget->form->onRefresh();

        // dd($c->widget->form);

        $this->mount($c->widget);



    }
    public function trigger()
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

    public function render()
    {
        // dd($this->form);
        return view('backend::widgets.quickform',['widget'=>$this->widget]);
    }
}
