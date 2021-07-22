
@php
    $id = 'id'.\Str::random(20);
    $prefix = $attributes->get('prefix')??'radio';
    $options = $attributes->get('options',[]);
    $o = [];
    foreach ($options as $key => $v) {
        $o[]=[
            'id' =>$key,
            'option' =>is_string($v)? array($v):$v
        ];
    }

@endphp

<div x-data="{{$prefix}}radio()" class="bg-gray-200">
    <div class="flex flex-col  ml-5">

        <div class="flex flex-col">
            @foreach ($o as $ov)

                <label class="inline-flex items-center my-3 " >
                    <input type="radio" x-model="value" class="form-radio h-5 w-5 text-green-600" value="{{$ov['id']}}"><span class="ml-2 text-gray-700">{{$ov['option'][0]}}</span>
                </label>
                @isset($ov['option'][1])
                     <p class="text-xs">{{__($ov['option'][1])}}</p>
                @endisset

            @endforeach


        </div>
    </div>
</div>

@push('scripts')
<script>
  function {{$prefix}}radio() {
      return {
          options: [],
          value:@entangle($attributes->wire('model')),
      }
  }
</script>
@endpush
