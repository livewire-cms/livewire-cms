
@php
    $config =$attributes->get('config',['hello'=>'world']);
@endphp
<div x-data="{
    value: @entangle($attributes->wire('model'))
}"
x-init='
quilleditor = new Quill($refs.quilleditor,@json($config));

quilleditor.on("text-change", function(delta, oldDelta, source) {
    value = quilleditor.getContents().ops

  });
quilleditor.setContents(@json($attributes->get('value',[])))

'
{{$attributes->wire('model')}}
wire:ignore
>

<div x-ref="quilleditor">

</div>

</div>
