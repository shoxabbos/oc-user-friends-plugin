<?php namespace Shohabbos\Friends;

use Event;
use Backend;
use RainLab\User\Models\User;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public $required = [
        'RainLab.User'
    ];

    public function boot()
    {
        Event::listen('backend.menu.extendItems', function($manager) {
            $this->extendUserMenu($manager);
        });

        User::extend(function(User $model) {
            $model->hasMany['friends'] = \Shohabbos\Friends\Models\Friend::class;
            $model->addDynamicMethod('isFriend', function ($user_id) use ($model) {
                foreach ($model->friends as $friend) {
                    if ($friend->friend_id == $user_id && $friend->status)
                    {
                        return $friend->status;
                    } elseif ($friend->friend_id == $user_id)
                    {
                        return false;
                    }
                }
            });
        });

    }

    public function registerComponents()
    {
        return [
            'Shohabbos\Friends\Components\FriendsList' => 'FriendsList',
            'Shohabbos\Friends\Components\FriendshipRequests' => 'FriendshipRequests',
            'Shohabbos\Friends\Components\AjaxRequests' => 'AjaxRequests'
        ];
    }

    public function registerSettings()
    {
    }


    // 
    // Helpers
    //
    private function extendUserMenu($manager) {
        $manager->addSideMenuItems('RainLab.User', 'user', [
            'friends' => [
                'label'       => 'Friends',
                'url'         => Backend::url('/shohabbos/friends/friends'),
                'icon'        => 'icon-users',
                'permissions' => [
                    'manage_friends'
                ]
            ]
        ]);
    }


}
