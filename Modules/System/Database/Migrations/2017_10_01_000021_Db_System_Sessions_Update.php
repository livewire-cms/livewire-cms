<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DbSystemSessionsUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('sessions', function (Blueprint $table) {
        //     $table->unsignedInteger('user_id')->nullable();
        //     $table->string('ip_address', 45)->nullable();
        //     $table->text('user_agent')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // ...
    }
}
