<?php namespace Modules\Backend\View\Livewire\Widgets;

use Livewire\Component;


class ReportAction extends Component
{

    protected $widget;
    public $action;

    public $modal;
    public $response;

    public function mount($widget=null)
    {

        $this->widget = $widget;

    }

    public function onAction($method, $params=[])
    {
        $c = find_controller_by_url(request()->input('fingerprint.path'));
        if (!$c) {
            throw new \RuntimeException('Could not find controller');
        }

        $c->initReportContainer();

        $reportcontainer = $c->widget->reportContainer;
        if(!method_exists($reportcontainer,$method)){
            throw new \RuntimeException($method.'do not exist');
        }

        if(empty($params)){
            $params = [];
        }

        if(!is_array($params)){
            $params = [$params];
        }
        $this->action = $method;

        request()->merge($params);
        $this->response = call_user_func_array([$reportcontainer,$method],[$this,$params]);

        $this->mount($c->widget);

    }

    public function render()
    {
        return view('backend::widgets.reportaction',['widget'=>$this->widget]);
    }
}
