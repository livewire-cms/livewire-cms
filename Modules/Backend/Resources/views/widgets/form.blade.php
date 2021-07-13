
@php
    if(!isset($widget)){
        $widget = null;
    }
@endphp

<div class="container grid p-6 mx-auto">
    <form wire:submit.prevent="save" >
        <div x-data="{tab:'{{key($tabs)}}',secondTab:'{{key($secondTabs)}}'}"  class="">
            <x-back-form-fields :fields="$fields" :form="$form" :widget="$widget"></x-back-form-fields>
            <x-back-form-tabs :tabs="$tabs" tab_name="tab" :form="$form" :widget="$widget"></x-back-form-tabs>
            <x-back-form-tabs :tabs="$secondTabs" tab_name="secondTab" :form="$form" :widget="$widget"></x-back-form-tabs>
            <div class=" my-4">
                <button
                class="py-2 px-4 border rounded-md border-blue-600 text-blue-600 cursor-pointer uppercase text-sm font-bold hover:bg-blue-500 hover:text-white hover:shadow"
                >保存</button>
            </div>
        </div>
    </form>
</div>
