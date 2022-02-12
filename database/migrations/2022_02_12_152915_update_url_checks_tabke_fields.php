<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUrlChecksTabkeFields extends Migration
{
    public function up()
    {
        Schema::table('url_checks', function (Blueprint $table) {
            $table->string('h1', 2048)->change();
            $table->string('title', 2048)->change();
            $table->string('description', 2048)->change();
        });
    }

    public function down()
    {
        Schema::table('url_checks', function (Blueprint $table) {
            $table->string('h1', 255)->change();
            $table->string('title', 255)->change();
            $table->string('description', 255)->change();
        });
    }
}
