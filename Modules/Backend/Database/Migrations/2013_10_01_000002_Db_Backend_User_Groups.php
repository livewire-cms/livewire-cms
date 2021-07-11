<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DbBackendUserGroups extends Migration
{
    public function up()
    {
        Schema::create('backend_user_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique('name_unique');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('backend_user_groups');
    }
}
