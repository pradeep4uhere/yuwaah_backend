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
        Schema::create('learner_import_failures', function (Blueprint $table) {
            $table->id();
        
            $table->foreignId('learner_import_id')
                ->constrained('learner_imports')
                ->cascadeOnDelete();
        
            $table->unsignedInteger('row_number')->nullable();
        
            $table->string('candidate_id')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_number')->nullable();
        
            $table->text('reason')->nullable();
        
            $table->json('row_data')->nullable();
        
            $table->timestamps();
        
            $table->index('learner_import_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learner_import_failures');
    }
};
