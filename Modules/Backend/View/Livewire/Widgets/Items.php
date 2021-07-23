<?php

namespace Modules\Backend\View\Livewire\Widgets;

use Livewire\Component;

class Items extends Component
{

    protected $widget;
    protected $prefix;

    public function mount($widget,$prefix)
    {
        // dd($lists);
        $this->widget = $widget;
        $this->prefix = $prefix;
    }

    public function render()
    {
        return view('backend::widgets.items',['widget' => $this->widget->render(),'prefix' =>$this->prefix]);
    }
}
