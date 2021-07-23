
@props(['field','form','widget'])


@php

@endphp
<div>

    <x-input wire:model="{{ $field['modelName']}}"  :id="$field['id']" :icon="$field['attributes']['field']['icon']??''"  :right-icon="$field['attributes']['field']['right-icon']??''"  :placeholder="$field['placeholder']" :hint="$field['comment']" :prefix="$field['attributes']['field']['prefix']??''" :suffix="$field['attributes']['field']['suffix']??''" :class="$field['cssClass']"/>


</div>
