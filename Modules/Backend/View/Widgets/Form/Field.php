<?php

namespace Modules\Backend\View\Widgets\Form;

use Livewire\Component;
use Route;
use Modules\LivewireCore\Html\Helper as HtmlHelper;

class Field extends Component
{
    public $fields = [];
    public $tabs = [];
    public $secondTabs = [];


    public $field; //字段对象


    public $form = [];

    public $parentContext;
    public $context;
    public $modelId;

    public function mount()
    {
        $args = func_get_args();
        $widget = $args[1];
        $this->parentContext = $widget->form->getContext();
        $this->modelId = $widget->form->model->getKey();
        $this->form[$this->field['alias'].'_loaded'] = 1;
        // dd($args,$this->field);
    }

    public function onAddItem()
    {
        request()->merge($this->form);
        // dd($this->fields);
        // dd($this->form);
        $c = find_controller_by_url(request()->input('fingerprint.path'));
        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        if ($this->parentContext=='create') {
            $c->asExtension('FormController')->create();
        } elseif ($this->parentContext=='update') {
            // dd($this->form);
            $c->asExtension('FormController')->update($this->modelId);
        }

        // dd($c->widget->{$this->field['alias']}->onAddItem());
        $vars = $c->widget->{$this->field['alias']}->onAddItem()->vars;
        // dd($vars);
        $form = $vars['widget'];
        $form->render();
        $indexValue = $vars['indexValue'];

        // $this->widget = $widget;

        $this->form['_session_key'] = $form->getSessionKey();


        $this->context = $form->context;
        $this->modelId = $form->model->getKey();



        $outsideTabs = $form->vars['outsideTabs'];
        $primaryTabs = $form->vars['primaryTabs'];
        $secondaryTabs = $form->vars['secondaryTabs'];

        // dd($outsideTabs,$secondaryTabs);
        foreach ($outsideTabs as $field) {
            $this->parseField($form, $field, 'fields');
        }

        foreach ($primaryTabs as $tab=>$primaryTabFields) {
            foreach ($primaryTabFields as $primaryTabField) {
                $this->parseField($form, $primaryTabField, 'tabs', $tab);
            }
        }




        foreach ($secondaryTabs as $secondaryTab=>$secondaryTabFields) {
            foreach ($secondaryTabFields as $secondaryTabField) {
                $this->parseField($form, $secondaryTabField, 'secondTabs', $secondaryTab);
            }
        }

    }
    protected function parseField($form, $field, $type, $tab='')
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
        } elseif ($field->type =='checkboxlist') {
            if (is_callable($field->options)) {
                $field->options = $field->options();
            }
        }


        //设置值
        $names = HtmlHelper::nameToArray($field->arrayName);

        if ($field->type=='password') {
            // $this->form[$field->arrayName][$field->fieldName] = '';
            $this->form[$names[0]][$names[1]][$names[2]][$field->fieldName] = $field->value;

        }else if ($field->type=='checkboxlist') {
            // $this->form[$field->arrayName][$field->fieldName] = $field->value?:[];
            $this->form[$names[0]][$names[1]][$names[2]][$field->fieldName] = $field->value?:[];
        } else {
            // $this->form[$field->arrayName][$field->fieldName] = $field->value;
            $this->form[$names[0]][$names[1]][$names[2]][$field->fieldName] = $field->value;
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
            // dd($form->getFormWidgets());
            // $field = $form->getFormWidgets()[$field->valueFrom]->render();
        }


        $names = HtmlHelper::nameToArray($field->getName());


        foreach ($names as &$name) {
            if (is_numeric($name)) {
                // $name = $name;
            }
        }
        $field->modelName = 'form.'.implode('.', $names);
        $field->modelName = str_replace(['.['], ['['], $field->modelName);
        $field->id = $field->getId();


        if ($tab) {
            $this->{$type}[$tab][] = (array)$field;
        } else {
            $this->{$type}[] = (array)$field;
        }
        // dd($field);

    }


    public function render()
    {
        return view('backend::widgets.form.field');
    }
}
