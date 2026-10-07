<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learner_imports', function (Blueprint $table) {
            $table->id();

            $table->string('file_name');
            $table->string('program_code', 100);

            $table->unsignedBigInteger('uploaded_by')->nullable();

            $table->unsignedInteger('total_records')->default(0);
            $table->unsignedInteger('success_count')->default(0);
            $table->unsignedInteger('duplicate_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);

            $table->enum('status', [
                'processing',
                'completed',
                'failed',
            ])->default('processing');

            $table->text('error_message')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index('program_code');
            $table->index('uploaded_by');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learner_imports');
    }
};