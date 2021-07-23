<?php

namespace Modules\Backend\View\Livewire\Widgets\RelationLists;

use Livewire\Component;
use Route;

class Column extends Component
{


    //父
    public $context;//create or update
    public $modelId;//update/:id


    //关联关系
    public $column;
    public $name;
    public $value;
    public $manage_id;

    public $prefix;
    public $sessionKey;//父的sessionKey
    // protected $listeners = ['search','filter'];



    public function mount($context, $modelId,$manage_id,$name,$value,$column,$prefix,$sessionKey)
    {

        $this->context = $context;
        $this->modelId = $modelId;

        $this->manage_id = $manage_id;
        $this->name = $name;
        $this->value = $value;
        $this->column = $column;
        $this->prefix = $prefix;
        $this->sessionKey = $sessionKey;

    }







    public function onRelationClickViewList()
    {
//         _relation_field: comments
// _relation_extra_config: eyJyZWFkT25seSI6ZmFsc2V9
// manage_id: 1
// _session_key: KQqL4z1ZiqyLp93SKS52Ozpxitpe3BMXizvKzanP
        // dd($this->manage_id);
        // dd($this->prefix);
        $this->emitTo('backend.livewire.widgets.relation_form','onRelationClickViewList',[
            '_relation_field' => $this->prefix,
            'modelId' => $this->modelId,
            'manage_id' => $this->manage_id,
            'context' => $this->context,
            '_relation_session_key' => $this->sessionKey,
        ]

    );
    }





    public function render()
    {

        return view('backend::widgets.relation_lists.column');
    }
}
