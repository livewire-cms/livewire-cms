<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class DbBackendUsers extends Migration
{
    public function up()
    {
        Schema::create('backend_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('login')->unique('login_unique')->index('login_index');
            $table->string('email')->unique('email_unique');
            $table->string('password');
            $table->string('activation_code')->nullable()->index('act_code_index');
            $table->string('persist_code')->nullable();
            $table->string('reset_password_code')->nullable()->index('reset_code_index');
            $table->text('permissions')->nullable();
            $table->boolean('is_activated')->default(0);
            $table->integer('role_id')->unsigned()->nullable()->index('admin_role_index');
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('last_login')->nullable();


            $table->rememberToken();
            $table->text('two_factor_secret')
                    ->nullable();
            $table->string('name')->nullable();

            $table->text('two_factor_recovery_codes')
                    ->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('backend_users');
    }
}
