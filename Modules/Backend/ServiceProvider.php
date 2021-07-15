<?php

namespace Modules\Backend;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Support\Facades\Blade;
use Modules\Backend\View\Components\AppLayout;
use Modules\Backend\View\Components\Containers\Container;
use Modules\Backend\View\Components\Widgets\Form;
use Modules\Backend\View\Components\Widgets\Form\Fields;
use Modules\Backend\View\Components\Widgets\Form\Tabs;
use Modules\Backend\View\Components\Widgets\Form\Widget;
use Livewire\Livewire;
use Modules\Backend\View\Widgets\Lists as WidgetLists;
use Modules\Backend\View\Widgets\RelationLists as WidgetRelationLists;
use Modules\Backend\View\Widgets\RelationLists\Column as WidgetRelationListsColumn;
use Modules\Backend\View\Widgets\Toolbar as WidgetToolbar;
use Modules\Backend\View\Widgets\Search as WidgetSearch;
use Modules\Backend\View\Widgets\Filter as WidgetFilter;
use Modules\Backend\View\Widgets\Filter\Select as WidgetFilterSelect;
use Modules\Backend\View\Widgets\Filter\Input as WidgetFilterInput;
use Modules\Backend\View\Widgets\Items as WidgetItems;
use Modules\Backend\View\Widgets\MainMenu as WidgetMainMenu;
use Modules\Backend\View\Widgets\SideMenu as WidgetSideMenu;
use Modules\Backend\View\Widgets\Form as WidgetForm;
use Modules\Backend\View\Widgets\RelationForm as WidgetRelationForm;
use Modules\Backend\View\Widgets\Form\Fields as WidgetFormFields;
use Modules\Backend\View\Widgets\Form\Repeater as WidgetFormRepeater;
use Modules\Backend\View\Widgets\Form\Text as WidgetFormText;

