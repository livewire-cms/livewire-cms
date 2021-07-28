<?php

namespace Modules\Backend\View\Livewire\Widgets;

use Livewire\Component;
use Route;

class RelationLists extends Component
{
    protected $widget;
    public $prefix;//relation name


    public $context;//create or update
    public $modelId;//update/:id

    public $parentSessionKey;
    public $sessionKey;

    public $update;

    // protected $listeners = ['search','filter'];



    public function mount($widget, $prefix)
    {

        $this->context = $widget->form->context;
        $this->modelId = $widget->form->model->getKey();
        $this->sessionKey = $widget->form->getSessionKey();
        $this->parentSessionKey = $widget->form->getSessionKey();


        // dd($widget, $prefix);
        $this->widget = $widget;
        $this->prefix = $prefix;

    }

    public function search($data)
    {


        $this->update =!$this->update;

        $pre = 'relation'.ucfirst(\Str::camel($this->prefix));

        request()->merge($data);
        request()->merge([
            '_session_key' => $this->parentSessionKey
        ]);

        //todo 找到控制器((
        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if(!$c){
            throw new \RuntimeException('Could not find controller');
        }
        if(!$this->modelId){
            $c->asExtension('FormController')->create($this->context);
        }else{
            $c->asExtension('FormController')->update($this->modelId,$this->context);
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
    public function onPaginate($page)
    {
        request()->merge([
            'page' => $page,
            '_session_key' => $this->parentSessionKey

        ]);
        $this->update = !$this->update;

        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }

        $pre = 'relation'.ucfirst(\Str::camel($this->prefix));


        $c->asExtension('FormController')->update($this->modelId,$this->context);

        //$widget = 执行->asExtension('ListController')->index()
        $c->relationRender($this->prefix);

        $c->widget->{$pre.'ViewList'}->onPaginate();

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
        $c->asExtension('FormController')->update($this->modelId,$this->context);
        $c->relationRender($this->prefix);

        //$widget = 执行->asExtension('ListController')->index()
        $c->widget->{$pre.'Filter'}->onFilterUpdate();
        $this->widget = $c->widget;

    }


    public function onRelationClickViewList($manageId)
    {
        $this->emitTo('backend.livewire.widgets.relation_form','onRelationClickViewList',[
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
