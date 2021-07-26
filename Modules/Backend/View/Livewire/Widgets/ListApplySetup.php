<?php

namespace Modules\Backend\View\Livewire\Widgets;

use Livewire\Component;

class ListApplySetup extends Component
{
    public $form;

    public $allColumns = [];
    public $column_order=[];
    public $visible_columns=[];

    public $records_per_page = 20;

    public $options = [];


    public $prefix;

    public $modal;

    public function mount($list)
    {
        $this->prefix = $list->alias;
        $this->records_per_page = $list->recordsPerPage;
        foreach ($list->getColumns() as $fieH=>$column){
            $this->allColumns[]= (array)$column;
            $this->options[$fieH] = $column->label;
        }
        $this->visible_columns =  array_keys($list->vars['columns']);


    }

    public function onApplySetup()
    {
        $this->modal = !$this->modal;

    }

    protected function getListeners()
    {
        return [
            'onApplySetup'.($this->prefix?'_'.$this->prefix:'') => 'onApplySetup',
        ];
    }
    public function save()
    {
        $this->modal = !$this->modal;


        $this->emitUp('onApplySetup',[
            'column_order' => $this->visible_columns,
            'visible_columns' => $this->visible_columns,
            'records_per_page' => $this->records_per_page,
        ]);
    }



    public function render()
    {

        return view('backend::widgets.listapplysetup');
    }
}
