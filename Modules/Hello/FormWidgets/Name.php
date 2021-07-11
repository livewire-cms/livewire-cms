<?php namespace Modules\Hello\FormWidgets;

use Modules\Backend\Classes\FormWidgetBase;
use Modules\LivewireCore\Exception\SystemException;
use Modules\LivewireCore\Html\Helper as HtmlHelper;

/**
 * Name Form Widget
 */
class Name extends FormWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'wpjscc_hello_name';

    /**
     * @inheritDoc
     */
    public function init()
    {
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this;
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        // $this->vars['name'] = $this->formField->getName();

        // $this->vars['value'] = $this->getLoadValue();
        // $this->vars['model'] = $this->model;
        // dd($this->formField);

        $this->formField->html   = $this->makePartial('name');
        $this->vars['field'] = $this->formField;
        // dd($this->formField);
        //todo 模版
    }



    public function getModelName()
    {
        $names = HtmlHelper::nameToArray($this->formField->getName());

        foreach ($names as &$name) {
            if (is_numeric($name)) {
                $name = '['.$name.']';
            }
        }
        return $this->formField->modelName = 'form.'.implode('.', $names);
    }


    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
