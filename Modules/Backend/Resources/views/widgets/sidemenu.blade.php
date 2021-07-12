<div>

    @php
       $sideNavManager=\Modules\System\Classes\SideNavManager::instance();
        $context = $sideNavManager->getContext();
        // dd($context);
       $settingItem = $sideNavManager->findSettingItem($context->owner,$context->itemCode);//选中的侧边菜单
       $activeItem = \BackendMenu::getActiveMainMenuItem();

    // dd($settingItem);
    //    $owner
    if(!$activeItem){
        $listItems = [];
    }else{
        $listItems =  $sideNavManager->listItems(strtolower($activeItem->owner));//上下文默认是插件名称(顶部选中的的插件) modules.plugin

    }

    //    $mainMenuItems =  \BackendMenu::listMainMenuItems();

        // dd($mainMenuItems);
    @endphp

<ul class="mt-6">

            @foreach ($listItems as $category=>$listItem)
            <li class="relative px-6 py-3" x-data="{open:false}">
                <button
                  class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                  @click="open=!open"
                  aria-haspopup="true"
                >
                  <span class="inline-flex items-center">
                    <svg
                      class="w-5 h-5"
                      aria-hidden="true"
                      fill="none"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                      ></path>
                    </svg>
                    <span class="ml-4">{{$category}}</span>
                  </span>
                  <svg
                    class="w-4 h-4"
                    aria-hidden="true"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </button>
                <template x-if="open">
                  <ul
                    x-transition:enter="transition-all ease-in-out duration-300"
                    x-transition:enter-start="opacity-25 max-h-0"
                    x-transition:enter-end="opacity-100 max-h-xl"
                    x-transition:leave="transition-all ease-in-out duration-300"
                    x-transition:leave-start="opacity-100 max-h-xl"
                    x-transition:leave-end="opacity-0 max-h-0"
                    class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                    aria-label="submenu"
                  >
                  @foreach ($listItem as $item)
                    @php
                        $active = '';
                       if((strtolower($item->owner)==$context->owner)&&(strtolower($item->code)==$context->itemCode)){
                        $active = 'outline-none text-gray-200 bg-gray-700 border-blue-500';
                       }
                    @endphp
                    <li
                    class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                  >
                    <a class="w-full" href="{{$item->url}}">{{__($item->label)}}</a>
                  </li>
                    @endforeach
                  </ul>
                </template>
              </li>



            @endforeach

</ul>





