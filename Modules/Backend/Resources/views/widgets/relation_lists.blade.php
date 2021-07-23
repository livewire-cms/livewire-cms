


@php
    $pre = 'relation'.ucfirst(\Str::camel($prefix));
    if(isset($widget->{$pre.'ViewList'})){
        $list= $widget->{$pre.'ViewList'}->prepareVars();

    }
    // dd($pre);

    // dd($widget);

    if(isset( $widget->{$pre.'ToolbarSearch'})){
        $listToolbarSearch = $widget->{$pre.'ToolbarSearch'}->prepareVars();

    }

    if(isset($widget->{$pre.'Filter'})){
        $listFilter = $widget->{$pre.'Filter'}->prepareVars();
    }
    $id = \Str::random(10);
    // dd($listToolbarSearch->vars);


@endphp

<div class="container grid px-6 mx-auto">
    @if (isset($listToolbar))
        <div class="grid p-2">
            {!! $listToolbar->vars['controlPanel'] !!}
        </div>
    @endif
    @if (isset($listToolbarSearch))
        <div class="grid grid-cols-3">
            <div></div>
            <div></div>
            @livewire('backend.livewire.widgets.search',['search'=>$listToolbarSearch->getActiveTerm()])
        </div>
    @endif
    @isset($list)

    <div class="w-full overflow-hidden rounded-lg shadow-xs" x-data="
    {
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
    " x-cloak>
      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr>
                @if (isset($listFilter))
                    @foreach ($listFilter->vars['scopes'] as $k=>$scope)
                        <td class="px-2">
                            @if ($scope->type=='group')
                                {{__($scope->label)}}
                            @livewire('backend.livewire.widgets.filter.select',['scopeName'=>$scope->scopeName,'options'=>$scope->options,'value'=>$scope->value,'prefix'=>$prefix])

                            @elseif ($scope->type=='text')
                                {{__($scope->label)}}
                                @livewire('backend.livewire.widgets.filter.input',['scopeName'=>$scope->scopeName,'value'=>$scope->value,'prefix'=>$prefix])
                            @else
                                {{__($scope->label)}}
                                @livewire('backend.livewire.widgets.filter.input',['scopeName'=>$scope->scopeName,'value'=>$scope->value,'prefix'=>$prefix])
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
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                @foreach ($list->vars['records'] as $record)
                    <tr class="text-gray-700 dark:text-gray-400" wire:click.stop="$emitTo('backend.livewire.widgets.relation_form','onRelationClickViewList',{_relation_field:'{{$prefix}}',modelId:'{{$modelId}}',manage_id:'{{$record->getKey()}}',context:'{{$context}}'})">
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
        @if(method_exists($list->vars['records'],'render'))
        {!! $list->vars['records']->render('vendor.pagination.tailwind-list') !!}

        @endif
      </div>
    </div>
    @endisset

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
