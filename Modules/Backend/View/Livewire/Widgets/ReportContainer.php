<?php namespace Modules\Backend\View\Livewire\Widgets;

use Livewire\Component;


class ReportContainer extends Component
{

    protected $reportWidgets;
    protected $widget;

    public function mount($widget)
    {
        $reportcontainer = $widget->reportContainer;
        $reportcontainer->render();

        $reportWidgets = $reportcontainer->getReportWidgets(); //['welcome'=>['widget'=>object,'sortOrder'=>50]]

        $this->reportWidgets = $reportWidgets;
        $this->widget = $widget;

    }

    public function render()
    {
        return view('backend::widgets.reportcontainer',['widget'=>$this->widget,'reportWidgets'=>$this->reportWidgets]);
    }
}
