
@php

    // dd($widget);
    $list= $widget->{$prefix}->prepareVars();
    if(isset($widget->{$prefix.'ToolbarSearch'})){
        $listToolbarSearch = $widget->{$prefix.'ToolbarSearch'}->prepareVars();
    }
    if(isset($widget->{$prefix.'Toolbar'})){
        $listToolbar = $widget->{$prefix.'Toolbar'}->prepareVars();
    }
    if(isset($widget->{$prefix.'Filter'})){
        $listFilter = $widget->{$prefix.'Filter'}->prepareVars();
    }
    $id = \Str::random(10);



@endphp
<div class="container grid px-6 mx-auto">
    <h2
      class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
    >
      Tables
    </h2>
    <!-- CTA -->
    <a
      class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple"
      href="https://github.com/estevanmaito/windmill-dashboard"
    >
      <div class="flex items-center">
        <svg
          class="w-5 h-5 mr-2"
          fill="currentColor"
          viewBox="0 0 20 20"
        >
          <path
            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
          ></path>
        </svg>
        <span>Star this project on GitHub</span>
      </div>
      <span>View more &RightArrow;</span>
    </a>
    <!-- With actions -->
    <h4
      class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300"
    >
      Table with actions
    </h4>
        @if (isset($listToolbar))
            <div class="grid p-2">
                {!! $listToolbar->vars['controlPanel'] !!}
            </div>
        @endif
        @if (isset($listToolbarSearch))
            <div class="grid grid-cols-3">
                <div></div>
                <div></div>
                @livewire('backend.widgets.search',['search'=>$listToolbarSearch->getActiveTerm()])
            </div>
        @endif




    <div class="w-full overflow-hidden rounded-lg shadow-xs" x-data="{{$prefix}}datatables()" x-cloak>
      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr>
                @if (isset($listFilter))


                    @foreach ($listFilter->vars['scopes'] as $k=>$scope)
                        <td class="px-2">
                            @if ($scope->type=='group')
                                {{__($scope->label)}}
                            @livewire('backend.widgets.filter.select',['scopeName'=>$scope->scopeName,'options'=>$scope->options,'value'=>$scope->value,'prefix'=>$prefix])

                            @elseif ($scope->type=='text')
                                {{__($scope->label)}}
                                @livewire('backend.widgets.filter.input',['scopeName'=>$scope->scopeName,'value'=>$scope->value,'prefix'=>$prefix])
                            @else
                                {{__($scope->label)}}
                                @livewire('backend.widgets.filter.input',['scopeName'=>$scope->scopeName,'value'=>$scope->value,'prefix'=>$prefix])
                            @endif
                        </td>
                    @endforeach
            @endif
            </tr>
            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-4 py-3">
                    <label
                        class="text-teal-500 inline-flex justify-between items-center hover:bg-gray-200 px-2 py-2 rounded-lg cursor-pointer">
                        <input wire:key="{{$id}}" type="checkbox" class="form-checkbox focus:outline-none focus:shadow-outline" x-on:click="selectAllCheckbox($event);">
                    </label>
                </th>

                @foreach ($list->vars['columns'] as $fie=>$column)
                    <th class="px-4 py-3 {{$fie}}{{$prefix}}" x-ref="{{$fie.$prefix}}" >
                        {{ __($column->label) }}
                    </th>
                @endforeach
                <th class="px-4 py-3">
                    操作
                </th>
            </tr>
          </thead>
          <tbody
            class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
          >

                @foreach ($list->vars['records'] as $record)
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">
                            <label
                                class="text-teal-500 inline-flex justify-between items-center hover:bg-gray-200 px-2 py-2 rounded-lg cursor-pointer">
                                <input type="checkbox" class="form-checkbox rowCheckbox{{$prefix}} focus:outline-none focus:shadow-outline" name="{{$record->id}}"
                                        x-on:click="getRowDetail($event, {{$record->id}})">
                            </label>
                        </td>
                        @foreach ($list->vars['columns'] as $k=>$column)
                            <td class="px-4 py-3 {{$k}}{{$prefix}}" x-ref="{{$k}}{{$prefix}}">
                                {!!$list->getColumnValue($record,$column)!!}
                            </td>
                         @endforeach
                         <td class="border-dashed border-t border-gray-200">
                             <span class="text-gray-700 px-6 py-3 flex items-center">
                                <a class="py-2 px-4 border rounded-md border-blue-600 text-blue-600 cursor-pointer uppercase text-sm font-bold hover:bg-blue-500 hover:text-white hover:shadow" href="<?= $list->getRecordUrl($record) ?>">编辑</a>
                             </span>
                         </td>
                    </tr>
                @endforeach
          </tbody>
        </table>
      </div>




      <div
        class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
        <span class="flex items-center col-span-3">

        </span>
        <span class="col-span-2"></span>
        <!-- Pagination -->
        {!! $list->vars['records']->render('vendor.pagination.tailwind-list') !!}

      </div>
    </div>
  </div>
  @push('scripts')
  <script>
      function {{$prefix}}datatables() {
          return {
              selectedRows: [],
              open: false,
              toggleColumn(key) {
                  // Note: All td must have the same class name as the headings key!
                  let columns = document.querySelectorAll('.' + key+'{{$prefix}}');
                  if (this.$refs[key+'{{$prefix}}'].classList.contains('hidden') && this.$refs[key+'{{$prefix}}'].classList.contains(key+'{{$prefix}}')) {
                      columns.forEach(column => {
                          column.classList.remove('hidden');
                      });
                  } else {
                      columns.forEach(column => {
                          column.classList.add('hidden');
                      });
                  }
              },

              getRowDetail($event, id) {

                  let rows = this.selectedRows;

                  if (rows.includes(id)) {
                      let index = rows.indexOf(id);
                      rows.splice(index, 1);
                  } else {
                      rows.push(id);
                  }
              },

              selectAllCheckbox($event) {
                  let columns = document.querySelectorAll('.rowCheckbox'+'{{$prefix}}');

                  this.selectedRows = [];

                  if ($event.target.checked == true) {
                      columns.forEach(column => {
                          column.checked = true
                          this.selectedRows.push(parseInt(column.name))
                      });
                  } else {
                      columns.forEach(column => {
                          column.checked = false
                      });
                      this.selectedRows = [];
                  }
              }
          }
      }
  </script>
  @endpush
