

<div>



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








  <div class="antialiased sans-serif bg-gray-200 h-screen">



	<div class="container mx-auto py-6 px-4" x-data="{{$prefix}}datatables()" x-cloak>
		<h1 class="text-3xl py-4 border-b mb-10">Datatable</h1>


		<div class="mb-4 flex justify-end items-center">

            @if (isset($listToolbar))
                {!! $listToolbar->vars['controlPanel'] !!}
            @endif
            @if (isset($listToolbarSearch))
                @livewire('backend.livewire.widgets.search',['search'=>$listToolbarSearch->getActiveTerm()])
            @endif

			<div>
				<div class="shadow rounded-lg flex">
					<div class="relative">
						<button @click.prevent="open = !open"
							class="rounded-lg inline-flex items-center bg-white hover:text-blue-500 focus:outline-none focus:shadow-outline text-gray-500 font-semibold py-2 px-2 md:px-4">
							<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 md:hidden" viewBox="0 0 24 24"
								stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
								stroke-linejoin="round">
								<rect x="0" y="0" width="24" height="24" stroke="none"></rect>
								<path
									d="M5.5 5h13a1 1 0 0 1 0.5 1.5L14 12L14 19L10 16L10 12L5 6.5a1 1 0 0 1 0.5 -1.5" />
							</svg>
							<span class="hidden md:block">Display</span>
							<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1" width="24" height="24"
								viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
								stroke-linecap="round" stroke-linejoin="round">
								<rect x="0" y="0" width="24" height="24" stroke="none"></rect>
								<polyline points="6 9 12 15 18 9" />
							</svg>
						</button>

						<div x-show="open" @click.away="open = false"
							class="z-40 absolute top-0 right-0 w-40 bg-white rounded-lg shadow-lg mt-12 -mr-1 block py-1 overflow-hidden">
                            @foreach ($list->vars['columns'] as $fieH=>$columnH)
                                <label
                                class="flex justify-start items-center text-truncate hover:bg-gray-100 px-4 py-2">
                                <div class="text-teal-600 mr-3">
                                    <input type="checkbox" class="form-checkbox focus:outline-none focus:shadow-outline" checked @click="toggleColumn('{{$fieH}}')">
                                </div>
                                <div class="select-none text-gray-700" >{{$columnH->label}}</div>
                                </label>
                            @endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="mb-4 flex  items-center">

            @if (isset($listFilter))

            @foreach ($listFilter->vars['scopes'] as $k=>$scope)
                @if ($scope->type=='group')

                <?= e(trans($scope->label)) ?>
                @livewire('backend.livewire.widgets.filter.select',['scopeName'=>$scope->scopeName,'options'=>$scope->options,'value'=>$scope->value,'prefix'=>$prefix])

                @elseif ($scope->type=='text')
                <?= e(trans($scope->label)) ?>

                    @livewire('backend.livewire.widgets.filter.input',['scopeName'=>$scope->scopeName,'value'=>$scope->value,'prefix'=>$prefix])
                @else

                     <?= e(trans($scope->label)) ?>
                    @livewire('backend.livewire.widgets.filter.input',['scopeName'=>$scope->scopeName,'value'=>$scope->value,'prefix'=>$prefix])
                @endif


            @endforeach
        @endif

        </div>

		<div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative"
			style="height: 405px;">
			<table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
				<thead>
					<tr class="text-left">
						<th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100">
							<label
								class="text-teal-500 inline-flex justify-between items-center hover:bg-gray-200 px-2 py-2 rounded-lg cursor-pointer">
								<input wire:key="{{$id}}" type="checkbox" class="form-checkbox focus:outline-none focus:shadow-outline" x-on:click="selectAllCheckbox($event);">
							</label>
						</th>

                        @foreach ($list->vars['columns'] as $fie=>$column)

                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs {{$fie}}{{$prefix}}" x-ref="{{$fie.$prefix}}" >

                                <?= e(trans($column->label)) ?>

                            </th>
                        @endforeach
                        <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-xs">
                            操作
                        </th>
					</tr>
				</thead>
				<tbody>
                    @foreach ($list->vars['records'] as $record)

                    <tr class="border-dashed border-t border-gray-200">
                        {{-- <th scope="row">1</th> --}}
                        <td class="border-dashed border-t border-gray-200 px-3">
                            <label
                                class="text-teal-500 inline-flex justify-between items-center hover:bg-gray-200 px-2 py-2 rounded-lg cursor-pointer">
                                <input type="checkbox" class="form-checkbox rowCheckbox{{$prefix}} focus:outline-none focus:shadow-outline" name="{{$record->id}}"
                                        x-on:click="getRowDetail($event, {{$record->id}})">
                            </label>
                        </td>
                        @foreach ($list->vars['columns'] as $k=>$column)
                         <td class="border-dashed border-t border-gray-200 {{$k}}{{$prefix}}" x-ref="{{$k}}{{$prefix}}">
                            <span class="text-gray-700 px-6 py-3 flex items-center">
                             {!!$list->getColumnValue($record,$column)!!}
                            </span>
                        </td>
                         @endforeach
                         <td class="border-dashed border-t border-gray-200">
                             <span class="text-gray-700 px-6 py-3 flex items-center">
                                 {{-- {{dd($list)}} --}}
                                <a class="py-2 px-4 border rounded-md border-blue-600 text-blue-600 cursor-pointer uppercase text-sm font-bold hover:bg-blue-500 hover:text-white hover:shadow" href="<?= $list->getRecordUrl($record) ?>">编辑</a>

                             </span>
                         </td>
                    </tr>
                    @endforeach
				</tbody>
			</table>
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

</div>

</div>


