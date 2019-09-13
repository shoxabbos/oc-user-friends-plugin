<?php namespace Shohabbos\Friends\Components;

use Auth;
use Flash;
use Input;
use Validator;
use ValidationException;
use Cms\Classes\ComponentBase;
use Shohabbos\Messenger\Models\Conversation;
use Shohabbos\Friends\Models\Friend;
use Shohabbos\Friends\Models\FriendshipRequest as FriendshipRequestModels;

class AjaxRequests extends ComponentBase
{
    private $user;
    private $conversations;
    private $friends;

    public function componentDetails()
    {
        return [
            'name'        => 'Ajax Requests',
            'description' => 'Adding ajax requests to the page'
        ];
    }

    public function defineProperties()
    {
        return [
            // 'recordsPerPage' => [
            //     'title'   => 'Records per page',
            //     'comment' => 'Number of notifications to display per page',
            //     'default' => 7
            // ]
        ];
    }

    
    public function onRun()
    {

    }


    //
    // AJAX
    //
    public function onSendRequest()
    {
        $user = Auth::getUser();

        if (!$user){
            return Flash::error('invalid token!');
        }

        $data = Input::only(['accepter_id']);
        
        $rules = [
            'accepter_id' => 'required|exists:users,id',
        ];

        $validation = Validator::make($data, $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }
        $friendship = ['requester_id' => $user->id, 'accepter_id' => $data['accepter_id'], 'status' => 1];
        
        $friendship = new FriendshipRequestModels($friendship);

        $friendship->save();

        return Flash::success('Request sended');
    }

    public function onAcceptRequest()
    {
        $user = Auth::getUser();
        
        if (!$user){
            return Flash::error('invalid token!');
        }

        $data = Input::only(['requester_id']);

        $rules = [
            'requester_id' => 'required|exists:users,id',
        ];

        $validation = Validator::make($data, $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $request = FriendshipRequestModels::where([
            'requester_id' => $data['requester_id'],
            'accepter_id'  => $user->id
        ])->update(['status' => 2]);
        
        $friendship = ['user_id' => $user->id, 'friend_id' => $data['requester_id'], 'status' => 1];

        $friend = new Friend($friendship);
        $friend->save();

        $friendship = ['user_id' => $data['requester_id'], 'friend_id' => $user->id, 'status' => 1];

        $friend = new Friend($friendship);
        $friend->save();

        return Flash::success('Request accepted');
    }

    public function onIgnoreRequest()
    {
        $user = Auth::getUser();
        $data = Input::only(['friend_id']);

        $rules = [
            'friend_id' => 'required|exists:users,id',
        ];

        $validation = Validator::make($data, $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $one = Friend::where([
            [ 'user_id', $user->id ],
            [ 'friend_id', $data['friend_id'] ]
        ])->update(['status' => 3]);
        $two = Friend::where([
            [ 'user_id', $data['friend_id'] ],
            [ 'friend_id', $user->id ]
        ])->update(['status' => 4]);


        return Flash::success('Request ignored');
    }

    public function onDeleteFriend()
    {
        $user = Auth::getUser();
        $data = Input::only(['friend_id']);

        $rules = [
            'friend_id' => 'required|exists:users,id',
        ];

        $validation = Validator::make($data, $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $friends = Friend::whereIn('user_id', [$user->id, $data['friend_id']])
                        ->whereIn('friend_id', [$data['friend_id'], $user->id])->delete();

        return Flash::success('user successfully deleted from friends');
    }

    //
    // Helpers
    //



}
