<?php namespace Shohabbos\Friends\Models;

use Model;

/**
 * Model
 */
class FriendshipRequest extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'shohabbos_friends_requests';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $guarded = ['id'];

    public $belongsTo = [
        'requester' => \Rainlab\User\Models\User::class,
        'accepter'  => \Rainlab\User\Models\User::class
    ];
}
