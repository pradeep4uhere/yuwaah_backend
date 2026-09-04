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
        Schema::table('event_transaction_comments', function (Blueprint $table) {
            $table->string('sakhi_id')->nullable()->default(null)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_transaction_comments', function (Blueprint $table) {
            $table->dropColumn('sakhi_id');
        });
    }
};
