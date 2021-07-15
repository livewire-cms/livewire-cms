


    <input
    x-data
    x-ref="input1"
    x-init="flatpickr($refs.input1,{
        enableTime: true,
        dateFormat: 'Y-m-d H:i',
    })"
    type="text"

    {{$attributes->wire('model')}}


>



