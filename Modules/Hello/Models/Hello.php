<?php namespace Modules\Hello\Models;

use Modules\LivewireCore\Database\Model;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use DbDongle;

class Hello extends Model
{
    // use \Modules\LivewireCore\Support\Traits\Emitter;
    // use \Modules\LivewireCore\Extension\ExtendableTrait;
    use \Modules\LivewireCore\Database\Traits\Validation;

    /**
     * @var array Rules
     */
    public $rules = [
        'name' => 'required',
    ];

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
    public $hasOne = [
        'phone' => [Phone::class, 'key' => 'person_id', 'scope' => 'isActive'],
    ];
    protected $casts = [
        'excerpt' => 'json',
        'extra' => 'json',
        'mood' => 'json',
    ];

    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class, 'category_hellos', 'hello_id', 'category_id');
    // }


}
