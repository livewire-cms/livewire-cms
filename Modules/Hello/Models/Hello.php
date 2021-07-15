<?php namespace Modules\Hello\Models;

use Modules\LivewireCore\Database\Model;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use DbDongle;

class Hello extends Model
{
    // use \Modules\LivewireCore\Support\Traits\Emitter;
    // use \Modules\LivewireCore\Extension\ExtendableTrait;



    public $belongsToMany = [
        'categories' => [
            Category::class,
            'table'=>'category_hellos',
            'hello_id'
        ]
    ];

    public $hasMany = [
        'worlds' => [
            World::class,
        ]
    ];
    public $belongsTo = [
        'category' => [
            Category::class,
        ]
    ];
    public $attachOne = [
        'avatar' => \Modules\System\Models\File::class
    ];

    protected $casts = [
        'extra' => 'json',
        'mood' => 'json',
    ];

    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class, 'category_hellos', 'hello_id', 'category_id');
    // }


}
