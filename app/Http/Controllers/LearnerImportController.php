<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LearnerImportTemplateExport;
use App\Imports\LearnerImport;
use App\Models\LearnerImport as LearnerImportLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\LearnerImportFailure;
use App\Exports\LearnerImportFailedExport;

class LearnerImportController extends Controller
{


    public function index()
    {
        $todayImported = LearnerImportLog::whereDate('created_at', today())
            ->sum('total_records');
    
        $todaySuccessful = LearnerImportLog::whereDate('created_at', today())
            ->sum('success_count');
    
        $todayDuplicates = LearnerImportLog::whereDate('created_at', today())
            ->sum('duplicate_count');
    
        $todayFailed = LearnerImportLog::whereDate('created_at', today())
            ->sum('failed_count');
    
        $recentImports = LearnerImportLog::with('uploader')
            ->latest()
            ->take(10)
            ->get();
    
        return view('learners.import', [
            'todayImported' => $todayImported,
            'todaySuccessful' => $todaySuccessful,
            'todayDuplicates' => $todayDuplicates,
            'todayFailed' => $todayFailed,
            'recentImports' => $recentImports,
        ]);
    }

    /**
     * Import learners from Excel.
     */
    public function import(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate upload
        |--------------------------------------------------------------------------
        */
    
        $validated = $request->validate([
            'program_code' => [
                'required',
                'string',
                'max:100',
            ],
    
            'excel_file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240',
            ],
        ], [
            'program_code.required' => 'Please select a program.',
    
            'excel_file.required' => 'Please select an Excel file.',
    
            'excel_file.mimes' => 'Only XLSX or XLS Excel files are allowed.',
    
            'excel_file.max' => 'The Excel file may not be larger than 10 MB.',
        ]);
    
        $programCode = $validated['program_code'];
        $file = $request->file('excel_file');
    
        /*
        |--------------------------------------------------------------------------
        | Create import history record
        |--------------------------------------------------------------------------
        */
    
        $importLog = LearnerImportLog::create([
            'file_name' => $file->getClientOriginalName(),
    
            'program_code' => $programCode,
    
            'uploaded_by' => Auth::id(),
    
            'status' => 'processing',
    
            'started_at' => now(),
        ]);

        $importer = new LearnerImport(
            $programCode,
            false,
            $importLog->id
        );
        
        Excel::import(
            $importer,
            $file
        );
    
        try {
    
            /*
            |--------------------------------------------------------------------------
            | Create importer
            |--------------------------------------------------------------------------
            */
    
            $import = new LearnerImport($programCode);
    
            /*
            |--------------------------------------------------------------------------
            | Import Excel
            |--------------------------------------------------------------------------
            */
    
            Excel::import(
                $import,
                $file
            );
    
            /*
            |--------------------------------------------------------------------------
            | Get result
            |--------------------------------------------------------------------------
            */
    
            $summary = $import->getSummary();
    
            /*
            |--------------------------------------------------------------------------
            | Update import history
            |--------------------------------------------------------------------------
            */
    
            $importLog->update([
                'total_records' => $summary['total'],
    
                'success_count' => $summary['successful'],
    
                'duplicate_count' => $summary['duplicates'],
    
                'failed_count' => $summary['failed'],
    
                'status' => 'completed',
    
                'completed_at' => now(),
            ]);
    
            /*
            |--------------------------------------------------------------------------
            | Store summary in session
            |--------------------------------------------------------------------------
            */
    
            session()->flash(
                'import_summary',
                $summary
            );
    
            /*
            |--------------------------------------------------------------------------
            | Success response
            |--------------------------------------------------------------------------
            */
    
            return redirect()
                ->back()
                ->with(
                    'success',
                    sprintf(
                        'Import completed. %d successful, %d duplicates, %d failed.',
                        $summary['successful'],
                        $summary['duplicates'],
                        $summary['failed']
                    )
                );
    
        } catch (\Throwable $e) {
    
            /*
            |--------------------------------------------------------------------------
            | Update failed import
            |--------------------------------------------------------------------------
            */
    
            $importLog->update([
                'status' => 'failed',
    
                'error_message' => $e->getMessage(),
    
                'completed_at' => now(),
            ]);
    
            /*
            |--------------------------------------------------------------------------
            | Log error
            |--------------------------------------------------------------------------
            */
    
            Log::error(
                'Learner Excel Import Failed',
                [
                    'import_id' => $importLog->id,
    
                    'program_code' => $programCode,
    
                    'file_name' => $file->getClientOriginalName(),
    
                    'error' => $e->getMessage(),
    
                    'trace' => $e->getTraceAsString(),
                ]
            );
    
            /*
            |--------------------------------------------------------------------------
            | Return error
            |--------------------------------------------------------------------------
            */
    
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'The Excel import failed. Please check the file and try again.'
                );
        }
    }

    /**
     * Download Excel template.
     *
     * We will implement the actual template generation next.
     */
    public function template()
    {
        return Excel::download(
            new LearnerImportTemplateExport(),
            'learner_import_template.xlsx'
        );
    }

    /**
     * Import history.
     *
     * We will connect this to the import log table next.
     */
    public function history()
    {
        $imports = LearnerImportLog::with('uploader')
            ->latest()
            ->paginate(20);

        return view('learners.import-history', compact('imports'));
    }

    /**
     * Show one import.
     */
    public function show(LearnerImportLog $import)
    {
        $import->load('uploader');
    
        return view('learners.import-show', [
            'import' => $import,
        ]);
    }

   /**
 * Download failed records.
 */
