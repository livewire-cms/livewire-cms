<?php namespace Modules\Hello\Models;

use Modules\LivewireCore\Database\Model;


class World extends Model
{
    public $attachOne = [
        'avatar' => \Modules\System\Models\File::class
    ];
    protected $casts = [
        'extra' => 'json',
    ];
}
