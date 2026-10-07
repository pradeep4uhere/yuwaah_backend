<?php

namespace App\Exports;

use App\Models\LearnerImportFailure;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LearnerImportFailedExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize
{
    protected int $importId;

    public function __construct(int $importId)
    {
        $this->importId = $importId;
    }

    public function collection(): Collection
    {
        $failures = LearnerImportFailure::query()
            ->where('learner_import_id', $this->importId)
            ->orderBy('row_number')
            ->get();

        return $failures->map(function ($failure) {

            $rowData = $failure->row_data;

            if (is_string($rowData)) {
                $rowData = json_decode($rowData, true);
            }

            if (!is_array($rowData)) {
                $rowData = [];
            }

            return [
                'row_number'   => $failure->row_number,
                'candidate_id' => $failure->candidate_id,
                'email'        => $failure->email,
                'mobile'       => $failure->mobile_number,

                'dlp'          => $rowData['dlp'] ?? '',
                'fee'          => $rowData['fee'] ?? '',
                'state'        => $rowData['state'] ?? '',
                'title'        => $rowData['title'] ?? '',
                'gender'       => $rowData['gender'] ?? '',
                'status'       => $rowData['status'] ?? '',
                'tp_name'      => $rowData['tp_name'] ?? '',
                'district'     => $rowData['district'] ?? '',
                'course_id'    => $rowData['course_id'] ?? '',
                'last_name'    => $rowData['last_name'] ?? '',
                'first_name'   => $rowData['first_name'] ?? '',
                'isenrolled'   => $rowData['isenrolled'] ?? '',
                'iscertified'  => $rowData['iscertified'] ?? '',
                'course_status_enum' =>
                    $rowData['course_status_enum'] ?? '',

                'course_enrollment_date' =>
                    $rowData['course_enrollment_date'] ?? '',

                'certification_issue_date' =>
                    $rowData['certification_issue_date'] ?? '',

                // IMPORTANT
                'failure_reason' => $failure->reason,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Row Number',
            'Candidate ID',
            'Email',
            'Mobile',

            'DLP',
            'Fee',
            'State',
            'Title',
            'Gender',
            'Status',
            'TP Name',
            'District',
            'Course ID',
            'Last Name',
            'First Name',
            'Is Enrolled',
            'Is Certified',
            'Course Status Enum',
            'Course Enrollment Date',
            'Certification Issue Date',

            'Failure Reason',
        ];
    }
}