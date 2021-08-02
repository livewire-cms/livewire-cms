<?php

namespace Modules\Backend\View\Livewire\Widgets;

use Livewire\Component;
use Route;
use Modules\LivewireCore\Html\Helper as HtmlHelper;
use Livewire\WithFileUploads;


class RelationForm extends Component
{
    // protected $widget;
    use WithFileUploads;
    use \WireUi\Traits\Actions;

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


    public $modal;

    public $needSetFields = [];

    protected $widget;

    protected $listeners = [
        'onRelationButtonCreate',
        'onRelationClickViewList',
        'upload:finished' => 'uploadFinished',
        'setRelationFormProperty'
    ];



    public function onRelationButtonCreate($data)
    {
        $this->resetData();

        // dd($data);
        $this->modal=true;
        // dd($data['_relation_session_key']);
        $this->update = !$this->update;
        $this->relation_field = $data['_relation_field'];
        $this->context = 'create';
        $this->parentContext = $data['context'];
        $this->modelId = $data['modelId'];
        $this->parentSessionKey = $data['_relation_session_key'];
        $this->form['_relation_field'] = $data['_relation_field'];
        // $data['_relation_mode'] = 'form';
        $this->form = array_merge($this->form,$data);

        request()->merge($data);
        $pre = 'relation'.ucfirst(\Str::camel($this->relation_field));


        //todo 找到控制器((
        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if(!$c){
            throw new \RuntimeException('Could not find controller');
        }
        if(!$this->modelId){
            $c->setAction('create');
            $c->setParams([$this->context]);
        }else{
            $c->setAction('update');
            $c->setParams([$this->modelId,$this->context]);
        }
        // $c->asExtension('FormController')->update($this->modelId);//todo
        $c->onRelationButtonCreate();
        // dd($c);

        // $c->widget->{$pre.'ManageForm'}->render();


        // dd($c->widget);

        $this->prepareVars($c->widget, $pre);

    }

    public function onRelationClickViewList($data)
    {

        $this->resetData();

        $this->modal=true;

        $this->relation_field = $data['_relation_field'];
        $this->context = 'update';
        $this->parentContext = $data['context'];
        $this->modelId = $data['modelId'];
        $this->manageId = $data['manage_id'];

        $this->form['_relation_field'] = $data['_relation_field'];

        $this->form = array_merge($this->form,$data);

        // $this->parentSessionKey = $data['_relation_session_key'];
        $data['_session_key'] = $this->sessionKey;//更新的时候用自己的sessionKey

        request()->merge($data);
        $pre = 'relation'.ucfirst(\Str::camel($this->relation_field));

        //todo 找到控制器((
        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if(!$c){
            throw new \RuntimeException('Could not find controller');
        }

        if(!$this->modelId){
            $c->setAction('create');
            $c->setParams([$this->context]);
        }else{
            $c->setAction('update');
            $c->setParams([$this->modelId,$this->context]);
        }

        // $c->asExtension('FormController')->update($this->modelId);
        $c->onRelationClickViewList();
        // dd($c);

        // $c->widget->{$pre.'ManageForm'}->render();
        // $this->sessionKey = $c->widget->{$pre.'ManageForm'}->getSessionKey();
        // $this->form['_session_key'] = $this->sessionKey;
        $this->prepareVars($c->widget, $pre);
        // $this->widget = $c->widget;

    }


    public function prepareVars($widget,$pre)
    {
        $this->widget = $widget;

        $this->sessionKey = $widget->{$pre.'ManageForm'}->getSessionKey();

        $this->form['_session_key'] = $this->sessionKey;

        $widget->{$pre.'ManageForm'}->render();

        // dd($widget);
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
         $this->trigger();


    }

