<?php namespace Modules\Backend\View\Livewire\Widgets;

use Livewire\Component;


class ReportWidget extends Component
{

    public $alias;
    protected $reportWidget;
    protected $widget;

    public $content;

    public function mount($widget,$alias)
    {
        $reportcontainer = $widget->reportContainer;
        $reportcontainer->render();
        // dd($reportcontainer);
        $this->reportWidget = $reportcontainer->findReportWidgetByAlias($alias); //['welcome'=>['widget'=>object,'sortOrder'=>50]]
        $this->widget = $widget;

        // $this->content = $this->reportWidget['widget']->render();
    }

    public function onRefresh()
    {
        $c = find_controller_by_url(request()->input('fingerprint.path'));

        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }
        $c->initReportContainer();

        $this->mount($c->widget,$this->alias);

    }

    public function render()
    {
        return view('backend::widgets.reportwidget',['reportWidget'=>$this->reportWidget]);
    }
}
