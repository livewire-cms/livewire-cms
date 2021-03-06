<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DbBackendUsersGroups extends Migration
{
    public function up()
    {
        Schema::create('backend_users_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('user_id')->unsigned();
            $table->integer('user_group_id')->unsigned();
            $table->primary(['user_id', 'user_group_id'], 'user_group');
        });
    }

    public function down()
    {
        Schema::dropIfExists('backend_users_groups');
    }
}
