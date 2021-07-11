

<div>

    <div>
        <select wire:model="value" multiple="multiple">
            @foreach ($options as $k1=>$value)
                <option value="{{$k1}}">{{$value}}</option>
            @endforeach
        </select>
    </div>


</div>


