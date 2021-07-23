<?php

namespace Modules\Backend\View\Livewire\Widgets;

use Livewire\Component;

class Filter extends Component
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
    public $options;
    public $prefix;

    public function mount($scopeName,$options,$prefix)
    {
        // dd($lists);
        $this->scopeName = $scopeName;
        $this->options = $options;
        $this->prefix = $prefix;

    }

    public function render()
    {
        return view('backend::widgets.filter');
    }
}
