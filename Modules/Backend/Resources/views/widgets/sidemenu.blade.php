<div>

    @php
       $sideNavManager=\Modules\System\Classes\SideNavManager::instance();
        $context = $sideNavManager->getContext();
        // dd($context);
       $settingItem = $sideNavManager->findSettingItem($context->owner,$context->itemCode);//选中的侧边菜单
       $activeItem = \BackendMenu::getActiveMainMenuItem();
    // dd($settingItem);
    //    $owner
       $listItems =  $sideNavManager->listItems(strtolower($activeItem->owner));//上下文默认是插件名称(顶部选中的的插件) modules.plugin

    //    $mainMenuItems =  \BackendMenu::listMainMenuItems();

        // dd($mainMenuItems);
    @endphp
<!-- component -->
<div class=" flex flex-col flex-auto flex-shrink-0 antialiased bg-gray-50 text-gray-800">
    <div class="fixed flex flex-col top-30 left-0 w-50 bg-gray-900 h-full shadow-lg">
        <div class="flex items-center pl-6 h-20 border-b border-gray-800">
            <img src="" alt="" class="rounded-full h-10 w-10 flex items-center justify-center mr-3 border-2 border-blue-500">
            <div class="ml-1">
                <p class="ml-1 text-md font-medium tracking-wide truncate text-gray-100 font-sans">JED DYLAN LEE</p>
                <div class="badge">
                       <span class="px-2 py-0.5 ml-auto text-xs font-medium tracking-wide text-blue-800 bg-blue-100 rounded-full">Admin</span>
                </div>
            </div>
        </div>
        <div class="overflow-y-auto overflow-x-hidden flex-grow">
        <ul class="flex flex-col py-6 space-y-1">


            @foreach ($listItems as $category=>$listItem)
                <li class="px-5">
                    <div class="flex flex-row items-center h-8">
                        <div class="flex font-semibold text-sm text-gray-300 my-4 font-sans uppercase">{{$category}}</div>
                    </div>
                </li>

                @foreach ($listItem as $item)
                    @php
                        $active = '';
                       if((strtolower($item->owner)==$context->owner)&&(strtolower($item->code)==$context->itemCode)){
                        $active = 'outline-none text-gray-200 bg-gray-700 border-blue-500';
                       }
                    @endphp
                    <li>
                        <a href="{{$item->url}}" class="relative flex flex-row items-center h-11 focus:outline-none hover:bg-gray-700 text-gray-500 hover:text-gray-200 border-l-4 border-transparent hover:border-blue-500 pr-6 {{$active}} ">
                            <span class="inline-flex justify-center items-center ml-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            </span>
                            <span class="ml-2 font-semibold text-sm tracking-wide truncate font-sans">{{__($item->label)}}</span>
                            <span class="px-2 py-0.5 ml-auto text-xs font-medium tracking-wide text-blue-500 bg-blue-100 rounded-full">New</span>
                        </a>
                    </li>
                @endforeach

            @endforeach


        </ul>
        </div>
    </div>
    </div>



</div>


