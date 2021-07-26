<?php

namespace Modules\Backend\View\Livewire\Widgets\Filter;

use Livewire\Component;

class Select extends Component
{


    // protected $widget;
    // protected $prefix;

    // public function mount($widget,$prefix)
    // {
    //     // dd($lists);
    //     $this->widget = $widget;
    //     $this->prefix = $prefix;
    // }
    public $form;

    public $scopeName;
    public $value;
    public $options;
    public $prefix;

    public function mount($scopeName,$options,$value,$prefix)
    {
        // dd($lists);
        $this->scopeName = $scopeName;
        $this->options = $options;



        $this->value = $value? array_map(function($v){
            return (string)$v;
        },array_keys($value)):[];
        $this->prefix = $prefix;

    }
    public function updatedValue($value)
    {
        // dd($value);
        // dd($this->options);

        if(is_string($value)){
            return ;
        }

        $options = [];
        foreach ($this->options as $id=>$name){
            if(in_array($id,$value)){
                $options[]=[
                    'id'=>$id,
                    'name'=>$name
                ];
            }
        }
        $this->emitUp('filter',[
            'scopeName' => $this->scopeName,
            'options' => json_encode($options)
        ]);
    }

    public function render()
    {

        return view('backend::widgets.filter.select');
    }
}
