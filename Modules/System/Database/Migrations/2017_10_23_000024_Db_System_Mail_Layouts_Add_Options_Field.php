<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DbSystemMailLayoutsAddOptionsField extends Migration
{
    public function up()
    {
        Schema::table('system_mail_layouts', function (Blueprint $table) {
            $table->text('options')->nullable()->after('is_locked');
        });
    }

    public function down()
    {
        Schema::table('system_mail_layouts', function (Blueprint $table) {
            $table->dropColumn('options');
        });
    }
}
