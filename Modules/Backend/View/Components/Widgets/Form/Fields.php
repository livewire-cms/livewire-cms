<?php

namespace Modules\Backend\View\Components\Widgets\Form;

use Illuminate\View\Component;

class Fields extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('backend::components.widgets.form.fields');
    }
}