    protected function parseField($widget, $field, $type, $tab='')
    {
        $pre = 'relation'.ucfirst(\Str::camel($this->relation_field));

        // if($field->fieldName=='groups'){
        //     dd($field);
        // }

        //解析自定义widget
        if ($field->type =='widget') {
            // dd($widget->form->getFormWidgets()['avatars']->render());
            // dd($widget->form,$primaryTabField->valueFrom);
            // dd($widget->form->getFormWidgets()[$field->valueFrom]->render());

            $field = $widget->{$pre.'ManageForm'}->getFormWidgets()[$field->valueFrom]->render()->vars['field'];

            // dd($primaryTabField);
            // $primaryTabs[$tab][$tabField] = $primaryTabField;
            // dd($primaryTabField->getId());
        }





        //设置partial
        if ($field->type=='partial') {
            $field->html = $widget->{$pre.'ManageForm'}->getController()->makePartial($field->path ?: $field->fieldName, [
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
        // }elseif ($field->type =='dropdown') {
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
        $field->modelName = 'form.'.implode('.', $names);
        $field->id = $field->getId();
        $keyName  = implode('.', $names);

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

    public function mount($parentSessionKey)
    {
        $this->parentSessionKey = $parentSessionKey;
    }

    public function save()
    {

        foreach($this->needSetFields as $modelNameNotFirst){
            $fieldValue = \Arr::get($this->form['fileList'], $modelNameNotFirst);
            \Arr::set($this->form, $modelNameNotFirst,$fieldValue);
        }


        $this->form['_relation_field'] = $this->relation_field;
        $this->form['_relation_mode'] = 'form';
        $this->form['manage_id'] = $this->manageId;
        $this->form['_session_key'] = $this->sessionKey;//自己的图片延迟绑定
        $this->form['_relation_session_key'] = $this->parentSessionKey;//延迟绑定到父类

        // dd($this->modelId);
        // dd($this->form);


        if($this->context=='update'){
            // $this->form["relation{ucfirst($this->relation_field)}ManageFormBreakdown_loaded"] = $this->modelId;
        }
        // dd($this->form);

        // dd($this);

        request()->merge($this->form);

        // dd($this->context,$this->parentContext);
        if(!$this->modelId){

            // $c->asExtension('FormController')->create_onSave();
            $c = find_controller_by_url(request()->input('fingerprint.path'));
            if(!$c){
                throw new \RuntimeException('Could not find controller');
            }
            $c->setAction('create');
            $c->setParams([$this->parentContext]);
            // $c->asExtension('FormController')->update($this->modelId);
            // dd($this->modelId,$this->relation_field);
            if(!$this->manageId){
                $c->onRelationManageCreate();
            }else{
                $c->onRelationManageUpdate();

            }
            $this->emitTo('backend.livewire.widgets.relation_lists','search'.($this->relation_field?'_'.$this->relation_field:''),[//todo 多个list
                'search' =>'',
                'context' => $this->context,
                '_session_key' => $this->parentSessionKey
            ]);
            $this->resetData();
        // dd($c);
        }else{
            $c = find_controller_by_url(request()->input('fingerprint.path'));

            if(!$c){
                throw new \RuntimeException('Could not find controller');
            }
            // $c->asExtension('FormController')->update($this->modelId);
            $c->setAction('update');
            $c->setParams([$this->modelId,$this->parentContext]);

            if(!$this->manageId){
                $c->onRelationManageCreate();
            }else{
                $c->onRelationManageUpdate();
            }

            $this->emitTo('backend.livewire.widgets.relation_lists','search'.($this->relation_field?'_'.$this->relation_field:''),[//todo 多个list
                'search' =>'',
                'context' => $this->context,
                '_session_key' => $this->parentSessionKey

            ]);
            $this->resetData();



        }
        $this->notification()->success(
            $title = 'Success',
            $description = 'Your data was successfull saved'
        );

        $this->trigger();



    }
    public function uploadFinished()
    {

        $formPrefix = 'relation'.ucfirst(\Str::camel($this->relation_field)).'ManageForm';

        $this->update = !$this->update;


        $params = func_get_args();
        $name = $params[0];

        $arrayName = explode('.', $name);

        array_shift($arrayName);
        // dd($this->form);
        request()->merge([
            '_session_key' => $this->form['_session_key']??'',
            '_relation_field' => $this->relation_field,
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

        $c->asExtension('FormController')->create();


        if(!$this->manageId){

            if(!$this->modelId){
                $c->asExtension('FormController')->create($this->parentContext);
            }else{
                $c->asExtension('FormController')->update($this->modelId,$this->parentContext);
            }
        }else{
            if(!$this->modelId){
                $c->asExtension('FormController')->create($this->parentContext);
            }else{
                $c->asExtension('FormController')->update($this->modelId,$this->parentContext);
            }
        }
        // dd($c->widget);
        // $c->widget->form->render();

        $widgetName = $this->getFieldWidgetName($keyStr);

        if (is_array($uplodaFiles)) {//多文件
            // $this->form['fileList'][$arrayName[0]][$arrayName[1]] = [];

            foreach ($uplodaFiles as $uplodaFile) {
                // dd($c->widget,'form'.ucfirst(\Str::camel($arrayName[1])));
                if (!is_string($uplodaFile)) {
                    request()->files->set('file_data', $uplodaFile);
                    request()->setConvertedFiles(request()->files->all());
                   $file = $c->widget->{$widgetName}->onUpload();
                   $files = \Arr::get($this->form['fileList'], $keyStr,[]);
                   array_push($files,$file);
                   \Arr::set($this->form['fileList'], $keyStr, $files);
                //    array_push($this->form['fileList'][$arrayName[0]][$arrayName[1]],$file);

                }
            }
        } else {//单文件

            request()->files->set('file_data', $uplodaFiles);
            request()->setConvertedFiles(request()->files->all());
            // $file = $c->widget->{$formPrefix.ucfirst(\Str::camel($arrayName[1]))}->onUpload();
            // $this->form['fileList'][$arrayName[0]][$arrayName[1]]=[];
            // array_push($this->form['fileList'][$arrayName[0]][$arrayName[1]],$file);

            $file = $c->widget->{$widgetName}->onUpload();
            // $this->form['fileList'][$arrayName[0]][$arrayName[1]]=[];
            \Arr::set($this->form['fileList'], $keyStr, [$file]);

            // dd(request()->hasFile('file_data'),3213);
        }

        $this->trigger();

    }

    //删除文件
    public function onRemoveAttachment($modelName,$id)
    {
        $formPrefix = 'relation'.ucfirst(\Str::camel($this->relation_field)).'ManageForm';

        $arrayName = explode('.', $modelName);

        array_shift($arrayName);
        $c = find_controller_by_url(request()->input('fingerprint.path'));
        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        request()->merge([
            '_relation_field' => $this->relation_field,
            '_session_key' => $this->form['_session_key']??'',

        ]);

        $c->asExtension('FormController')->create($this->parentContext);

        // $c->asExtension('FormController')->update($this->modelId);

        request()->merge([
            'file_id' => $id
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

        $c->widget->{$widgetName}->onRemoveAttachment();
        // $files = $this->form['fileList'][$arrayName[0]][$arrayName[1]];
        // $this->form['fileList'][$arrayName[0]][$arrayName[1]] = array_filter($files,function($file)use($id){
        //     return $file['id']!=$id;
        // });

        $files = \Arr::get($this->form['fileList'],$keyStr,[]);

        \Arr::set($this->form['fileList'] ,$keyStr, array_filter($files,function($file)use($id){
            return $file['id']!=$id;
        }));


        $this->trigger();


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
    public function updatedmodal($value)
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
        $this->sessionKey = null;
        $this->modal = false;

    }


    public function updated($name, $value)
    {


        $this->seeDependsOn($name);
    }

    public function updatedForm()
    {
        // dd($this->alias.'_'.'setForm');
        // dd(post('refresh_fields'));
        $this->dependsOn();

        $this->trigger();


    }



    /**
     * 同步child-component 到自己
     *
     * @param [type] $data
     * @return void
     */
    public function setRelationFormProperty($data)
    {

        $name = $data['name'];
        $value = $data['value'];

        if(\Str::startsWith($name, 'form.')){
            $name = substr_replace($name,'',strpos($name,'form.'),strlen('form.'));
        };
        \Arr::set($this->form, $name,$value);
        $this->trigger();


    }

    public function onRefresh($data)
    {

        // $this->form['manage_id'] = $this->manageId;

        request()->merge($data)->merge($this->form);
        $pre = 'relation'.ucfirst(\Str::camel($this->relation_field));



        $c = find_controller_by_url(request()->input('fingerprint.path'));
        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        if(!$this->manageId){

            if(!$this->modelId){
                $c->asExtension('FormController')->create($this->parentContext);
            }else {
                $c->asExtension('FormController')->update($this->modelId,$this->parentContext);
            }

        }else{
            if($this->parentContext=='create'){
                $c->asExtension('FormController')->create($this->parentContext);
            }else{
                $c->asExtension('FormController')->update($this->modelId,$this->parentContext);
            }
        }

        $c->widget->{$pre.'ManageForm'}->onRefresh();

        $this->resetFieldData();

        $this->prepareVars($c->widget,$pre);



    }

    public function resetFieldData()
    {
        $this->fields = [];
        $this->tabs = [];
        $this->secondTabs = [];
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


    /**
     * 下拉框联动 调用之前 先seeDependsOn
     *
     * @return void
     */
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

        return view('backend::widgets.relation_form',['widget'=>$this->widget]);
    }
}
