<?php namespace Shohabbos\Friends\Components;

use Auth;
use Flash;
use Input;
use Validator;
use ValidationException;
use Cms\Classes\ComponentBase;
use Shohabbos\Messenger\Models\Conversation;
use Shohabbos\Friends\Models\Friend;
use Shohabbos\Friends\Models\FriendshipRequests as FriendshipRequestsModels;

class FriendshipRequests extends ComponentBase
{
    private $user;
    private $conversations;
    private $friends;

    public function componentDetails()
    {
        return [
            'name'        => 'Friendship requests list Component',
            'description' => 'Display friendship requests list of user'
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
        $this->user = Auth::getUser();
        
        if (!$this->user) {
            return;
        }

        $this->prepareVars();
    }

    protected function prepareVars()
    {
        //$this->page['conversations'] = $this->conversations = $this->loadConversations();
        $this->page['friends'] = $this->friends = $this->loadRequestsList();
    }


    //
    // AJAX
    //
    public function onSendRequest()
    {
        $user = Auth::getUser();
        $data = Input::only(['friend_id']);
        
        $rules = [
            'friend_id' => 'required|exists:users',
        ];

        $validation = Validator::make($data, $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $friends = new Friend();

        $friends->insert(
            [ 'user_id' => $user->id, 'friend_id' => $data['friend_id'], 'status' => 1 ],
            [ 'user_id' => $data['friend_id'], 'friend_id' => $user->id, 'status' => 2 ]
        );

        return Flash::success('Request sended');
    }

    public function onAcceptRequest()
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
                        ->whereIn('friend_id', [$user->id, $data['friend_id']]);

        $friends->update(['status' => 5]);

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


    protected function loadRequestsList()
    {
        return Friend::where('user_id', $this->user->id)->whereIn('status', [2, 3])->get();
    } 

}
