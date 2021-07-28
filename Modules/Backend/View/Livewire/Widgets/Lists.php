<?php

namespace Modules\Backend\View\Livewire\Widgets;

use Livewire\Component;
use Route;

class Lists extends Component
{
    use \WireUi\Traits\Actions;

    protected $widget;
    public $prefix;
    public $update;

    public $form;


    public $selectedRows=[];

    protected $listeners = ['search','filter','onApplySetup'];



    public function mount($widget, $prefix='')
    {
        // dd($widget, $prefix);
        $this->widget = $widget;
        if($prefix){
            $this->prefix = $prefix;
        }

    }

    public function refresh()
    {
        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        $c->asExtension('ListController')->index();


        $this->mount($c->widget);
    }



    public function search($data)
    {
        // dd(request()->input('fingerprint.path'));
        $this->update = !$this->update;

        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }



        $c->asExtension('ListController')->index();
        //$widget = 执行->asExtension('ListController')->index()

        $c->widget->{$this->prefix.'ToolbarSearch'}->setActiveTerm($data['search']??'');

        $c->widget->{$this->prefix.'ToolbarSearch'}->fireEvent('search.submit', []);

        // 看下 lists onRefresh许更改
        // dd($this->search);
        // dd(request()->all());
        $this->mount($c->widget);

    }
    // sortColumn
    public function onSort($data)
    {
        request()->merge($data);
        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        $c->asExtension('ListController')->index();
        $c->widget->{$this->prefix}->onSort();
        $this->mount($c->widget);



    }

    public function onApplySetup($data)
    {
        request()->merge($data);
        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        $c->asExtension('ListController')->index();
        $c->widget->{$this->prefix}->onApplySetup();
        $this->mount($c->widget);

    }


    public function onPaginate($page)
    {
        request()->merge(['page' => $page]);
        $this->update = !$this->update;

        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }



        $c->asExtension('ListController')->index();
        //$widget = 执行->asExtension('ListController')->index()

        $c->widget->{$this->prefix}->onPaginate();
        // 看下 lists onRefresh许更改
        // dd($this->search);
        // dd(request()->all());
        $this->mount($c->widget);

    }

    public function filter($data)
    {
        $this->update = !$this->update;
        // dd(request()->all(),$data);
        // dd($data);
        // dd($data);
        request()->merge($data);
        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        $c->asExtension('ListController')->index();
        //$widget = 执行->asExtension('ListController')->index()
        $c->widget->{$this->prefix.'Filter'}->onFilterUpdate();
        $this->mount($c->widget);

    }



    public function onQuickFormCreate($data)
    {
        // dd($data);
        $this->emitTo(
            'backend.livewire.widgets.quickform',
            'onQuickFormCreate',
            $data
        );
    }
    public function onQuickFormUpdate($data)
    {
        // dd($data);
        $this->emitTo(
            'backend.livewire.widgets.quickform',
            'onQuickFormUpdate',
            $data
        );
    }

    public function onAction($method,$params=[])
    {

        $c = find_controller_by_url(request()->input('fingerprint.path'));
        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }

        if(!method_exists($c,$method)){
            throw new \RuntimeException($method.'do not exist');
        }

        if(empty($params)){
            $params = [];
        }

        if(!is_array($params)){
            $params = [$params];
        }



        call_user_func_array([$c,$method],[$this,$params]);

    }

    public function render()
    {

        return view('backend::widgets.lists', ['widget' => $this->widget]);
    }
}
