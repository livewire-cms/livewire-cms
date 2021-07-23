<?php

namespace Modules\Backend\View\Livewire\Widgets\Form;

use Livewire\Component;
use Route;
use Modules\LivewireCore\Html\Helper as HtmlHelper;

class Fields extends Component
{

    public $fields = [];
    public $tabs = [];
    public $secondTabs = [];

    public $form = [];

    public $context;
    public $modelId;

    public function mount($widget)
    {
        $outsideTabs = $widget->form->vars['outsideTabs'];
        $primaryTabs = $widget->form->vars['primaryTabs'];
        $secondaryTabs = $widget->form->vars['secondaryTabs'];

        // dd($outsideTabs,$secondaryTabs);
        foreach($outsideTabs as $field)
        {
            $this->form[$field->arrayName][$field->fieldName] = $field->value;

            $names = HtmlHelper::nameToArray($field->getName());

            foreach($names as &$name){
                if(is_numeric($name)){
                    $name = '['.$name.']';
                }
            }
            $field->modelName = 'form.'.implode('.', $names);
            $field->id = $field->getId();
            // $field = (array)$field;

        }
        $this->fields=(function($fields){
            $a = [];

            $fs =  $fields->getAllFields();
           foreach ($fs as $f){
               $a[] = (array)$f;
           }
           return $a;
        }) ($outsideTabs);


        foreach($primaryTabs as $tab=>$primaryTabFields)
        {
            foreach ($primaryTabFields as $primaryTabField){
                $this->form[$primaryTabField->arrayName][$primaryTabField->fieldName] = $primaryTabField->value;

                $names = HtmlHelper::nameToArray($primaryTabField->getName());

                foreach($names as &$name){
                    if(is_numeric($name)){
                        $name = '['.$name.']';
                    }
                }
                $primaryTabField->modelName = 'form.'.implode('.', $names);
                $primaryTabField->id = $primaryTabField->getId();

            }


            // $field = (array)$field;

        }
        $this->tabs=(function($primaryTabs){
            $a = [];

            foreach ($primaryTabs as $tab=>$primaryTabFields){
                foreach($primaryTabFields as $primaryTabField){
                    $a[$tab][]= (array)$primaryTabField;
                }
            }
           return $a;
        }) ($primaryTabs);


        foreach($secondaryTabs as $secondaryTabFields)
        {
            foreach ($secondaryTabFields as $secondaryTabField){
                $this->form[$secondaryTabField->arrayName][$secondaryTabField->fieldName] = $secondaryTabField->value;

                $names = HtmlHelper::nameToArray($secondaryTabField->getName());

                foreach($names as &$name){
                    if(is_numeric($name)){
                        $name = '['.$name.']';
                    }
                }
                $secondaryTabField->modelName = 'form.'.implode('.', $names);
                $secondaryTabField->id = $secondaryTabField->getId();

            }




        }

        $this->secondTabs=(function($secondaryTabs){
            $a = [];

            foreach ($secondaryTabs as $tab=>$secondaryTabFields){
                foreach($secondaryTabFields as $secondaryTabField){
                    $a[$tab][]= (array)$secondaryTabField;
                }
            }
           return $a;
        }) ($secondaryTabs);


    }

    public function save()
    {

        // dd($this->form);
        request()->merge($this->form);
        $c =  (new \Plugin\Wpjscc\Hello\Controllers\Hellos());
        if($this->context=='create'){
            $c->asExtension('FormController')->create_onSave();

        }elseif($this->context=='update'){
            $c->asExtension('FormController')->update_onSave($this->modelId);

        }

    }





    public function render()
    {

        return view('backend::widgets.form.fields');
    }
}
