
@php
    if(!isset($widget)){
        $widget = null;
    }
@endphp

<div>

        <x-jet-dialog-modal wire:model="quickFormModal">
            <x-slot name="title">
                hello create
            </x-slot>

            <x-slot name="content">
                <div class="container grid p-6 mx-auto">
                    @if (!empty($fields)||!empty($tabs)||!empty($secondTabs))
                        <form>
                            <div x-data="{tab:'{{key($tabs)}}',secondTab:'{{key($secondTabs)}}'}"  class="">
                                <x-back-form-fields :fields="$fields" :form="$form" :widget="$widget"></x-back-form-fields>
                                <x-back-form-tabs :tabs="$tabs" tab_name="tab" :form="$form" :widget="$widget"></x-back-form-tabs>
                                <x-back-form-tabs :tabs="$secondTabs" tab_name="secondTab" :form="$form" :widget="$widget"></x-back-form-tabs>
                            </div>
                        </form>
                    @endif
                </div>
            </x-slot>
            <x-slot name="footer">
                <div wire:loading>
                    Loading...
                </div>
                <x-jet-secondary-button wire:click="$toggle('quickFormModal')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="save({redirect:0})" wire:loading.attr="disabled">
                    {{ __('Save') }}
                </x-jet-danger-button>
            </x-slot>


        </x-jet-dialog-modal>


</div>
