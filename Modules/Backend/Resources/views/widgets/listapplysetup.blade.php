<div wire:ignore>

        <x-jet-dialog-modal wire:model="modal">
            <x-slot name="title">
                {{__('Setup')}}
            </x-slot>
            <x-slot name="content">
                <div class="container  p-6 mx-auto">
                    <div class="flex flex-wrap">
                        <div class="w-1/4"></div>

                        <div class="w-1/2">
                            <label class="block font-medium tracking-tight dark:text-gray-400">

                                    {{__('Display Column')}}

                            </label>
                            <x-select.multiple wire:model.lazy="visible_columns" :options="$options"></x-select.multiple>
                        </div>
                        <div class="w-1/4"></div>
                        <div class="w-1/4"></div>

                        <div class="w-1/2">
                            {{__('Display Per Page')}}

                            <select wire:model="records_per_page"  class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                <option value="2">2</option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>


                    </div>

                </div>
            </x-slot>

            <x-slot name="footer">
                <div wire:loading>
                    Loading...
                </div>
                <x-jet-secondary-button wire:click="$toggle('modal')" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="save()" wire:loading.attr="disabled">
                    {{ __('Save') }}
                </x-jet-danger-button>
            </x-slot>


        </x-jet-dialog-modal>

</div>
