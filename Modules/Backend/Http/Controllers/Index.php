<?php namespace Modules\Backend\Http\Controllers;

use Redirect;
use BackendMenu;
use Modules\Backend\Classes\Controller;
use Modules\Backend\Widgets\ReportContainer;

/**
 * Dashboard controller
 *
 * @package winter\wn-backend-module
 * @author Alexey Bobkov, Samuel Georges
 *
 */
class Index extends Controller
{
    use \Modules\Backend\Traits\InspectableContainer;

    /**
     * @var array Permissions required to view this page.
     * @see checkPermissionRedirect()
     */
    public $requiredPermissions = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Modules.Backend', 'backend');



    }

    public function index()
    {
        if ($redirect = $this->checkPermissionRedirect()) {
            return $redirect;
        }
        $this->initReportContainer();

        $this->pageTitle = 'backend::lang.dashboard.menu_label';

        return view('backend::index.dashboard',['widget'=>$this->widget]);
    }



    /**
     * Prepare the report widget used by the dashboard
     * @param Model $model
     * @return void
     */
    public function initReportContainer()
    {
        new ReportContainer($this, 'config_dashboard.yaml');
    }

    /**
     * Custom permissions check that will redirect to the next
     * available menu item, if permission to this page is denied.
     */
    protected function checkPermissionRedirect()
    {
        if (!$this->user->hasAccess('backend.access_dashboard')) {
            $true = function () {
                return true;
            };
            if ($first = \Arr::first(BackendMenu::listMainMenuItems(), $true)) {
                return Redirect::intended($first->url);
            }
        }
    }
}
