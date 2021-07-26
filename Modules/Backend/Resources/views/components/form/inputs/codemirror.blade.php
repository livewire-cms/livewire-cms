
@props(['field','form','widget'])

@php
$config = json_encode($field['config']['config']??[
    ''
]);

@endphp
<div wire:ignore class="h-full">

    <div x-data="{
        height: '500px',
        content:@entangle($field['modelName']),
        codeMirrorEditor:null,
        tmps:[],
        init(){
            this.$nextTick(() => {
                {{-- {

                    mode: 'php',
                    theme: 'material-darker',
                    lineWrapping: true
                 } --}}

            this.codeMirrorEditor = codemirror.fromTextArea(this.$refs.input, JSON.parse(this.$refs.config.value));

            this.codeMirrorEditor.setValue(this.content?this.content:'');
            this.codeMirrorEditor.setSize('100%', this.height);



            this.codeMirrorEditor.on('change', () => {
                 //content = codeMirrorEditor.getValue()
                 this.tmps.push(this.codeMirrorEditor.getValue());
                 if(this.tmps.length>1){
                     this.tmps.shift()
                 }
                 this.startUpdate()
             })
             setTimeout(() =>{
                this.codeMirrorEditor.refresh();
              }, 1);
              setTimeout(() =>{
                this.codeMirrorEditor.refresh();
              }, 1000);
              setTimeout(() =>{
                this.codeMirrorEditor.refresh();
              }, 5000);
            })

        },
        startUpdate(){

            setTimeout(() => {
                if(this.tmps.length>0){
                    console.log('正在更新',this.tmps.length)

                    this.content = this.tmps[this.tmps.length-1]
                    this.tmps.shift()

                }else{
                    console.log('取消更新',this.tmps.length)
                }
            }, 1000);
        }
    }"
    x-init="
        init()
    "
    >

        <div id="{{ $field['id'] }}">
            <textarea

                x-ref="input"
                x-model.debounce.1000ms="content"
                class=" hidden"

            ></textarea>
        </div>

        <input type="hidden"  x-ref="config" value="{{$config}}">



    </div>


</div>
