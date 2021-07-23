<?php

namespace Modules\Backend\View\Livewire\Widgets\Form;

use Livewire\Component;
use Route;
use Modules\LivewireCore\Html\Helper as HtmlHelper;

class Text extends Component
{

    public $name;
    public $name_id;
    public $value;
    public $placeholder;
    public $maxlength;
    public $attributes;


    public function mount($field)
    {
        // dd($widget, $prefix);
        $this->name = $field->getName();
        $this->name_id = $field->getId();
        $this->value = $field->value;
        $this->placeholder = $field->placeholder;
        $this->maxlength = $field->hasAttribute('maxlength') ? '' : 'maxlength="255"';
        $this->attributes = $field->getAttributes();

    }





    public function render()
    {

        return view('backend::widgets.form.text');
    }
}
