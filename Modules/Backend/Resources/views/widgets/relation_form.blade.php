
@php



//todo if $field['type']=='widget' todo form makeFormFieldWidget
// dd($fields,$tabs,$secondTabs);
@endphp
<div>

@if (!empty($fields)||!empty($tabs)||!empty($secondTabs))

    <form wire:submit.prevent="save" >
        <div x-data="{tab:'{{key($tabs)}}',secondTab:'{{key($secondTabs)}}'}"  class="flex flex-wrap justify-between ">
            <x-back-form-fields :fields="$fields"></x-back-form-fields>
            <x-back-form-tabs :tabs="$tabs" tab_name="tab"></x-back-form-tabs>
            <x-back-form-tabs :tabs="$secondTabs" tab_name="secondTab"></x-back-form-tabs>




                <div class=" my-4">
                    <button
                    class="py-2 px-4 border rounded-md border-blue-600 text-blue-600 cursor-pointer uppercase text-sm font-bold hover:bg-blue-500 hover:text-white hover:shadow"
                    >保存</button>
                </div>







        </div>
    </form>










@endif

</div>
