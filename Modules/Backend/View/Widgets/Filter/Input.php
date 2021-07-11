<?php

namespace Modules\Backend\View\Widgets\Filter;

use Livewire\Component;

class Input extends Component
{


    // protected $widget;
    // protected $prefix;

    // public function mount($widget,$prefix)
    // {
    //     // dd($lists);
    //     $this->widget = $widget;
    //     $this->prefix = $prefix;
    // }

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
            'options' => [
                'value' => [
                    $this->scopeName=>$value
                ]
            ],

        ]);
    }

    public function render()
    {
        return view('backend::widgets.filter.input');
    }
}
