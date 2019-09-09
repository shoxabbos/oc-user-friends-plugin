<?php namespace Shohabbos\Friends\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateShohabbosFriendsRequests extends Migration
{
    public function up()
    {
        Schema::create('shohabbos_friends_requests', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('requester_id')->unsigned();
            $table->integer('accepter_id')->unsigned();
            $table->integer('status')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('shohabbos_friends_requests');
    }
}