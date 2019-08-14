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
        });

    }

    public function registerComponents()
    {
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
