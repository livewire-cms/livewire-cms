
@php
    if(!isset($widget)){
        $widget = null;
    }
@endphp

<div class="container grid p-6 mx-auto" >
    <form  >
        <div x-data="{tab:'{{key($tabs)}}',secondTab:'{{key($secondTabs)}}'}"  class="">
            <x-back-form-fields :fields="$fields" :form="$form" :widget="$widget"></x-back-form-fields>
            <x-back-form-tabs :tabs="$tabs" tab_name="tab" :form="$form" :widget="$widget"></x-back-form-tabs>
            <x-back-form-tabs :tabs="$secondTabs" tab_name="secondTab" :form="$form" :widget="$widget"></x-back-form-tabs>
            <div class="flex">
                <x-jet-action-message class="mr-3" on="saved">
                    {{ __('Saved.') }}
                </x-jet-action-message>
                @if ($context=='create')
                    <div class="p-2">
                        <button
                        class="py-2 px-4 border rounded-md border-blue-600 text-blue-600 cursor-pointer uppercase text-sm font-bold hover:bg-blue-500 hover:text-white hover:shadow"
                        wire:click.prevent="save({redirect:1})">创建 </button>
                    </div>
                @elseif ($context=='update')
                    <div class="p-2">
                        <button
                        class="py-2 px-4 border rounded-md border-blue-600 text-blue-600 cursor-pointer uppercase text-sm font-bold hover:bg-blue-500 hover:text-white hover:shadow"
                        wire:click.prevent="save({redirect:0})">保存 </button>
                    </div>
                    <div class="p-2">
                        <button
                        class="py-2 px-4 border rounded-md border-blue-600 text-blue-600 cursor-pointer uppercase text-sm font-bold hover:bg-blue-500 hover:text-white hover:shadow"
                        wire:click.prevent="save({redirect:1})">保存和关闭 </button>
                    </div>
                @endif

            </div>

        </div>
    </form>
</div>
