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
        Schema::create('event_transaction_comments', function (Blueprint $table) {
            $table->id();
            $table->string('comment'); // comment - string
            $table->unsignedBigInteger('agent_id'); // agent_id - integer
            $table->unsignedBigInteger('event_transaction_id'); // event_transaction_id - integer
            $table->unsignedBigInteger('user_id'); // user_id - integer
            $table->string('user_name'); // user_name - string
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_transaction_comments');
    }
};
