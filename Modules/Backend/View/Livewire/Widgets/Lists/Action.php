<?php

namespace Modules\Backend\View\Livewire\Widgets\Lists;

use Livewire\Component;

class Action extends Component
{
    public $column;
    public $prefix;
    public $recordId;



    public function mount($column,$record,$prefix,$list)
    {
        $this->column = (array) $column;
        $this->recordId = $record->getKey();
        // dd($this->column);
    }

    public function render()
    {
        return view('backend::widgets.lists.action',[]);
    }
}
