<?php namespace Winter\Storm\Halcyon;

use Illuminate\Support\Collection as CollectionBase;

/**
 * This class represents a collection of Halcyon models.
 *
 * @author Alexey Bobkov, Samuel Georges
 */
class Collection extends CollectionBase
{
    public function lists($value, $key = null)
    {
        return $this->pluck($value, $key)->all();
    }
}
