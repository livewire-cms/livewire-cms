
@php
    $config =$attributes->get('config',['hello'=>'world']);
    $prefix = $attributes->get('prefix')??'random'.\Str::random(10);

@endphp
<div >
    <div x-data="quillEditor({})"
    x-init="init()"
    {{$attributes->wire('model')}}
    wire:ignore
    >

        <div x-ref="quilleditor" >

        </div>

    </div>
</div>

@push('scripts')
    <script>

function quillEditor(data) {
    return {
        instance: null,
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
@endpush
