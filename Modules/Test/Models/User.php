<?php

namespace Modules\Test\Models;

use Modules\LivewireCore\Database\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use \Modules\LivewireCore\Database\Traits\Validation;

    use HasFactory;

   // protected $fillable = [];

   public $table = 'test_users';
     /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [
        'phone' => Phone::class,
    ];
    public $hasMany = [
        'posts' => Post::class,
    ];
    public $hasOneThrough = [];
    public $hasManyThrough = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [
        'avatar' => \Modules\System\Models\File::class
    ];
    public $attachMany = [];
}
