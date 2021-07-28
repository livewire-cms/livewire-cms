
@php

if(!isset($widget)){
        $widget = null;
    }else{
        // dd($widget);
    }

//todo if $field['type']=='widget' todo form makeFormFieldWidget
// dd($fields,$tabs,$secondTabs);
@endphp
<x-jet-dialog-modal wire:model="modal">
    <x-slot name="title">
        {{ __($context) }} {{__($relation_field)}}
    </x-slot>

    <x-slot name="content">
        <div class="container grid p-6 mx-auto">

            @if ($modal)

                <form  >
                    <div x-data="{tab:'{{key($tabs)}}',secondTab:'{{key($secondTabs)}}'}"  class="">
                        <x-back-form-fields :fields="$fields" :form="$form" :relation_field="$relation_field" :widget="$widget"></x-back-form-fields>
                        <x-back-form-tabs :tabs="$tabs" tab_name="tab" :form="$form" :relation_field="$relation_field" :widget="$widget"></x-back-form-tabs>
                        <x-back-form-tabs :tabs="$secondTabs" tab_name="secondTab" :form="$form" :relation_field="$relation_field" :widget="$widget"></x-back-form-tabs>
                    </div>
                </form>
            @endif
        </div>

    </x-slot>

    <x-slot name="footer">
        <div wire:loading>
            Loading...
        </div>
        <x-jet-secondary-button wire:click="$toggle('modal')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-2" wire:click="save" wire:loading.attr="disabled">
            {{ __('Save') }}
        </x-jet-danger-button>
    </x-slot>
</x-jet-dialog-modal>
