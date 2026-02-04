<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateCourseCompleted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'learners:update-course-completed';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update course_completed for learners based on yhub completion status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $updated = DB::connection('mysql2')->update("
        UPDATE learners l
        INNER JOIN yhub_learners y
            ON l.primary_phone_number = REPLACE(y.email_address, '+91 ', '')
            SET l.course_completed = 1
            WHERE y.completion_status = 1
            AND l.PROGRAM_CODE = 'CSC'
            AND (l.course_completed = 0 OR l.course_completed IS NULL)
        ");

        $this->info("Course completed updated for {$updated} learners.");

        return Command::SUCCESS;
    }
}
