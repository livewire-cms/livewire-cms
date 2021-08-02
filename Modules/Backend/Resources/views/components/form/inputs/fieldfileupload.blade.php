
@props(['field','form'])

@php
    // dd($field);
@endphp
<div >

    @if ($field['vars']['displayMode']=='image-single')

    <input

    id="{{$field['id']}}"
    wire:model="{{$field['modelName']}}"
    type="file">

        @foreach (\Arr::get($form['fileList'],$field['modelNameNotFirst'],[]) as $v)

         <img src="{{$v['path']}}" style="{{$field['vars']['cssDimensions']}}">
         <button wire:click.stop.prevent="onRemoveAttachment('{{$field['modelName']}}','{{$v['id']}}')">删除</button>

        @endforeach


    @elseif ($field['vars']['displayMode']=='image-multi')
    <input

    id="{{$field['id']}}"
    wire:model="{{$field['modelName']}}"
    type="file" multiple="multiple">
        @foreach (\Arr::get($form['fileList'],$field['modelNameNotFirst'],[]) as $v)

         <img src="{{$v['path']}}" style="{{$field['vars']['cssDimensions']}}">

         <button wire:click.prevent.stop="onRemoveAttachment('{{$field['modelName']}}','{{$v['id']}}')">删除</button>

        @endforeach


    @endif

    <div wire:loading wire:target="{{$field['modelName']}}">Uploading...</div>

</div>
