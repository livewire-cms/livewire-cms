<?php namespace Modules\Test\ReportWidgets;

use BackendAuth;
use Modules\Backend\Models\AccessLog;
use Modules\Backend\Classes\ReportWidgetBase;
use Modules\Backend\Models\BrandSetting;
use Exception;


/**
 * User welcome report widget.
 *
 * @package winter\wn-backend-module
 * @author Alexey Bobkov, Samuel Georges
 */
class Yibiaopan extends ReportWidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'yibiaopan';

    /**
     * Renders the widget.
     */
    public function render()
    {
        try {
            $this->loadData();
        }
        catch (Exception $ex) {
            $this->vars['error'] = $ex->getMessage();
        }
        return $this->makePartial('widget');
    }

    public function defineProperties()
    {
        return [
            'title' => [
                'title'             => 'backend::lang.dashboard.widget_title_label',
                'default'           => 'backend::lang.dashboard.welcome.widget_title_default',
                'type'              => 'string',
                'validationPattern' => '^.+$',
                'validationMessage' => 'backend::lang.dashboard.widget_title_error',
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function loadAssets()
    {
        // $this->addCss('css/welcome.css', 'core');
    }

    protected function loadData()
    {


    }
}
