
@php
    $config =$attributes->get('config',['hello'=>'world']);
    $prefix = $attributes->get('prefix')??'random'.\Str::random(10);
    $prefix = md5($prefix);
@endphp
<div >
    <div x-data="
    {
        instance: null,
        value: @entangle($attributes->wire('model')),
        tmps:[],
        init() {
            this.$nextTick(() => {
                @php
                     $config = json_encode($config);

                     $value = json_encode($attributes->get('value',[]));

               @endphp

                this.instance = new Quill(this.$refs.quilleditor, JSON.parse(this.$refs.config.value));
                this.instance.setContents(JSON.parse(this.$refs.value.value));
                this.instance.on('text-change', () => {
                    this.tmps.push(this.instance.getContents().ops);
                    if(this.tmps.length>3){
                        this.tmps.shift()
                    }
                    this.startUpdate()
                })
            })
        },
        startUpdate(){

            setTimeout(() => {
                if(this.tmps.length>0){
                    console.log('正在更新',this.tmps.length)



                    this.value = this.tmps[this.tmps.length-1]
                    this.tmps.shift()

                }else{
                    console.log('取消更新',this.tmps.length)
                }
            }, 0);
        }


    }
    "
    x-init="init()"
    {{$attributes->wire('model')}}

    class="dark:bg-gray-800"
    wire:ignore
    >

        <div x-ref="quilleditor" class="dark:text-gray-400">

        </div>
        <input type="hidden" x-ref="value" value="{{$value}}">
        <input type="hidden" x-ref="config" value="{{$config}}">

    </div>
</div>

{{-- @push('scripts') --}}
    <script>

function quillEditor{{$prefix}}(data) {
    return {
        instance: null,
        // value: @entangle($attributes->wire('model')),
        value: @entangle($attributes->wire('model')),
        tmps:[],
        init() {
            this.$nextTick(() => {
                this.instance = new Quill(this.$refs.quilleditor, @json($config));

                this.instance.setContents(@json($attributes->get('value',[])))
                this.instance.on('text-change', () => {
                    this.tmps.push(this.instance.getContents().ops);
                    if(this.tmps.length>3){
                        this.tmps.shift()
                    }
                    this.startUpdate()
                    // this.value = this.instance.getContents().ops
                })
            })
        },
        startUpdate(){

            setTimeout(() => {
                if(this.tmps.length>0){
                    console.log('正在更新',this.tmps.length)



                    this.value = this.tmps[this.tmps.length-1]
                    this.tmps.shift()

                }else{
                    console.log('取消更新',this.tmps.length)
                }
            }, 0);
        },

        ...data
    }
}


    </script>
{{-- @endpush --}}
