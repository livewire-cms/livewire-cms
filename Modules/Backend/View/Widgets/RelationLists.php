<?php

namespace Modules\Backend\View\Widgets;

use Livewire\Component;
use Route;

class RelationLists extends Component
{
    protected $widget;
    public $prefix;//relation name





    public $context;//create or update
    public $modelId;//update/:id

    public $sessionKey;

    public $update;

    // protected $listeners = ['search','filter'];



    public function mount($widget, $prefix)
    {

        $this->context = $widget->form->context;
        $this->modelId = $widget->form->model->getKey();
        $this->sessionKey = $widget->form->getSessionKey();


        // dd($widget, $prefix);
        $this->widget = $widget;
        $this->prefix = $prefix;

    }

    public function search($data)
    {

        $this->update =!$this->update;

        $pre = 'relation'.ucfirst(\Str::camel($this->prefix));

        request()->merge($data);

        //todo 找到控制器((
        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if(!$c){
            throw new \RuntimeException('Could not find controller');
        }
        if($this->context=='create'){
            $c->asExtension('FormController')->create();
        }else if($this->context=='update'){
            $c->asExtension('FormController')->update($this->modelId);
        }else{
            throw new \RuntimeException('Could not find context');
        }
        $c->relationRender($this->prefix);

        //$widget = 执行->asExtension('ListController')->index()

        $c->widget->{$pre.'ToolbarSearch'}->setActiveTerm($data['search']??'');

        $c->widget->{$pre.'ToolbarSearch'}->fireEvent('search.submit', []);

        // 看下 lists onRefresh许更改
        // dd($this->search);
        // dd(request()->all());
        $this->widget = $c->widget;
    }



    public function filter($data)
    {
        $pre = 'relation'.ucfirst(\Str::camel($this->prefix));

        $this->update = !$this->update;
        // dd(request()->all(),$data);
        // dd($data);
        request()->merge($data);
        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if(!$c){
            throw new \RuntimeException('Could not find controller');
        }
        $c->asExtension('FormController')->update($this->modelId);
        $c->relationRender($this->prefix);

        //$widget = 执行->asExtension('ListController')->index()
        $c->widget->{$pre.'Filter'}->onFilterUpdate();
        $this->widget = $c->widget;

    }


    public function onRelationClickViewList($manageId)
    {
        $this->emitTo('backend.widgets.relation_form','onRelationClickViewList',[
            '_relation_field' => $this->prefix,
            'modelId' => $this->modelId,
            'manage_id' => $manageId,
            'context' => $this->context,
            '_relation_session_key' => $this->sessionKey,
        ]);

    }

    protected function getListeners()
    {
        return [
            'search'.($this->prefix?'_'.$this->prefix:'') => 'search',
            'search' => 'search',
            'filter'.($this->prefix?'_'.$this->prefix:'') => 'filter',
            'filter' => 'filter',
        ];
    }



    public function render()
    {

        return view('backend::widgets.relation_lists', ['widget' => $this->widget]);
    }
}
