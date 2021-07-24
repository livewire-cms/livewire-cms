
@php
    if(!isset($widget)){
        $widget = null;
        // dd($form);
    }
@endphp



<div class="p-4  block" >

    @foreach ($allFields as $k=>$allfield)
        <div x-data="{tab:'{{key($allfield['tabs']??[])}}',secondTab:'{{key($allfield['secondTabs']??[])}}'}">

            <div class="float-right">
                <button
                class="py-2 px-4 border rounded-md border-blue-600 text-blue-600 cursor-pointer uppercase text-sm font-bold hover:bg-blue-500 hover:text-white hover:shadow"
                wire:click.prevent="onRemoveItem('{{$k}}')"  >
                <span aria-hidden="true">&times;</span>

                </button>
            </div>
            <x-back-form-fields :fields="$allfield['fields']??[]" :form="$form" :relation_field="$relation_field" :widget="$widget"></x-back-form-fields>
            <x-back-form-tabs :tabs="$allfield['tabs']??[]" tab_name="tab" :form="$form" :relation_field="$relation_field" :widget="$widget"></x-back-form-tabs>
            <x-back-form-tabs :tabs="$allfield['secondTabs']??[]" tab_name="secondTab" :relation_field="$relation_field" :form="$form" :widget="$widget"></x-back-form-tabs>
            <x-jet-section-border />
         </div>


    @endforeach

    <div class="flex justify-center items-center">
        <button
        class="py-2 px-4 border rounded-md border-blue-600 text-blue-600 cursor-pointer uppercase text-sm font-bold hover:bg-blue-500 hover:text-white hover:shadow"
        wire:click.prevent="onAddItem"  >
        @isset($field['vars']['prompt'])
            {{__($field['vars']['prompt'])}}
        @else
            {{dd($field)}}
        @endisset
         </button>
    </div>

</div>
