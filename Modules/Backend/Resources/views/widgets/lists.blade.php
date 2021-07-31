
@if ($widget)
@php

    // dd($widget);
    $list= $widget->{$prefix}->prepareVars();

    if(isset($widget->{$prefix.'ToolbarSearch'})){
        $listToolbarSearch = $widget->{$prefix.'ToolbarSearch'}->prepareVars();
    }
    if(isset($widget->{$prefix.'Toolbar'})){
        $listToolbar = $widget->{$prefix.'Toolbar'}->prepareVars();
        // dd($listToolbar);
    }
    if(isset($widget->{$prefix.'Filter'})){
        $listFilter = $widget->{$prefix.'Filter'}->prepareVars();
    }
    $id = \Str::random(10);



@endphp

<div class="container grid px-6 mx-auto">
    @livewire('backend.livewire.widgets.listapplysetup',['list'=>$list])
    <h2
      class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
    >

    </h2>








    <div class="w-full overflow-hidden rounded-lg shadow-xs" x-data="{{$prefix}}datatables()" x-cloak>

        @if (isset($listToolbar))
            <div class="grid p-2 grid-cols-3">
                <div>
                    @if (isset($listToolbar))
                        {!! $listToolbar->vars['controlPanel'] !!}
                    @endif
                </div>
                <div></div>
                @isset($listToolbarSearch)
                    @livewire('backend.livewire.widgets.search',['search'=>$listToolbarSearch->getActiveTerm()])

                @endisset
            </div>
        @endif
        @if (isset($listFilter))
        <div class="p-2 dark:text-gray-400">
            <h1>Filter</h1>
        </div>
        <div class="flex p-2">
                @foreach ($listFilter->vars['scopes'] as $k=>$scope)
                    <div class="flex justify-center items-center" wire:ignore>
                        <div class="p-2 dark:text-gray-400">
                            {{__($scope->label)}}:
                        </div>
                        @if ($scope->type=='group')
                            @livewire('backend.livewire.widgets.filter.select',['scopeName'=>$scope->scopeName,'options'=>$scope->options,'value'=>$scope->value,'prefix'=>$prefix])
                        @elseif ($scope->type=='text')
                            @livewire('backend.livewire.widgets.filter.input',['scopeName'=>$scope->scopeName,'value'=>$scope->value,'prefix'=>$prefix])
                        @elseif (in_array($scope->type,['toggle','switch']))
                            @livewire('backend.livewire.widgets.filter.toggle',['scopeName'=>$scope->scopeName,'value'=>$scope->value,'prefix'=>$prefix])
                        @else
                            @livewire('backend.livewire.widgets.filter.input',['scopeName'=>$scope->scopeName,'value'=>$scope->value,'prefix'=>$prefix])
                        @endif
                    </div>
                @endforeach
        </div>
        @endif

      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="flex items-center  dark:text-gray-400">

            </tr>
            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-4 py-3">

                </th>

                @foreach ($list->vars['columns'] as $fie=>$column)
                    @if($column->sortable)
                    <th wire:click="onSort({sortColumn:'{{$column->columnName}}'})" class="px-4 py-3 cursor-pointer {{$fie}}{{$prefix}}" x-ref="{{$fie.$prefix}}" >

                        {{ __($column->label) }}

                        @if ($list->getSortColumn()==$column->columnName)

                            @if ($list->getSortDirection()=='desc')
                                <span> &uarr;</span>
                            @else
                                <span> &darr;</span>
                            @endif
                        @else
                            <span> &uarr;&darr;</span>
                        @endif
                    </th>
                    @else
                    <th  class="px-4 py-3  {{$column->cssClass}} {{$fie}}{{$prefix}}" x-ref="{{$fie.$prefix}}" >
                        {{ __($column->label) }}
                    </th>
                    @endif

                @endforeach
                <th class="px-4 py-3">


                    <span class="cursor-pointer float-right" wire:click="$emitTo('backend.livewire.widgets.listapplysetup','onApplySetup_{{$prefix}}')">{{__('Setup')}}</span>

                </th>
            </tr>
          </thead>
          <tbody
            class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800"
          >

                @foreach ($list->vars['records'] as $record)
                    <tr class="text-gray-700 dark:text-gray-400" wire:key="{{$record->getKey()}}{{$prefix}}tr">
                        <td class="px-4 py-3">

                        </td>
                        @foreach ($list->vars['columns'] as $k=>$column)
                            <td class="px-4 py-3  {{$column->cssClass}} {{$k}}{{$prefix}}" x-ref="{{$k}}{{$prefix}}">
                                @if ($column->useLivewireComponent)



                                @else
                                    {!!$list->getColumnValue($record,$column)!!}

                                @endif
                                @if ($column->enableEdit)
                                    <x-icon name="pencil-alt" class="cursor-pointer w-5 h-5"  x-on:click="$wire.onQuickFormUpdate({record_id:'{{$record->getKey()}}',edit_fields: JSON.parse($refs['{{$prefix}}{{$record->getKey()}}{{$k}}'].value)})"/>
                                    <input type="hidden"  x-ref="{{$prefix}}{{$record->getKey()}}{{$k}}" value="{{json_encode($column->editFields)}}">
                                @endif
                            </td>
                         @endforeach
                         <td class="flex border-dashed  border-gray-200">

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
        @if ($list->showPagination)
            {!! $list->vars['records']->render('vendor.pagination.tailwind-list') !!}

        @endif

      </div>
    </div>
  </div>
  @push('scripts')
  <script>
      function {{$prefix}}datatables() {
          return {
              selectedRows: @json($selectedRows),
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
              itemSelected(item) {
                console.log(32131)

                return this.selectedRows.indexOf(item) > -1;

                return false
            },
            toggleItem(item) {

              if (this.itemSelected(item)) {
                this.selectedRows = this.selectedRows.filter(i => i != item);
              }
              else {
                this.selectedRows.push(item);
              }

              console.log(JSON.stringify(this.selectedRows))

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
                          this.selectedRows.push(column.name)
                      });
                  } else {
                      columns.forEach(column => {
                          column.checked = false
                      });
                      this.selectedRows = [];
                  }
                    console.log(JSON.stringify(this.selectedRows))

              }
          }
      }
  </script>
  @endpush
  @endif
