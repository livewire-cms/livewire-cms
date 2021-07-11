<?php

namespace Modules\Backend\View\Components\Widgets;

use Illuminate\View\Component;

class Form extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('backend::components.widgets.form');
    }
}
