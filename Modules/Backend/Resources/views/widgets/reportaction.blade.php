

<div class="">
@if ($widget)

{!! $response !!}

@else
<span wire:click="onAction('onLoadAddPopup',{})"> 设置</span>
@endif

</div>
