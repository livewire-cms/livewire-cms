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

   public $selectedRows=[];

    protected $listeners = ['search','filter','onApplySetup'];



    public function mount($widget, $prefix)
    {
        // dd($widget, $prefix);
        $this->widget = $widget;
        $this->prefix = $prefix;
    }

    public function refresh()
    {
        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        $c->asExtension('ListController')->index();
        $this->widget = $c->widget;


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
        $this->widget = $c->widget;
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
        $this->widget = $c->widget;


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
        $this->widget = $c->widget;
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
        $this->widget = $c->widget;
    }

    public function filter($data)
    {
        $this->update = !$this->update;
        // dd(request()->all(),$data);
        // dd($data);
        request()->merge($data);
        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        $c->asExtension('ListController')->index();
        //$widget = 执行->asExtension('ListController')->index()
        $c->widget->{$this->prefix.'Filter'}->onFilterUpdate();
        $this->widget = $c->widget;
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
        // //search
        // if ($this->search) {//搜索的时候

        //     //todo 找到控制器((
        //     $c =  (new \Modules\Hello\Controllers\Hellos());
        //     $c->asExtension('ListController')->index();
        //     //$widget = 执行->asExtension('ListController')->index()

        //     $c->widget->{$this->prefix.'ToolbarSearch'}->setActiveTerm($this->search);

        //     $c->widget->{$this->prefix.'ToolbarSearch'}->fireEvent('search.submit', []);



        //     // 看下 lists onRefresh许更改
        //     // dd($this->search);
        //     // dd(request()->all());
        //     $this->widget = $c->widget;

        // // $this->prefix = 'list';
        // } else {//
        //     if (!$this->widget) {//搜索条件为空的时候
        //         //todo 找到控制器((
        //         $c =  (new \Modules\Hello\Controllers\Hellos());
        //         $c->asExtension('ListController')->index();
        //         //$widget = 执行->asExtension('ListController')->index()


        //         // 看下 lists onRefresh许更改
        //         // dd($this->search);
        //         // dd(request()->all());
        //         $this->widget = $c->widget;
        //         $this->widget->{$this->prefix.'ToolbarSearch'}->setActiveTerm('');
        //         $this->widget->{$this->prefix.'ToolbarSearch'}->fireEvent('search.submit', []);
        //     } else {//刷新页面的时候
        //        $this->search =  $this->widget->{$this->prefix.'ToolbarSearch'}->getActiveTerm();//默认的搜索值
        //     }
        // }


        //filter
        //todo 触发
        //$widget->listFilter->onFilterUpdate()
        //$widget->listFilter->setScopeValue($scope,$value);//$value需要模拟
        //$widget->listFilter->fireEvent('filter.update', [])

        // dd($this->lists);
        return view('backend::widgets.lists', ['widget' => $this->widget]);
    }
}
