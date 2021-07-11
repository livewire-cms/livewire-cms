
@php
    $prefix = $attributes->get('prefix','id'.\Str::random(20));

@endphp




<div  x-data="{value: @entangle($attributes->wire('model'))}">
    <label id="$prefix" class="inline-flex items-center">
        <input x-model="value" type="checkbox" >
      </label>

</div>