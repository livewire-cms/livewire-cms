<?php

namespace Modules\Backend\View\Widgets;

use Livewire\Component;
use Route;
use Modules\LivewireCore\Html\Helper as HtmlHelper;
use Livewire\WithFileUploads;


class RelationForm extends Component
{
    // protected $widget;
    use WithFileUploads;

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

    protected $listeners = [
        'onRelationButtonCreate',
        'onRelationClickViewList',
        'upload:finished' => 'uploadFinished'
    ];



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
        $this->form['_session_key'] = $this->sessionKey;
        // dd($c->widget);

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
        // $this->parentSessionKey = $data['_relation_session_key'];
        $data['_session_key'] = $this->sessionKey;//更新的时候用自己的sessionKey

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
        $this->form['_session_key'] = $this->sessionKey;
        $this->prepareVars($c->widget, $pre);
    }


    public function prepareVars($widget,$pre)
    {
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
        if ($field->type =='radio') {
            if (is_callable($field->options)) {
                $field->options = $field->options();
            }
            // dd($primaryTabField);
        } elseif ($field->type =='checkboxlist') {
            if (is_callable($field->options)) {
                $field->options = $field->options();
            }
        }


        //设置值
        if ($field->type=='password') {
            $this->form[$field->arrayName][$field->fieldName] = '';
        }else if ($field->type=='checkboxlist') {
            $this->form[$field->arrayName][$field->fieldName] = $field->value?:[];
        } else {
            $this->form[$field->arrayName][$field->fieldName] = $field->value;
        }

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
            // dd($this->form);

            // dd($widget->form->getFormWidgets());
            // $field = $widget->form->getFormWidgets()[$field->valueFrom]->render();
        }


        $names = HtmlHelper::nameToArray($field->getName());

        foreach ($names as &$name) {
            if (is_numeric($name)) {
                $name = '['.$name.']';
            }
        }
        $field->modelName = 'form.'.implode('.', $names);
        $field->id = $field->getId();


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
        // dd($this->sessionKey);

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


        if($this->context=='create'){

            if($this->parentContext=='create'){
                $c->asExtension('FormController')->create();
            }else if($this->parentContext=='update'){
                $c->asExtension('FormController')->update($this->modelId);
            }

        }elseif($this->context=='update'){
            if($this->parentContext=='create'){
                $c->asExtension('FormController')->create();
            }else if($this->parentContext=='update'){
                $c->asExtension('FormController')->update($this->modelId);
            }
        }
        // dd($c->widget);
        // $c->widget->form->render();


        if (is_array($uplodaFiles)) {//多文件

            foreach ($uplodaFiles as $uplodaFile) {
                // dd($c->widget,'form'.ucfirst(\Str::camel($arrayName[1])));
                if (!is_string($uplodaFile)) {
                    request()->files->set('file_data', $uplodaFile);
                    request()->setConvertedFiles(request()->files->all());
                   $file = $c->widget->{$formPrefix.ucfirst(\Str::camel($arrayName[1]))}->onUpload();

                   array_push($this->form['fileList'][$arrayName[0]][$arrayName[1]],$file);

                }
            }
        } else {//单文件

            request()->files->set('file_data', $uplodaFiles);
            request()->setConvertedFiles(request()->files->all());
            $file = $c->widget->{$formPrefix.ucfirst(\Str::camel($arrayName[1]))}->onUpload();
            $this->form['fileList'][$arrayName[0]][$arrayName[1]]=[];
            array_push($this->form['fileList'][$arrayName[0]][$arrayName[1]],$file);

            // dd(request()->hasFile('file_data'),3213);
        }
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

        $c->asExtension('FormController')->create();

        // $c->asExtension('FormController')->update($this->modelId);

        request()->merge([
            'file_id' => $id
        ]);

       $c->widget->{$formPrefix.ucfirst(\Str::camel($arrayName[1]))}->onRemoveAttachment();
        $files = $this->form['fileList'][$arrayName[0]][$arrayName[1]];

        $this->form['fileList'][$arrayName[0]][$arrayName[1]] = array_filter($files,function($file)use($id){
            return $file['id']!=$id;
        });


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
        $this->sessionKey = null;
        $this->relationFormModal = false;


    }







    public function render()
    {

        return view('backend::widgets.relation_form');
    }
}
