<?php

namespace Modules\Backend\View\Livewire\Widgets;

use Livewire\Component;

class Search extends Component
{

    public $search;

    public $prefix;

    public function mount($search)
    {
        $this->search = $search;

    }

    public function updatedSearch($search)
    {

        $this->emitUp('search',[
            'search' => $search
        ]);
    }


    public function render()
    {

        return view('backend::widgets.search');
    }
}
