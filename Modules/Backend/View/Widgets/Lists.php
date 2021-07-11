<?php

namespace Modules\Backend\View\Widgets;

use Livewire\Component;
use Route;

class Lists extends Component
{
    protected $widget;
    public $prefix;
    public $update;


    protected $listeners = ['search','filter'];



    public function mount($widget, $prefix)
    {
        // dd($widget, $prefix);
        $this->widget = $widget;
        $this->prefix = $prefix;
    }

    public function search($data)
    {
        // dd(request()->input('fingerprint.path'));
        $this->update = !$this->update;

        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if(!$c){
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



    public function filter($data)
    {
        $this->update = !$this->update;
        // dd(request()->all(),$data);
        // dd($data);
        request()->merge($data);
        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if(!$c){
            throw new \RuntimeException('Could not find controller');
        }
        $c->asExtension('ListController')->index();
        //$widget = 执行->asExtension('ListController')->index()
        $c->widget->{$this->prefix.'Filter'}->onFilterUpdate();
        $this->widget = $c->widget;

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
