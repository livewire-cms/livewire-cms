<div x-data="{
    value: @entangle($attributes->wire('model'))
}"
x-init="
editor = new wangeditor($refs.wangeditor);
editor.config.onchange =   (newHtml)=>{
   value = newHtml
   {{-- console.log(newHtml,this) --}}
};

editor.config.onchangeTimeout = 500; // 修改为 500ms
editor.create();
editor.txt.html('{!!$attributes->get('value','')!!}')

"
{{$attributes->wire('model')}}

wire:ignore
>

<div x-ref="wangeditor">

</div>

</div>
