

<div class="p-2">
    <div class="
w-1/12
w-2/12
w-3/12
w-4/12
w-5/12
w-6/12
w-7/12
w-8/12
w-9/12
w-10/12
w-11/12
w-full
"></div>
    @if($update)
        <form class="flex flex-wrap p-6">
    @endif
        <div wire:ignore class="flex  flex-wrap p-6 ">
        @if($widget)
            @foreach ($reportWidgets as $reportWidget)
            <div class="{{$reportWidget['widget']->property('ocWidgetWidth')}} relative ">
                @livewire('backend.livewire.widgets.reportwidget',[
                    'widget'=>$widget,
                    'alias'=>$reportWidget['widget']->alias,
                ],key($reportWidget['widget']->alias))
                <span class=" w-full text-gray-700 px-6 py-3 flex justify-center items-center text-xs">
                    <a class="p-2 border rounded-md border-blue-600 text-blue-600 cursor-pointer  text-sm font-bold hover:bg-blue-500 hover:text-white hover:shadow" wire:click="onAction('onRemoveWidget',{alias:'{{$reportWidget['widget']->alias}}'})">X</a>
                </span>
            </div>
            @endforeach
        @endif
        </div>
    @if($update)
    </form>
    @endif

    <span class="w-full text-gray-700 px-6 py-3 flex justify-center items-center text-xs">
        <a class="p-1 border rounded-md border-blue-600 text-blue-600 cursor-pointer  text-sm font-bold hover:bg-blue-500 hover:text-white hover:shadow" wire:click="onAction('onLoadAddPopup',{})">{{__('backend::lang.dashboard.add_widget')}}</a>
     </span>




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
