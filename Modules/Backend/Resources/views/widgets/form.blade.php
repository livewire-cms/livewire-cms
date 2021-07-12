
@php

//todo if $field['type']=='widget' todo form makeFormFieldWidget
// dd($fields,$tabs,$secondTabs);
@endphp

<div class="container grid p-6 mx-auto">

<form wire:submit.prevent="save" >
    <div x-data="{tab:'{{key($tabs)}}',secondTab:'{{key($secondTabs)}}'}"  class="">
        <x-back-form-fields :fields="$fields" :form="$form"></x-back-form-fields>
        <x-back-form-tabs :tabs="$tabs" tab_name="tab" :form="$form"></x-back-form-tabs>
        <x-back-form-tabs :tabs="$secondTabs" tab_name="secondTab" :form="$form"></x-back-form-tabs>

        @foreach ($loadRelations as $loadRelation)
        <button wire:click.prevent="onRelationButtonCreate('{{$loadRelation}}')">
            创建{{$loadRelation}}
        </button>
        @endforeach





            <div class=" my-4">
                <button
                class="py-2 px-4 border rounded-md border-blue-600 text-blue-600 cursor-pointer uppercase text-sm font-bold hover:bg-blue-500 hover:text-white hover:shadow"
                >保存</button>
            </div>







    </div>
</form>







</div>


