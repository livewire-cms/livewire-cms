
@php
    if(!isset($widget)){
        $widget = null;
        // dd($form);
    }
@endphp



<div >
    hello world livewire field
    <x-back-form-fields :fields="$fields" :form="$form" :widget="$widget"></x-back-form-fields>
    <x-back-form-tabs :tabs="$tabs" tab_name="tab" :form="$form" :widget="$widget"></x-back-form-tabs>
    <x-back-form-tabs :tabs="$secondTabs" tab_name="secondTab" :form="$form" :widget="$widget"></x-back-form-tabs>
    <p wire:click="onAddItem"> 添加</p>
</div>
