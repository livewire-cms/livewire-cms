<div>
    @php
        $id = 'id'.\Str::random(20);
        $prefix = $attributes->get('prefix')??'select';
        $options = $attributes->get('options',[]);
        $o = [];
        foreach ($options as $key => $v) {
            $o[]=[
                'id' =>$key,
                'name' =>$v
            ];
        }
    @endphp


<div  wire:ignore x-data="
init_field({
    form:@entangle('form'),
    options: [],
    selected: [],
    value:@entangle($attributes->wire('model')),
    show: false,
    search:'',
    open() { this.show = true },
    close() { this.show = false },
    isOpen() { return this.show === true },
    select(index, event) {
        if(this.selected.length==1){
            this.options[this.selected[0]].selected= false
            this.selected = []
        }
        if (!this.options[index].selected) {

            this.options[index].selected = true;
            this.options[index].element = event.target;

            this.selected.push(index);

        } else {
            this.selected.splice(this.selected.lastIndexOf(index), 1);
            this.options[index].selected = false
        }

        let sele = [];

        for (let index = 0; index < this.selected.length; index++) {

              sele.push(this.options[this.selected[index]].value)
        }
        this.value = sele[0]



    },
    filterOptions(option){

        return option.text.includes(this.search)
    },
    remove(index, option) {
        this.options[option].selected = false;
        this.selected.splice(index, 1);
        this.value = ''
    },
    loadOptions() {

        const options = this.$refs['{{$id}}'].options;

        for (let i = 0; i < options.length; i++) {
            if(options[i].value==this.value){
              this.selected.push(i)
            }
            this.options.push({
                value: options[i].value,
                text: options[i].innerText,
                selected: (options[i].getAttribute('selected') != null || options[i].value==this.value)? true : false
            });
        }


    },

    selectedValues(){
        return this.selected.map((option)=>{
            return this.options[option].value;
        })
    },
    wire:null,
    extend_init(){
        this.wire = @this;
        this.loadOptions();
    }
})
" {{$attributes->wire('model')}} x-init="init()" class="flex flex-col">
    <select  x-ref="{{$id}}" style="display:none">
        @foreach ($o as $k1=>$v1)
        <option value="{{$v1['id']}}"
                >{{$v1['name']}}</option>
        @endforeach
    </select>

    <form>
    <input x-ref="field" type="hidden" value="{{json_encode($attributes->get('field',[]))}}">

      <input name="values" type="hidden" x-bind:value="selectedValues()">
      <div class="w-full inline-block relative ">
          <div class="w-full flex flex-col  relative">
              <div x-on:click="open" class="w-full  svelte-1l8159u">
                  <div class="my-2 p-1 flex border border-gray-200 bg-white rounded svelte-1l8159u">
                      <div class="flex flex-auto flex-wrap">
                          <template x-for="(option,index) in selected" :key="options[option].value">
                              <div
                                  class="flex  items-center m-1 font-medium py-1 px-2 bg-white rounded-full text-teal-700 bg-green-100 border border-teal-300 ">
                                  <div class="text-xs font-normal leading-none max-w-full flex-initial x-model="
                                      options[option]" x-text="options[option].text"></div>
                                  <div class="flex flex-auto flex-row-reverse">
                                      <div x-on:click="remove(index,option)">
                                          <svg class="fill-current h-6 w-6 " role="button" viewBox="0 0 20 20">
                                              <path d="M14.348,14.849c-0.469,0.469-1.229,0.469-1.697,0L10,11.819l-2.651,3.029c-0.469,0.469-1.229,0.469-1.697,0
                                         c-0.469-0.469-0.469-1.229,0-1.697l2.758-3.15L5.651,6.849c-0.469-0.469-0.469-1.228,0-1.697s1.228-0.469,1.697,0L10,8.183
                                         l2.651-3.031c0.469-0.469,1.228-0.469,1.697,0s0.469,1.229,0,1.697l-2.758,3.152l2.758,3.15
                                         C14.817,13.62,14.817,14.38,14.348,14.849z" />
                                          </svg>

                                      </div>
                                  </div>
                              </div>
                          </template>
                          <div class="flex-1">
                              <input :disabled="trigger_endable_or_disable()" placeholder="Select a option"
                                  class="disabled:bg-gray-200 bg-transparent p-1 px-2 appearance-none outline-none h-full w-full text-gray-800"
                                  {{-- x-bind:value="selectedValues()" --}}
                                  x-model="search"

                              >
                          </div>
                      </div>
                      <div
                          class="text-gray-300 w-8 py-1 pl-2 pr-1 border-l flex  border-gray-200 svelte-1l8159u">

                          <button type="button" x-show="isOpen() === true" x-on:click="open"
                              class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                              <svg version="1.1" class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                  <path d="M17.418,6.109c0.272-0.268,0.709-0.268,0.979,0s0.271,0.701,0,0.969l-7.908,7.83
  c-0.27,0.268-0.707,0.268-0.979,0l-7.908-7.83c-0.27-0.268-0.27-0.701,0-0.969c0.271-0.268,0.709-0.268,0.979,0L10,13.25
  L17.418,6.109z" />
                              </svg>

                          </button>
                          <button type="button" x-show="isOpen() === false" x-on:click="close"
                              class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none">
                              <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                  <path d="M2.582,13.891c-0.272,0.268-0.709,0.268-0.979,0s-0.271-0.701,0-0.969l7.908-7.83
  c0.27-0.268,0.707-0.268,0.979,0l7.908,7.83c0.27,0.268,0.27,0.701,0,0.969c-0.271,0.268-0.709,0.268-0.978,0L10,6.75L2.582,13.891z
  " />
                              </svg>

                          </button>
                      </div>
                  </div>
              </div>
              <div class="w-full px-4" x-show="!trigger_endable_or_disable()">
                  <div x-show.transition.origin.top="isOpen()"
                      class="absolute shadow top-100 bg-white z-40 w-full lef-0 rounded max-h-select overflow-y-auto svelte-5uyqqj"
                      x-on:click.away="close">
                      <div class="flex flex-col w-full">
                          <template x-for="(option,index) in options" :key="option">
                              <div x-show="filterOptions(option)">
                                  <div class="cursor-pointer w-full border-gray-100 rounded-t border-b hover:bg-teal-100"
                                      @click="select(index,$event)">
                                      <div x-bind:class="option.selected ? 'bg-green-400' : ''"
                                          class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative">
                                          <div class="w-full items-center flex">
                                              <div class="mx-2 leading-6" x-model="option" x-text="option.text"></div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </template>
                      </div>
                  </div>
              </div>
          </div>


      </form>
      </div>

      @push('scripts')
      <script>
        function {{$prefix}}sdropdown() {
            return {
                options: [],
                selected: [],
                value:@entangle($attributes->wire('model')),
                show: false,
                search:'',
                open() { this.show = true },
                close() { this.show = false },
                isOpen() { return this.show === true },
                select(index, event) {
                    if(this.selected.length==1){
                        this.options[this.selected[0]].selected= false
                        this.selected = []
                    }
                    if (!this.options[index].selected) {

                        this.options[index].selected = true;
                        this.options[index].element = event.target;

                        this.selected.push(index);

                    } else {
                        this.selected.splice(this.selected.lastIndexOf(index), 1);
                        this.options[index].selected = false
                    }

                    let sele = [];

                    for (let index = 0; index < this.selected.length; index++) {

                          sele.push(this.options[this.selected[index]].value)
                    }
                    this.value = sele[0]

                  //   @this.$set('value',JSON.parse(JSON.stringify(sele)))

                },
                filterOptions(option){

                    return option.text.includes(this.search)
                },
                remove(index, option) {
                    this.options[option].selected = false;
                    this.selected.splice(index, 1);

                    // let sele = [];

                    //   for (let index = 0; index < this.selected.length; index++) {

                    //       sele.push(this.options[this.selected[index]].value)
                    //   }
                      this.value = ''

                  //     @this.$set('value',JSON.parse(JSON.stringify(sele)))
                },
                loadOptions() {

                    const options = this.$refs['{{$id}}'].options;
                    // this.value = this.value.map(function (v) {
                    //     retutn v+''
                    // })
                    for (let i = 0; i < options.length; i++) {
                        if(options[i].value==this.value){
                          this.selected.push(i)
                        }
                        this.options.push({
                            value: options[i].value,
                            text: options[i].innerText,
                            selected: (options[i].getAttribute('selected') != null || options[i].value==this.value)? true : false
                        });
                    }


                },

                selectedValues(){
                    return this.selected.map((option)=>{
                        return this.options[option].value;
                    })
                }
            }
        }
    </script>
      @endpush




    </div>



</div>


