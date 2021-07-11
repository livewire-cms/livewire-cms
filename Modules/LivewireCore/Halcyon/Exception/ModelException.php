<?php namespace Modules\LivewireCore\Halcyon\Exception;

use Modules\LivewireCore\Halcyon\Model;
use Modules\LivewireCore\Exception\ValidationException;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;
use InvalidArgumentException;
use Exception;


/**
 * Used when validation fails. Contains the invalid model for easy analysis.
 *
 * @author Alexey Bobkov, Samuel Georges
 */
class ModelException extends Exception
{

    /**
     * @var Model The invalid model.
     */
    protected $model;

    /**
     * Receives the invalid model and sets the {@link model} and {@link errors} properties.
     * @param Model $model The troublesome model.
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->errors = $model->errors();
        $this->evalErrors();
    }

    /**
     * Returns the model with invalid attributes.
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

     /**
     * Evaluate errors.
     */
    protected function evalErrors()
    {
        foreach ($this->errors->getMessages() as $field => $messages) {
            $this->fields[$field] = $messages;
        }

        $this->message = $this->errors->first();
    }

    /**
     * Returns directly the message bag instance with the model's errors.
     * @return \Illuminate\Support\MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns invalid fields.
     */
    public function getFields()
    {
        return $this->fields;
    }
}
