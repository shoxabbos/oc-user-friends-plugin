<?php namespace Shohabbos\Friends\Models;

use Model;
use RainLab\User\Models\User;
/**
 * Model
 */
class Friend extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'shohabbos_friends_friends';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'user' => User::class,
        'friend' => User::class,
    ];

    public $guarded = ['id'];

}
