<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LearnerImportInstructionsSheet implements
    FromArray,
    WithStyles,
    WithColumnWidths,
    WithTitle
{
    /**
     * Instructions content.
     */
    public function array(): array
    {
        return [

            [
                'LEARNER IMPORT TEMPLATE - INSTRUCTIONS',
                '',
                '',
                '',
            ],

            [
                'Purpose',
                'Use the "Learners" sheet to upload learner enrollment data.',
                '',
                '',
            ],

            [
                'Important',
                'Do not change the column names in the Learners sheet.',
                '',
                '',
            ],

            [
                'Important',
                'Do not delete or rename the Learners sheet.',
                '',
                '',
            ],

            [
                'File Format',
                'Save/upload the file as XLSX or XLS.',
                '',
                '',
            ],

            [
                '',
                '',
                '',
                '',
            ],

            [
                'COLUMN',
                'DESCRIPTION',
                'REQUIRED',
                'EXPECTED VALUE / FORMAT',
            ],

            [
                'candidate_id',
                'Unique candidate identifier from the source system.',
                'Recommended',
                'Text / ID',
            ],

            [
                'TP NAME',
                'Training Partner / Training Provider name.',
                'Optional',
                'Text',
            ],

            [
                'first_name',
                'Learner first name.',
                'YES',
                'Text',
            ],

            [
                'last_name',
                'Learner last name.',
                'YES',
                'Text',
            ],

            [
                'gender',
                'Learner gender.',
                'Optional',
                '1 = Male, 2 = Female, other = Other',
            ],

            [
                'state',
                'Learner/program state.',
                'Optional',
                'Text',
            ],

            [
                'district',
                'Learner/program district.',
                'Optional',
                'Text',
            ],

            [
                'mobile_number',
                'Learner mobile number.',
                'Recommended',
                '10 digit mobile number',
            ],

            [
                'email',
                'Learner email address.',
                'Recommended',
                'Valid email address',
            ],

            [
                'iscertified',
                'Indicates whether the learner is certified/completed.',
                'Optional',
                '1 = Certified/Completed, 0 = Not Certified',
            ],

            [
                '',
                '',
                '',
                '',
            ],

            [
                'DUPLICATE RULE',
                'Existing learners are not imported again.',
                '',
                '',
            ],

            [
                'Duplicate Priority',
                'Candidate ID → Email → Mobile Number',
                '',
                '',
            ],

            [
                'PROGRAM CODE',
                'Selected separately on the upload page.',
                '',
                'Example: NMDC',
            ],

            [
                '',
                '',
                '',
                '',
            ],

            [
                'IMPORTANT NOTES',
                '',
                '',
                '',
            ],

            [
                '1',
                'Do not modify the header names.',
                '',
                '',
            ],

            [
                '2',
                'Do not insert formulas into the Learners sheet.',
                '',
                '',
            ],

            [
                '3',
                'Remove completely empty rows before uploading.',
                '',
                '',
            ],

            [
                '4',
                'Use valid email addresses where email is provided.',
                '',
                '',
            ],

            [
                '5',
                'Mobile numbers should preferably contain 10 digits.',
                '',
                '',
            ],

            [
                '6',
                'The Program Code is selected from the web upload form.',
                '',
                '',
            ],
        ];
    }

    /**
     * Sheet title.
     */
    public function title(): string
    {
        return 'Instructions';
    }

    /**
     * Column widths.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 28,
            'B' => 55,
            'C' => 18,
            'D' => 45,
        ];
    }

    /**
     * Worksheet styling.
     */
    public function styles(Worksheet $sheet): array
    {
        /*
        |--------------------------------------------------------------------------
        | Main title
        |--------------------------------------------------------------------------
        */

        $sheet->mergeCells('A1:D1');

        $sheet->getStyle('A1:D1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
            ],

            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(32);

        /*
        |--------------------------------------------------------------------------
        | General wrapping
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A1:D' . count($this->array()))
            ->getAlignment()
            ->setVertical('top')
            ->setWrapText(true);

        /*
        |--------------------------------------------------------------------------
        | Column heading
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A7:D7')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],

            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
                'wrapText' => true,
            ],

            'border' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ],
            ],
        ]);

        $sheet->getRowDimension(7)->setRowHeight(30);

        /*
        |--------------------------------------------------------------------------
        | Important notes heading
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A25:D25')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Borders for instruction table
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A7:D23')->applyFromArray([
            'border' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                ],
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Freeze
        |--------------------------------------------------------------------------
        */

        $sheet->freezePane('A8');

        return [];
    }
}