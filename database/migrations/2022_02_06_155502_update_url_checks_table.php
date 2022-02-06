<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateUrlChecksTable extends Migration
{
    public function up(): void
    {
        DB::table('url_checks')->delete();

        Schema::table('url_checks', function (Blueprint $table) {
            $table->string('status_code')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('url_checks', function (Blueprint $table) {
            $table->string('status_code')->nullable()->change();
        });
    }
}
