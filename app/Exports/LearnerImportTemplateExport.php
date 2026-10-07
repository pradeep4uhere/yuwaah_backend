<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LearnerImportTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new LearnerImportDataSheet(),
            new LearnerImportInstructionsSheet(),
        ];
    }
}