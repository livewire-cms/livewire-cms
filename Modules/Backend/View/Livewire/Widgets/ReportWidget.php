<?php namespace Modules\Backend\View\Livewire\Widgets;

use Livewire\Component;


class ReportWidget extends Component
{

    public $alias;
    protected $reportWidget;
    protected $widget;

    public function mount($widget,$reportWidget)
    {
        $reportcontainer = $widget->reportContainer;
        $reportcontainer->render();
        $this->reportWidget = $reportWidget; //['welcome'=>['widget'=>object,'sortOrder'=>50]]
        $this->widget = $widget;
        $this->alias = $reportWidget['widget']->alias;

    }

    public function render()
    {
        return view('backend::widgets.reportwidget',['widget'=>$this->widget,'reportWidget'=>$this->reportWidget]);
    }
}
