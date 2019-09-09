<?php namespace Shohabbos\Friends\Models;

use Model;

/**
 * Model
 */
class FriendshipRequests extends Model
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
}
