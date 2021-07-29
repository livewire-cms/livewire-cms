<?php namespace Modules\Backend\Http\Controllers;

use Backend;
use BackendMenu;
use Modules\Backend\Classes\Controller;

use Modules\System\Classes\SideNavManager;

/**
 * Access Logs controller
 *
 * @package winter\wn-system-module
 * @author Alexey Bobkov, Samuel Georges
 */
class AccessLogs extends Controller
{
    /**
     * @var array Extensions implemented by this controller.
     */
    public $implement = [
        \Modules\Backend\Behaviors\ListController::class
    ];

    /**
     * @var array `ListController` configuration.
     */
    public $listConfig = 'config_list.yaml';

    /**
     * @var array Permissions required to view this page.
     */
    public $requiredPermissions = ['system.access_logs'];

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Modules.System', 'system');
        SideNavManager::setContext('Modules.Backend', 'access_logs');
    }

    public function index()
    {

        $this->asExtension('ListController')->index();

        return view('backend::index',['widget'=>$this->widget]);

    }

}
