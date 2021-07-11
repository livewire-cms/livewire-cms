<?php namespace Modules\LivewireCore\Database;

use Modules\LivewireCore\Extension\ExtensionBase;

/**
 * Base class for model behaviors.
 *
 * @author Alexey Bobkov, Samuel Georges
 */
class ModelBehavior extends ExtensionBase
{
    /**
     * @var \Modules\LivewireCore\Database\Model Reference to the extended model.
     */
    protected $model;

    /**
     * Constructor
     * @param \Modules\LivewireCore\Database\Model $model The extended model.
     */
    public function __construct($model)
    {
        $this->model = $model;
    }
}
