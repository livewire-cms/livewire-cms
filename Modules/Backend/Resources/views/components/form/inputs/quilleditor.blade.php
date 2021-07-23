
@props(['field','form','widget'])

@php

@endphp
<div>

    <x-form.quill-editor wire:model.lazy="{{ $field['modelName']}}" :value="$field['value']" :config="$field['config']['config']" :prefix="str_replace('-','_',$field['id'])"></x-form.quill-editor>


</div>
