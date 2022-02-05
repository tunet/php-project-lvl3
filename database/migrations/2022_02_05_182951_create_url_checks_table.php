<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlChecksTable extends Migration
{
    public function up(): void
    {
        Schema::create('url_checks', function (Blueprint $table) {
            $table->id();
            $table->string('status_code')->nullable();
            $table->string('h1')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->dateTime('created_at');
            $table->foreignId('url_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('url_checks');
    }
}
