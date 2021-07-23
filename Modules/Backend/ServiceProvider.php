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
use Modules\Backend\View\Livewire\Widgets\Lists as LivewireWidgetLists;
use Modules\Backend\View\Livewire\Widgets\ListApplySetup as LivewireWidgetListApplySetup;
use Modules\Backend\View\Livewire\Widgets\RelationLists as LivewireWidgetRelationLists;
use Modules\Backend\View\Livewire\Widgets\RelationLists\Column as LivewireWidgetRelationListsColumn;
use Modules\Backend\View\Livewire\Widgets\Toolbar as LivewireWidgetToolbar;
use Modules\Backend\View\Livewire\Widgets\Search as LivewireWidgetSearch;
use Modules\Backend\View\Livewire\Widgets\Filter as LivewireWidgetFilter;
use Modules\Backend\View\Livewire\Widgets\Filter\Select as LivewireWidgetFilterSelect;
use Modules\Backend\View\Livewire\Widgets\Filter\Input as LivewireWidgetFilterInput;
use Modules\Backend\View\Livewire\Widgets\Items as LivewireWidgetItems;
use Modules\Backend\View\Livewire\Widgets\MainMenu as LivewireWidgetMainMenu;
use Modules\Backend\View\Livewire\Widgets\SideMenu as LivewireWidgetSideMenu;
use Modules\Backend\View\Livewire\Widgets\Form as LivewireWidgetForm;
use Modules\Backend\View\Livewire\Widgets\QuickForm as LivewireWidgetQuickForm;
use Modules\Backend\View\Livewire\Widgets\RelationForm as LivewireWidgetRelationForm;
use Modules\Backend\View\Livewire\Widgets\Form\Fields as LivewireWidgetFormFields;
use Modules\Backend\View\Livewire\Widgets\Form\Repeater as LivewireWidgetFormRepeater;
use Modules\Backend\View\Livewire\Widgets\Form\EditorJs as LivewireWidgetFormEditorJs;
use Modules\Backend\View\Livewire\Widgets\Form\Text as LivewireWidgetFormText;

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
        $this->registerBladeDirectives();
        // $this->app->afterResolving(BladeCompiler::class, function () {

                Livewire::component('backend.livewire.widgets.toolbar', LivewireWidgetToolbar::class);
                Livewire::component('backend.livewire.widgets.search', LivewireWidgetSearch::class);
                Livewire::component('backend.livewire.widgets.filter', LivewireWidgetFilter::class);
                Livewire::component('backend.livewire.widgets.filter.select', LivewireWidgetFilterSelect::class);
                Livewire::component('backend.livewire.widgets.filter.input', LivewireWidgetFilterInput::class);
                Livewire::component('backend.livewire.widgets.lists', LivewireWidgetLists::class);
                Livewire::component('backend.livewire.widgets.listapplysetup', LivewireWidgetListApplySetup::class);
                Livewire::component('backend.livewire.widgets.relation_lists', LivewireWidgetRelationLists::class);
                Livewire::component('backend.livewire.widgets.relation_lists.column', LivewireWidgetRelationListsColumn::class);
                Livewire::component('backend.livewire.widgets.items', LivewireWidgetItems::class);
                Livewire::component('backend.livewire.widgets.mainmenu', LivewireWidgetMainMenu::class);
                Livewire::component('backend.livewire.widgets.sidemenu', LivewireWidgetSideMenu::class);
                Livewire::component('backend.livewire.widgets.form', LivewireWidgetForm::class);
                Livewire::component('backend.livewire.widgets.quickform', LivewireWidgetQuickForm::class);
                Livewire::component('backend.livewire.widgets.relation_form', LivewireWidgetRelationForm::class);
                Livewire::component('backend.livewire.widgets.form.fields', LivewireWidgetFormFields::class);
                Livewire::component('backend.livewire.widgets.form.repeater', LivewireWidgetFormRepeater::class);
                Livewire::component('backend.livewire.widgets.form.editorjs', LivewireWidgetFormEditorJs::class);
                Livewire::component('backend.livewire.widgets.form.text', LivewireWidgetFormText::class);

        // });
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

        Blade::directive('livewireEditorjsScripts', function () {
            // $scriptsUrl = asset('/vendor/livewire-editorjs/editorjs.js',true);
            $scriptsUrl = '/vendor/livewire-editorjs/editorjs.js';

            return <<<EOF
                <script src="$scriptsUrl"></script>
            EOF;
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
            $this->registerFormComponent('inputs.default');
            $this->registerFormComponent('inputs.text');
            $this->registerFormComponent('inputs.textarea');
            $this->registerFormComponent('inputs.radio');
            $this->registerFormComponent('inputs.checkbox');
            $this->registerFormComponent('inputs.wangeditor');
            $this->registerFormComponent('inputs.toggle');
            $this->registerFormComponent('inputs.checkboxlist');
            $this->registerFormComponent('inputs.dropdown');
            $this->registerFormComponent('inputs.editorjs');
            $this->registerFormComponent('inputs.quilleditor');
            $this->registerFormComponent('inputs.datepicker');
            $this->registerFormComponent('inputs.datetimepicker');
            $this->registerFormComponent('inputs.partial');
            $this->registerFormComponent('inputs.password');
            $this->registerFormComponent('inputs.email');
            $this->registerFormComponent('inputs.fileupload');
            $this->registerFormComponent('inputs.codemirror');
            $this->registerFormComponent('inputs.markdown');
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
