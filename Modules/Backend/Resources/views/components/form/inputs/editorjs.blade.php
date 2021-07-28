
@props(['field','form','widget'])


@php

@endphp
<div>

    <div wire:ignore>
        @livewire('backend.livewire.widgets.form.editorjs', [
            'editorId' => $field["modelName"],
            'value' => $field['value'],
            'uploadDisk' => 'public',
            'downloadDisk' => 'public',
            'class' => "bg-white dark:text-gray-400 dark:bg-gray-800",
            'style' => '',
            'readOnly' => false,
            'placeholder' => $field['placeholder'],
            'relation_field'=>$field['relation_field']
        ],key($field['id']))
    </div>


</div>
