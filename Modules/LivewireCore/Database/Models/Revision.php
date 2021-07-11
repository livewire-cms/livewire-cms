<?php namespace Modules\LivewireCore\Database\Models;

use Db;
use Modules\LivewireCore\Database\Model;

/**
 * Revision Model
 *
 * @author Alexey Bobkov, Samuel Georges
 */
class Revision extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'revisions';

    /**
     * Returns "new value" casted as the saved type.
     * @return mixed
     */
    public function getNewValueAttribute($value)
    {
        if ($this->cast == 'date' && !is_null($value)) {
            return $this->asDateTime($value);
        }

        return $value;
    }

    /**
     * Returns "old value" casted as the saved type.
     * @return mixed
     */
    public function getOldValueAttribute($value)
    {
        if ($this->cast == 'date' && !is_null($value)) {
            return $this->asDateTime($value);
        }

        return $value;
    }
}
