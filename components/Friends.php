<?php namespace Shohabbos\Messenger\Components;

use Auth;
use Flash;
use Input;
use Validator;
use ValidationException;
use Cms\Classes\ComponentBase;
use Shohabbos\Messenger\Models\Conversation;

class ChatList extends ComponentBase
{
    private $user;
    private $conversations;

    public function componentDetails()
    {
        return [
            'name'        => 'Friend list Component',
            'description' => 'Display friend list of user'
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
        $this->page['conversations'] = $this->conversations = $this->loadConversations();
    }


    //
    // AJAX
    //
    public function onFriendRequest() {
        $user = Auth::getUser();
        $data = Input::only(['friend_id']);
        
        $rules = [
            'friend_id' => 'required',
        ];

        $validation = Validator::make($data, $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $conversations = new Conversation([
            'title' => $data['title'],
            'creator_id' => $user->id,
            'participants' => $participants,
        ]);

        if ($conversations->save()) {
            Flash::success('Ok');
        } else {
            Flash::error('Error');
        }
    }


    //
    // Helpers
    //

}
