<?php

namespace Modules\Backend\View\Components\Containers;

use Illuminate\View\Component;

class Container extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('backend::components.containers.container');
    }
}
