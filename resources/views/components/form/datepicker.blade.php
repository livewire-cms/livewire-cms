


    <input
    x-data
    x-ref="input1"
    x-init="flatpickr($refs.input1,{
        dateFormat: 'Y-m-d',
    })"
    type="text"

    {{$attributes->wire('model')}}

>



