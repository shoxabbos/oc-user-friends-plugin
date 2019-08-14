<?php namespace Shohabbos\Friends\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Friends extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController'
    ];
    
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = [
        'manage_friends' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('RainLab.User', 'user', 'friends');
    }
}
