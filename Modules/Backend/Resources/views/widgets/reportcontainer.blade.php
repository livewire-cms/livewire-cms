

<div class="">
    @if($update)
        <form>
    @endif
        <div wire:ignore>
        @if($widget)
            @foreach ($reportWidgets as $reportWidget)
                @livewire('backend.livewire.widgets.reportwidget',[
                    'widget'=>$widget,
                    'alias'=>$reportWidget['widget']->alias,
                ],key($reportWidget['widget']->alias))
                <span wire:click="onAction('onRemoveWidget',{alias:'{{$reportWidget['widget']->alias}}'})">移除</span>
            @endforeach
        @endif
        </div>
    @if($update)
    </form>
    @endif



<span wire:click="onAction('onLoadAddPopup',{})"> 设置</span>

<x-jet-dialog-modal wire:model="modal" maxWidth="2xl">
    <x-slot name="title">
        {{__('backend::lang.dashboard.add_widget')}}
    </x-slot>

    <x-slot name="content">
        <div class="container grid p-6">

            @if ($modal)
                <form>
                    <div x-data="{tab:'{{key($tabs)}}',secondTab:'{{key($secondTabs)}}'}"  class="">
                        <x-back-form-fields :fields="$fields" :form="$form" :widget="$formWidget"></x-back-form-fields>
                        <x-back-form-tabs :tabs="$tabs" tab_name="tab" :form="$form" :widget="$formWidget"></x-back-form-tabs>
                        <x-back-form-tabs :tabs="$secondTabs" tab_name="secondTab" :form="$form" :widget="$formWidget"></x-back-form-tabs>
                    </div>
                </form>
             @endif

        </div>

    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('modal')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-jet-secondary-button>
        <x-jet-danger-button class="ml-2" wire:click="onAction('onAddWidget',{})" wire:loading.attr="disabled">
            {{ __('Save') }}
        </x-jet-danger-button>

    </x-slot>
</x-jet-dialog-modal>


</div>