/**
 * Download failed records.
 */
public function downloadFailed(LearnerImportLog $import)
{
    if ($import->failed_count <= 0) {
        return back()->with(
            'error',
            'There are no failed records for this import.'
        );
    }

    $fileName = 'failed_records_' .
        $import->id .
        '_' .
        now()->format('Ymd_His') .
        '.xlsx';

    return Excel::download(
        new LearnerImportFailedExport($import->id),
        $fileName
    );
}


    public function cancel()
    {
        $preview = session('learner_import_preview');

        if ($preview && !empty($preview['file_path'])) {

            if (\Storage::exists($preview['file_path'])) {
                \Storage::delete($preview['file_path']);
            }
        }

        session()->forget(
            'learner_import_preview'
        );

        return redirect()
            ->route('learners.import.form')
            ->with(
                'success',
                'Pending import cancelled.'
            );
    }



    public function confirm()
    {
        $preview = session('learner_import_preview');

        /*
        |--------------------------------------------------------------------------
        | No preview available
        |--------------------------------------------------------------------------
        */

        if (!$preview || empty($preview['file_path'])) {

            return redirect()
                ->route('learners.import.form')
                ->with(
                    'error',
                    'No pending import was found. Please upload the Excel file again.'
                );
        }

        $filePath = $preview['file_path'];
        $programCode = $preview['program_code'];

        try {

            /*
            |--------------------------------------------------------------------------
            | Make sure file still exists
            |--------------------------------------------------------------------------
            */

            if (!\Storage::exists($filePath)) {

                return redirect()
                    ->route('learners.import.form')
                    ->with(
                        'error',
                        'The uploaded Excel file is no longer available. Please upload it again.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Create import log
            |--------------------------------------------------------------------------
            */

            $importLog = LearnerImportLog::create([
                'file_name' => $preview['file_name'],
                'program_code' => $programCode,
                'uploaded_by' => auth()->id(),
                'status' => 'processing',
                'started_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Actual import
            |--------------------------------------------------------------------------
            */

            $import = new LearnerImport(
                $programCode,
                false,
                $importLog->id
            );

            Excel::import(
                $import,
                \Storage::path($filePath)
            );

            /*
            |--------------------------------------------------------------------------
            | Summary
            |--------------------------------------------------------------------------
            */

            $summary = $import->getSummary();

            /*
            |--------------------------------------------------------------------------
            | Update import log
            |--------------------------------------------------------------------------
            */

            $importLog->update([
                'total_records' => $summary['total'],
                'success_count' => $summary['successful'],
                'duplicate_count' => $summary['duplicates'],
                'failed_count' => $summary['failed'],
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Remove pending file
            |--------------------------------------------------------------------------
            */

            \Storage::delete($filePath);

            /*
            |--------------------------------------------------------------------------
            | Clear preview session
            |--------------------------------------------------------------------------
            */

            session()->forget(
                'learner_import_preview'
            );

            /*
            |--------------------------------------------------------------------------
            | Final message
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('learners.import.form')
                ->with(
                    'success',
                    sprintf(
                        'Import completed successfully. %d imported, %d duplicates, %d failed.',
                        $summary['successful'],
                        $summary['duplicates'],
                        $summary['failed']
                    )
                );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Update import log if created
            |--------------------------------------------------------------------------
            */

            if (isset($importLog)) {

                $importLog->update([
                    'status' => 'failed',

                    'error_message' => $e->getMessage(),

                    'completed_at' => now(),
                ]);
            }

            Log::error(
                'Confirmed Learner Import Failed',
                [
                    'program_code' => $programCode,
                    'file_name' => $preview['file_name'] ?? null,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]
            );

            return redirect()
                ->route('learners.import.form')
                ->with(
                    'error',
                    'The learner import failed. No further records were processed.'
                );
        }
    }



    public function preview(Request $request)
    {
        $validated = $request->validate([
            'program_code' => [
                'required',
                'string',
                'max:100',
            ],

            'excel_file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240',
            ],
        ], [
            'program_code.required' => 'Please select a program.',

            'excel_file.required' => 'Please select an Excel file.',

            'excel_file.mimes' => 'Only XLSX or XLS Excel files are allowed.',

            'excel_file.max' => 'The Excel file may not be larger than 10 MB.',
        ]);

        $programCode = $validated['program_code'];

        $file = $request->file('excel_file');

        try {

            /*
            |--------------------------------------------------------------------------
            | Preview only
            |--------------------------------------------------------------------------
            |
            | No learner will be inserted into mysql2.
            |
            */

            $import = new LearnerImport(
                $programCode,
                true
            );

            Excel::import(
                $import,
                $file
            );

            $summary = $import->getSummary();

            /*
            |--------------------------------------------------------------------------
            | Keep uploaded file for confirmation
            |--------------------------------------------------------------------------
            */

            $storedPath = $file->store(
                'learner-imports/pending'
            );

            session([
                'learner_import_preview' => [
                    'file_path' => $storedPath,
                    'file_name' => $file->getClientOriginalName(),
                    'program_code' => $programCode,
                    'summary' => $summary,
                ],
            ]);

            return redirect()
                ->route('learners.import.form')
                ->with('preview_ready', true);

        } catch (\Throwable $e) {

            Log::error(
                'Learner Excel Preview Failed',
                [
                    'program_code' => $programCode,
                    'file_name' => $file->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ]
            );

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to analyse the Excel file. Please check the file format.'
                );
        }
    }
}