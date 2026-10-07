<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LearnerImportDataSheet implements
    FromArray,
    WithStyles,
    WithColumnWidths,
    WithTitle
{
    /**
     * Excel headers.
     */
    public function array(): array
    {
        return [
            [
                'candidate_id',
                'TP NAME',
                'first_name',
                'last_name',
                'gender',
                'state',
                'district',
                'mobile_number',
                'email',
                'iscertified',
            ],
        ];
    }

    /**
     * Sheet title.
     */
    public function title(): string
    {
        return 'Learners';
    }

    /**
     * Column widths.
     */
    public function columnWidths(): array
    {
        return [
            'A' => 22, // candidate_id
            'B' => 32, // TP NAME
            'C' => 22, // first_name
            'D' => 22, // last_name
            'E' => 14, // gender
            'F' => 22, // state
            'G' => 25, // district
            'H' => 20, // mobile_number
            'I' => 38, // email
            'J' => 18, // iscertified
        ];
    }

    /**
     * Style worksheet.
     */
    public function styles(Worksheet $sheet): array
    {
        /*
        |--------------------------------------------------------------------------
        | Header
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A1:J1')->applyFromArray([
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

        /*
        |--------------------------------------------------------------------------
        | Header row height
        |--------------------------------------------------------------------------
        */

        $sheet->getRowDimension(1)->setRowHeight(30);

        /*
        |--------------------------------------------------------------------------
        | Freeze header
        |--------------------------------------------------------------------------
        */

        $sheet->freezePane('A2');

        /*
        |--------------------------------------------------------------------------
        | Auto filter
        |--------------------------------------------------------------------------
        */

        $sheet->setAutoFilter('A1:J1');

        /*
        |--------------------------------------------------------------------------
        | Make first row visually prominent
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        return [];
    }
}