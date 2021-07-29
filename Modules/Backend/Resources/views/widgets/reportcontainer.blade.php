

<div class="text-center">

@foreach ($reportWidgets as $reportWidget)
    @livewire('backend.livewire.widgets.reportwidget',[
        'widget'=>$widget,
        'reportWidget'=>$reportWidget,
    ])
@endforeach

</div>
