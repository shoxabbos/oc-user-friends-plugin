<?php namespace Shohabbos\Friends\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateShohabbosFriendsFriends extends Migration
{
    public function up()
    {
        Schema::create('shohabbos_friends_friends', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('friend_id')->unsigned();
            $table->integer('status')->unsigned()->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('shohabbos_friends_friends');
    }
}
