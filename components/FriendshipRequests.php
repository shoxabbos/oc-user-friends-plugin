<?php namespace Shohabbos\Friends\Components;

use Auth;
use Flash;
use Input;
use Validator;
use ValidationException;
use Cms\Classes\ComponentBase;
use Shohabbos\Messenger\Models\Conversation;
use Shohabbos\Friends\Models\Friend;
use Shohabbos\Friends\Models\FriendshipRequest as FriendshipRequestModel;

class FriendshipRequests extends ComponentBase
{
    private $user;
    private $conversations;
    private $requests;

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
        $this->page['requests'] = $this->requests = $this->loadRequestsList();
    }


    //
    // AJAX
    //


    //
    // Helpers
    //


    protected function loadRequestsList()
    {
        return FriendshipRequestModel::where('accepter_id', $this->user->id)->where('status', 1)->get();
    } 

}