use BackendMenu;
use Backend;
use Modules\Backend\Classes\WidgetManager;
use Modules\Backend\Classes\PermissionManager;
use Illuminate\Http\Request;
use Livewire\TemporaryUploadedFile;
use Illuminate\Filesystem\Filesystem;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Request::macro('setConvertedFiles', function ($files) {
            $this->convertedFiles = $files;
            return $this;
        });

        Filesystem::macro('isLocalDisk', function ($disk) {
            return ($disk->getDriver()->getAdapter() instanceof \League\Flysystem\Adapter\Local);
        });



        /*
        * Backend specific
        */
        if ($this->app['execution.context']=='back-end') {
            $this->registerBackendNavigation();
            $this->registerBackendReportWidgets();
            $this->registerBackendWidgets();
            $this->registerBackendPermissions();
            $this->registerBackendSettings();
        }
        $this->app->afterResolving(BladeCompiler::class, function () {

                Livewire::component('backend.widgets.toolbar', WidgetToolbar::class);
                Livewire::component('backend.widgets.search', WidgetSearch::class);
                Livewire::component('backend.widgets.filter', WidgetFilter::class);
                Livewire::component('backend.widgets.filter.select', WidgetFilterSelect::class);
                Livewire::component('backend.widgets.filter.input', WidgetFilterInput::class);
                Livewire::component('backend.widgets.lists', WidgetLists::class);
                Livewire::component('backend.widgets.relation_lists', WidgetRelationLists::class);
                Livewire::component('backend.widgets.relation_lists.column', WidgetRelationListsColumn::class);
                Livewire::component('backend.widgets.items', WidgetItems::class);
                Livewire::component('backend.widgets.mainmenu', WidgetMainMenu::class);
                Livewire::component('backend.widgets.sidemenu', WidgetSideMenu::class);
                Livewire::component('backend.widgets.form', WidgetForm::class);
                Livewire::component('backend.widgets.relation_form', WidgetRelationForm::class);
                Livewire::component('backend.widgets.form.fields', WidgetFormFields::class);
                Livewire::component('backend.widgets.form.repeater', WidgetFormRepeater::class);
                Livewire::component('backend.widgets.form.text', WidgetFormText::class);

        });
    }


    /*
     * Register navigation
     */
    protected function registerBackendNavigation()
    {

        //todo后台管理基础页面
        BackendMenu::registerCallback(function ($manager) {
            $manager->registerMenuItems('Winter.Backend', [
                // 'dashboard' => [
                //     'label'       => 'backend::lang.dashboard.menu_label',
                //     'icon'        => 'icon-dashboard',
                //     'iconSvg'     => 'modules/backend/assets/images/dashboard-icon.svg',
                //     'url'         => Backend::url('backend'),
                //     'permissions' => ['backend.access_dashboard'],
                //     'order'       => 10
                // ],
                // 'media' => [
                //     'label'       => 'backend::lang.media.menu_label',
                //     'icon'        => 'icon-folder',
                //     'iconSvg'     => 'modules/backend/assets/images/media-icon.svg',
                //     'url'         => Backend::url('backend/media'),
                //     'permissions' => ['media.*'],
                //     'order'       => 200
                // ]
            ]);
            // $manager->registerOwnerAlias('Winter.Backend', 'October.Backend');
        });
    }

    /*
     * Register report widgets
     */
    protected function registerBackendReportWidgets()
    {
        \Modules\Backend\Classes\WidgetManager::instance()->registerReportWidgets(function ($manager) {
            // $manager->registerReportWidget(\Backend\ReportWidgets\Welcome::class, [
            //     'label'   => 'backend::lang.dashboard.welcome.widget_title_default',
            //     'context' => 'dashboard'
            // ]);
        });
    }
    /*
        * Register permissions
        */
    protected function registerBackendPermissions()
    {
        PermissionManager::instance()->registerCallback(function ($manager) {
            //todo
        });
    }
    /*
     * Register widgets
     */
    protected function registerBackendWidgets()
    {
        WidgetManager::instance()->registerFormWidgets(function ($manager) {
            $manager->registerFormWidget(\Modules\Backend\FormWidgets\Relation::class, 'relation');
            $manager->registerFormWidget(\Modules\Backend\FormWidgets\FileUpload::class, 'fileupload');
            $manager->registerFormWidget(\Modules\Backend\FormWidgets\Repeater::class, 'repeater');

            // $manager->registerFormWidget('Backend\FormWidgets\CodeEditor', 'codeeditor');
            // $manager->registerFormWidget('Backend\FormWidgets\RichEditor', 'richeditor');
            // $manager->registerFormWidget('Backend\FormWidgets\MarkdownEditor', 'markdown');
            // $manager->registerFormWidget('Backend\FormWidgets\FileUpload', 'fileupload');
            // $manager->registerFormWidget('Backend\FormWidgets\Relation', 'relation');
            // $manager->registerFormWidget('Backend\FormWidgets\DatePicker', 'datepicker');
            // $manager->registerFormWidget('Backend\FormWidgets\TimePicker', 'timepicker');
            // $manager->registerFormWidget('Backend\FormWidgets\ColorPicker', 'colorpicker');
            // $manager->registerFormWidget('Backend\FormWidgets\DataTable', 'datatable');
            // $manager->registerFormWidget('Backend\FormWidgets\RecordFinder', 'recordfinder');
            // $manager->registerFormWidget('Backend\FormWidgets\Repeater', 'repeater');
            // $manager->registerFormWidget('Backend\FormWidgets\TagList', 'taglist');
            // $manager->registerFormWidget('Backend\FormWidgets\MediaFinder', 'mediafinder');
            // $manager->registerFormWidget('Backend\FormWidgets\NestedForm', 'nestedform');
            // $manager->registerFormWidget('Backend\FormWidgets\Sensitive', 'sensitive');
        });
    }
    /*
    * Register settings
    */
    protected function registerBackendSettings()
    {
        //todo 设置管理
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


        // dd(__DIR__.'/resources/views');
        $this->configureComponents();
    }



    protected function registerBladeDirectives()
    {
        Blade::directive('formwidget', function ($expression) {
            return <<<EOT
            <?php if ( isset({$expression}['html'])) :
                echo '<x-back-app-guest>'.{$expression}['html'].'</x-back-app-guest>';
                ?>  <?php endif; ?>
            EOT;
        });
    }





    /**
    * Configure the Jetstream Blade components.
    *
    * @return void
    */
    protected function configureComponents()
    {
        Blade::component('back-app-layout', AppLayout::class);
        Blade::component('back-container', Container::class);
        Blade::component('back-form', Form::class);
        Blade::component('back-form-tabs', Tabs::class);
        Blade::component('back-form-fields', Fields::class);
        Blade::component('back-form-widget', Widget::class);

        $this->callAfterResolving(BladeCompiler::class, function () {
            $this->registerComponent('action-message');
            $this->registerFormComponent('fileupload');
            $this->registerFormComponent('relation_lists');
        });
    }

    /**
     * Register the given component.
     *
     * @param  string  $component
     * @return void
     */
    protected function registerFormComponent(string $component)
    {
        Blade::component('backend::components.form.'.$component, 'back-form-'.$component);
    }
    /**
    * Register the given component.
    *
    * @param  string  $component
    * @return void
    */
    protected function registerComponent(string $component)
    {
        Blade::component('backend::components.'.$component, 'back-'.$component);
    }
}
