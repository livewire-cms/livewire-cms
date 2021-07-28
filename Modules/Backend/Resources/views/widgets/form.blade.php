
@php
    if(!isset($widget)){
        $widget = null;
    }
@endphp


<div class="container grid p-6 mx-auto">

    <form  >

        <div x-data="{tab:'{{key($tabs)}}',secondTab:'{{key($secondTabs)}}'}"  class="">
            {!! $formHeader !!}
            <x-back-form-fields :fields="$fields" :form="$form" :widget="$widget"></x-back-form-fields>
            <x-back-form-tabs :tabs="$tabs" tab_name="tab" :form="$form" :widget="$widget"></x-back-form-tabs>
            <x-back-form-tabs :tabs="$secondTabs" tab_name="secondTab" :form="$form" :widget="$widget"></x-back-form-tabs>
            {!! $formFooter !!}
        </div>
    </form>


</div>
