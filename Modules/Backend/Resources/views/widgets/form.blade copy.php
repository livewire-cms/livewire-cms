

<div>



    @php
    $form= $widget->form->render();
    // dd($form);
    // extract($form->vars);
    // dd();
    //  dd($widget->formCategories->render());
    // dd($outsideTabs,$primaryTabs,$secondaryTabs);





    @endphp
    @livewire('backend.livewire.widgets.form.fields',array_merge(['widget'=>$widget],['context'=>$form->context,'modelId'=>$form->model->getKey()]))

    {{-- @if ($outsideTabs->hasFields())
        @if ($outsideTabs->suppressTabs)
        {{-- @livewire('backend.livewire.widgets.form.fields',['fields'=>$outsideTabs]) --}}
            {{-- @foreach ($outsideTabs as $field)
                @if ($field->type=='text')
                @livewire('backend.livewire.widgets.form.text', ['field'=>$field])

                @endif
            @endforeach --}}
        {{-- @endif --}}

    {{-- @endif --}}

    {{-- @if ($primaryTabs->hasFields())
        primaryTabs
    @endif
    @if ($secondaryTabs->hasFields())
        secondaryTabs
    @endif --}}
</div>
