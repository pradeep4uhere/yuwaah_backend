<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('api_fetch_log', function (Blueprint $table) {
            $table->id();
            $table->integer('page_number');
            $table->integer('records_fetched');
            $table->integer('total_records');
            $table->integer('records_remaining');
            $table->timestamp('fetched_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_fetch_log');
    }
};
