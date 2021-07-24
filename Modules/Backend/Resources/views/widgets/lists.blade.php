
@if ($widget)
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
    @livewire('backend.livewire.widgets.listapplysetup',['list'=>$list])
    <h2
      class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200"
    >

    </h2>


        @if (isset($listToolbar))
            <div class="grid p-2">
                {!! $listToolbar->vars['controlPanel'] !!}
            </div>
        @endif
        @if (isset($listToolbarSearch))
            <div class="grid p-2 grid-cols-3">
                <div></div>
                <div></div>
                @livewire('backend.livewire.widgets.search',['search'=>$listToolbarSearch->getActiveTerm()])
            </div>
        @endif





    <div class="w-full overflow-hidden rounded-lg shadow-xs" x-data="{{$prefix}}datatables()" x-cloak>
      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="flex items-center  dark:text-gray-400">
                @if (isset($listFilter))


                    @foreach ($listFilter->vars['scopes'] as $k=>$scope)
                        <td class="px-2 max-w-xs" wire:ignore>
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
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">
                            <label
                                class="text-teal-500 inline-flex justify-between items-center hover:bg-gray-200 px-2 py-2 rounded-lg cursor-pointer">
                                <input type="checkbox" class="form-checkbox rowCheckbox{{$prefix}} focus:outline-none focus:shadow-outline" name="{{$record->id}}"
                                        x-on:click="getRowDetail($event, {{$record->id}})">
                            </label>
                        </td>
                        @foreach ($list->vars['columns'] as $k=>$column)
                            <td class="px-4 py-3  {{$column->cssClass}} {{$k}}{{$prefix}}" x-ref="{{$k}}{{$prefix}}">
                                @if ($column->useLivewireComponent)

                                    @livewire($column->livewireComponent,['record'=>$record,'column'=>$column,'prefix'=>$prefix,'list'=>$list],key($prefix.'-'.$record->id))

                                @else
                                    {!!$list->getColumnValue($record,$column)!!}

                                @endif
                                @if ($column->enableEdit)
                                    <x-icon name="pencil-alt" class="cursor-pointer w-5 h-5"  x-on:click="$wire.onQuickFormUpdate({record_id:'{{$record->getKey()}}',edit_fields: JSON.parse($refs['{{$prefix}}{{$k}}'].value)})"/>
                                    <input type="hidden"  x-ref="{{$prefix}}{{$k}}" value="{{json_encode($column->editFields)}}">
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
  @endif
