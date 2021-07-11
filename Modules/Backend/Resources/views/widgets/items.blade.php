

<div>

    <table class="table">
        <thead>
          <tr>
            <th >#</th>

              @foreach ($widget->vars['columns'] as $column)

                <th scope="col">{{$column->label}}</th>
              @endforeach

          </tr>
        </thead>
        <tbody>
            @foreach ($widget->vars['records'] as $record)

            <tr>
                <th scope="row">1</th>
                @foreach ($widget->vars['columns'] as $k=>$column)
                <th >{!!$record->{$k}!!}</th>
                 @endforeach
            </tr>
            @endforeach


        </tbody>
      </table>


</div>


