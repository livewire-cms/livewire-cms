<?php

namespace Modules\Backend\View\Livewire\Widgets\Filter;

use Livewire\Component;

class Toggle extends Component
{



    public $form;

    public $scopeName;
    public $value;
    public $prefix;

    public function mount($scopeName,$value,$prefix)
    {
        // dd($lists);
        $this->scopeName = $scopeName;
        $this->value = $value;
        $this->prefix = $prefix;

    }
    public function updatedValue($value)
    {

        $this->emitUp('filter',[
            'scopeName' => $this->scopeName,
            'value' => $value?1:0,

        ]);
    }

    public function render()
    {
        return view('backend::widgets.filter.toggle');
    }
}
