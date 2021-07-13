

    @php
       $activeItem = \BackendMenu::getActiveMainMenuItem();
       $mainMenuItems =  \BackendMenu::listMainMenuItems();

        // dd($mainMenuItems);
    @endphp


<div class="flex">

    @foreach ($mainMenuItems as $mainMenuItem)
        @php
            $isActive = BackendMenu::isMainMenuItemActive($mainMenuItem);

        @endphp
        <div class="space-x-8 sm:-my-px sm:ml-10 sm:flex ">
            <x-jet-nav-link href="{{$mainMenuItem->url}}" class="dark:text-gray-400" :active="$isActive">
                {{ __($mainMenuItem->label) }}
            </x-jet-nav-link>
        </div>
    @endforeach

</div>
