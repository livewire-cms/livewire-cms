

<div>

    <input
    type="text"
    name="{{$name}}"
    id="{{$name_id}}"
    wire:model="value"
    placeholder="{{$placeholder}}"
    class="form-control"
    autocomplete="off"

    {{$maxlength}}

    {{$attributes}} />

    {{$value}}

</div>


