
@php
    $options = $attributes->get('options',[]);
        $o = [];
        foreach ($options as $key => $v) {
            $o[]=[
                'id' =>$key,
                'name' =>$v
            ];
        }
    $inline = $attributes->get('inline',false);
@endphp
<div class="flex">

    <div
         x-data="
          {
            items: [],
            selectedItems: @entangle($attributes->wire('model')),
            buttonLabel() {
              if (this.selectedItems.length > 0) {
                return this.selectedItems.join(', ');
              }
              else {
                return 'Please select...';
              }
            },
            itemSelected(item) {
              return this.selectedItems.indexOf(item) > -1;
            },
            toggleItem(item) {
              if (this.itemSelected(item)) {
                this.selectedItems = this.selectedItems.filter(i => i != item);
              }
              else {
                this.selectedItems.push(item);
              }

            },
            showCheckboxes: false
          }
         "
         x-init="items = ['Banana', 'Apple', 'Pear', 'Orange']"
         class="relative">


         @foreach ($o as $v1)
            <label class="{{$inline?'':'block'}}">
                <input type="checkbox" x-bind:checked="itemSelected('{{$v1['id']}}')" x-on:change="toggleItem('{{$v1['id']}}')">
                <span >
                    @if (is_array($v1['name']))
                    {{$v1['name'][0]}}
                    @else
                    {{$v1['name']}}
                    @endif

                </span>
            </label>
         @endforeach
        {{-- <template x-for="item in items">
          <label class="block">
            <input type="checkbox" x-bind:checked="itemSelected(item)" x-on:change="toggleItem(item)">
            <span x-text="item"></span>
          </label>
        </template> --}}

    </div>

  </div>
