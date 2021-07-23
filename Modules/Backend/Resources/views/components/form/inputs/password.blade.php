
@props(['field','form','widget'])

@php

@endphp
<div>


    <input
    type="password"
    id="{{$field['id']}}"
    wire:model.lazy="{{ $field['modelName']}}"
    class="w-full border border-gray-400 text-gray-800 placeholder-gray-400 rounded focus:border-transparent focus:outline-none focus:shadow-outline px-3 py-2"
    placeholder="{{$field['placeholder']}}"
    />

</div>
