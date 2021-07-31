<?php namespace Modules\Backend\View\Livewire\Widgets;

use Livewire\Component;
use stdClass;
use Modules\LivewireCore\Html\Helper as HtmlHelper;

class ReportContainer extends Component
{
    public $fields = [];
    public $tabs = [];
    public $secondTabs = [];
    public $form = [];
    public $context;
    public $modelId;
    public $alias;

    public $update;



    protected $reportWidgets;
    protected $widget;

    protected $formWidget;




    public $modal;
    public $action;


    protected $listeners = [
        'setFormProperty'
    ];

    protected $rules = [
        'form.Custom.className' => 'required',
    ];


    public function mount($widget)
    {
        $reportcontainer = $widget->reportContainer;
        $reportcontainer->render();

        $reportWidgets = $reportcontainer->getReportWidgets(); //['welcome'=>['widget'=>object,'sortOrder'=>50]]

        $this->reportWidgets = $reportWidgets;
        $this->widget = $widget;
    }



    public function onRefresh()
    {
        $this->update = !$this->update;

        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        $c->initReportContainer();

        $this->mount($c->widget);
    }

    protected function makeForm($widget)
    {
        $widget->form->render();
        $this->form['_session_key'] = $widget->form->getSessionKey();
        $this->alias = $widget->form->alias;
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
    }
    protected function parseField($widget, $field, $type, $tab='')
    {

        // if($field->fieldName=='groups'){
        //     dd($field);
        // }



        //解析自定义widget
        if ($field->type =='widget') {
            // dd($widget->form->getFormWidgets()['avatars']->render());
            // dd($widget->form,$primaryTabField->valueFrom);
            // dd($widget->form->getFormWidgets()[$field->valueFrom]->render());

            $field = $widget->form->getFormWidgets()[$field->valueFrom]->render()->vars['field'];

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
        if (\Arr::get($field->config, 'type')=='fileupload') {

            //todo 过滤$field->vars['fileList']
            $this->form['fileList'][$field->arrayName][$field->fieldName] = $field->vars['fileList']->map(function ($file) {
                return [
                    'id' => $file->id,
                    'thumb' => $file->thumbUrl,
                    'path' => $file->pathUrl
                ];
            })->toArray();
            // dd($widget->form->getFormWidgets());
            // $field = $widget->form->getFormWidgets()[$field->valueFrom]->render();
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


        unset($field->config['form']);
        unset($field->vars['formWidgets']);

        if ($tab) {
            $this->{$type}[$tab][] = (array)$field;
        } else {
            $this->{$type}[] = (array)$field;
        }
    }
    public function setFormProperty($data)
    {
        $name = $data['name'];
        $value = $data['value'];

        if (\Str::startsWith($name, 'form.')) {
            $name = substr_replace($name, '', strpos($name, 'form.'), strlen('form.'));
        };
        \Arr::set($this->form, $name, $value);
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

        $this->emit($this->alias.'_'.'setForm', $this->form);
    }
    public function trigger()
    {
        $this->filterTriggerAttributes($this->fields);
        array_map(function ($fields) {
            $this->filterTriggerAttributes($fields);
        }, $this->tabs);
        array_map(function ($fields) {
            $this->filterTriggerAttributes($fields);
        }, $this->secondTabs);
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
            $this->onRefresh([]);
        }
    }

    protected function seeDependsOn($name)
    {
        $this->dependsOnContainName($this->fields, $name);
        array_map(function ($fields) use ($name) {
            $this->dependsOnContainName($fields, $name);
        }, $this->tabs);
        array_map(function ($fields) use ($name) {
            $this->dependsOnContainName($fields, $name);
        }, $this->secondTabs);
    }

    protected function dependsOnContainName($fields, $name)
    {
        $refreshFields = post('refresh_fields');

        foreach ($fields as $field) {
            if (in_array($name, $field['dependsFieldModelNames'])) {
                $refreshFields[]=$field['fieldName'];
                request()->merge(['refresh_fields_'.$field['fieldName']=>!$field['update']]);
            }
        }
        if (!empty($refreshFields)) {
            request()->merge(['refresh_fields'=>$refreshFields]);
        }
    }

    public function resetData()
    {
        $this->form = [];
        $this->fields = [];
        $this->tabs = [];
        $this->secondTabs = [];
    }
    public function onAction($method, $params=[])
    {

        $c = find_controller_by_url(request()->input('fingerprint.path'));
        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }

        $c->initReportContainer();

        $reportcontainer = $c->widget->reportContainer;
        if (!method_exists($reportcontainer, $method)) {
            throw new \RuntimeException($method.'do not exist');
        }

        if (empty($params)) {
            $params = [];
        }

        if (!is_array($params)) {
            $params = [$params];
        }
        $this->action = $method;

        request()->merge($params);
        if($this->action=='onLoadAddPopup'){
            $this->modal = true;
        }
        else if($this->action=='onAddWidget'){
            $this->modal = true;

            $this->validate();
            request()->merge($this->form['Custom']??[]);



        }elseif ($this->action=='onRemoveWidget'){

        }
        $res = call_user_func_array([$reportcontainer,$method], $params);

        if ($this->action == 'onLoadAddPopup') {
            $this->resetData();

            $reportWidgetOptions = [];

            $widgets = $res->vars['widgets'];
            foreach ($widgets as $className => $widgetInfo){
                $reportWidgetOptions[$className] = isset($widgetInfo['label']) ? e(trans($widgetInfo['label'])) : $className;
            }
            $config = $c->makeConfig([
                'fields' => [
                    'className'=>[
                        "label" => "backend::lang.dashboard.widget_label",
                        "span" => "middle",
                        "type"=>"dropdown",
                        "options"=>$reportWidgetOptions,
                        "comment" => "hello comment",
                    ],
                    'size' => [
                        "label" => "backend::lang.dashboard.widget_width",
                        "span" => "middle",
                        "type"=>"dropdown",
                        "options"=> $res->vars['sizes'],

                    ]
                ]
            ]);
            $config->context = 'create';
            $model = new \Modules\System\Models\Custom;
            $config->model = $model;
            $config->arrayName = class_basename($model);
            $config->context = 'create';

            $formWidget =  new \Modules\Backend\Widgets\Form($c, $config);
            $formWidget->bindToController();

            if (isset($c->widget->form)) {
                $this->makeForm($c->widget);
                $this->formWidget = $c->widget;
            }
        }elseif ($this->action == 'onAddWidget'){
            $this->onRefresh();
            $this->modal = false;
        }elseif ($this->action=='onRemoveWidget'){
            $this->onRefresh();
            $this->modal = false;
        }




        // $this->mount($c->widget);
    }


    public function render()
    {
        return view('backend::widgets.reportcontainer', ['widget'=>$this->widget,'formWidget'=>$this->formWidget,'reportWidgets'=>$this->reportWidgets]);
    }
}
