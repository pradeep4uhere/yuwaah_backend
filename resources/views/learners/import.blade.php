<x-app-layout>

    <x-slot name="header">
        <div class="learner-import-header">
            <div>
                <div class="learner-import-header-title">
                    Learner Import
                </div>
                <div class="learner-import-header-subtitle">
                    Upload and safely import daily learner Excel records
                </div>
            </div>
        </div>
    </x-slot>

<style>
    .learner-import-page {
        padding: 24px;
    }

    .import-header {
        margin-bottom: 24px;
    }

    .import-header h2 {
        margin: 0 0 6px;
        font-size: 24px;
        font-weight: 700;
    }

    .import-header p {
        margin: 0;
        color: #6b7280;
    }

    .import-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .04);
    }

    .import-card h4 {
        margin: 0 0 18px;
        font-size: 18px;
        font-weight: 700;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-weight: 600;
        color: #374151;
    }

    .form-control {
        width: 100%;
        min-height: 42px;
        padding: 9px 12px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
    }

    .file-help {
        margin-top: 6px;
        font-size: 13px;
        color: #6b7280;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 17px;
        border: 0;
        border-radius: 7px;
        cursor: pointer;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
    }

    .btn-primary {
        background: #2563eb;
        color: #fff;
    }

    .btn-primary:hover {
        background: #1d4ed8;
    }

    .btn-success {
        background: #16a34a;
        color: #fff;
    }

    .btn-success:hover {
        background: #15803d;
    }

    .btn-danger {
        background: #dc2626;
        color: #fff;
    }

    .btn-secondary {
        background: #6b7280;
        color: #fff;
    }

    .btn-template {
        background: #111827;
        color: #fff;
    }

    .alert {
        padding: 13px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .alert-danger {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .alert-warning {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .alert-info {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .summary-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
    }

    .summary-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 8px;
    }

    .summary-value {
        font-size: 28px;
        font-weight: 700;
    }

    .summary-total .summary-value {
        color: #111827;
    }

    .summary-success .summary-value {
        color: #16a34a;
    }

    .summary-duplicate .summary-value {
        color: #d97706;
    }

    .summary-failed .summary-value {
        color: #dc2626;
    }

    .preview-meta {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .meta-item {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        padding: 12px 14px;
        border-radius: 8px;
    }

    .meta-label {
        display: block;
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .meta-value {
        font-weight: 600;
        color: #111827;
        word-break: break-word;
    }

    .preview-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .details-section {
        margin-top: 24px;
        border-top: 1px solid #e5e7eb;
        padding-top: 20px;
    }

    .details-section h5 {
        margin: 0 0 12px;
        font-size: 16px;
        font-weight: 700;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }

    .import-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
    }

    .import-table th,
    .import-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
        font-size: 13px;
        vertical-align: top;
    }

    .import-table th {
        background: #f9fafb;
        font-weight: 700;
        color: #374151;
    }

    .import-table tr:last-child td {
        border-bottom: 0;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-success {
        background: #dcfce7;
        color: #166534;
    }

    .badge-warning {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-info {
        background: #dbeafe;
        color: #1e40af;
    }

    .empty-state {
        padding: 25px;
        text-align: center;
        color: #6b7280;
    }

    .required {
        color: #dc2626;
    }

    .actions-cell {
        white-space: nowrap;
    }

    @media (max-width: 900px) {
        .summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .preview-meta {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 600px) {
        .learner-import-page {
            padding: 14px;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .import-card {
            padding: 16px;
        }
    }
</style>

<div class="learner-import-page">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="import-header">
        <h2>Import Learners</h2>
        <p>
            Upload the daily learner Excel file and import new learners safely.
            Existing learners will be automatically skipped.
        </p>
    </div>


    {{-- =========================================================
         FLASH MESSAGES
    ========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success">
            <strong>Success:</strong>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <strong>Error:</strong>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following:</strong>

            <ul style="margin:8px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- =========================================================
         TODAY SUMMARY
    ========================================================== --}}

    <div class="summary-grid">

        <div class="summary-card summary-total">
            <div class="summary-label">Today's Imported Records</div>
            <div class="summary-value">
                {{ number_format($todayImported ?? 0) }}
            </div>
        </div>

        <div class="summary-card summary-success">
            <div class="summary-label">Today's Successful</div>
            <div class="summary-value">
                {{ number_format($todaySuccessful ?? 0) }}
            </div>
        </div>

        <div class="summary-card summary-duplicate">
            <div class="summary-label">Today's Duplicates</div>
            <div class="summary-value">
                {{ number_format($todayDuplicates ?? 0) }}
            </div>
        </div>

        <div class="summary-card summary-failed">
            <div class="summary-label">Today's Failed</div>
            <div class="summary-value">
                {{ number_format($todayFailed ?? 0) }}
            </div>
        </div>

    </div>


    {{-- =========================================================
         PREVIEW RESULT
    ========================================================== --}}

    @if(session('preview_ready') && session('learner_import_preview'))

        @php
            $preview = session('learner_import_preview');
            $summary = $preview['summary'] ?? [];

            $failedRows = $summary['failed_rows'] ?? [];
            $duplicateRows = $summary['duplicate_rows'] ?? [];
        @endphp

        <div class="import-card">

            <h4>
                <i class="fas fa-eye"></i>
                Import Preview
            </h4>

            <div class="alert alert-info">
                <strong>Preview generated successfully.</strong>
                No learner records have been inserted yet.
                Please review the results before confirming the import.
            </div>

            {{-- Preview Meta --}}
            <div class="preview-meta">

                <div class="meta-item">
                    <span class="meta-label">Excel File</span>
                    <span class="meta-value">
                        {{ $preview['file_name'] ?? '—' }}
                    </span>
                </div>

                <div class="meta-item">
                    <span class="meta-label">Program Code</span>
                    <span class="meta-value">
                        {{ $preview['program_code'] ?? '—' }}
                    </span>
                </div>

            </div>


            {{-- Preview Summary --}}
            <div class="summary-grid">

                <div class="summary-card summary-total">
                    <div class="summary-label">Total Records</div>
                    <div class="summary-value">
                        {{ number_format($summary['total'] ?? 0) }}
                    </div>
                </div>

                <div class="summary-card summary-success">
                    <div class="summary-label">Valid Records</div>
                    <div class="summary-value">
                        {{ number_format($summary['successful'] ?? 0) }}
                    </div>
                </div>

                <div class="summary-card summary-duplicate">
                    <div class="summary-label">Duplicates</div>
                    <div class="summary-value">
                        {{ number_format($summary['duplicates'] ?? 0) }}
                    </div>
                </div>

                <div class="summary-card summary-failed">
                    <div class="summary-label">Failed</div>
                    <div class="summary-value">
                        {{ number_format($summary['failed'] ?? 0) }}
                    </div>
                </div>

            </div>


            {{-- Failed warning --}}
            @if(($summary['failed'] ?? 0) > 0)

                <div class="alert alert-warning">
                    <strong>
                        {{ $summary['failed'] }}
                        record(s) failed validation.
                    </strong>

                    These records will not be imported.
                    Review the failure details below.
                </div>

            @endif


            {{-- Duplicate information --}}
            @if(($summary['duplicates'] ?? 0) > 0)

                <div class="alert alert-info">
                    <strong>
                        {{ $summary['duplicates'] }}
                        duplicate record(s) found.
                    </strong>

                    Existing learners will be skipped automatically.
                </div>

            @endif


            {{-- =================================================
                 FAILED ROW DETAILS
            ================================================== --}}

            @if(count($failedRows) > 0)

                <div class="details-section">

                    <h5>
                        <i class="fas fa-exclamation-triangle"></i>
                        Failed Records
                    </h5>

                    <div class="table-wrapper">

                        <table class="import-table">

                            <thead>
                                <tr>
                                    <th>Excel Row</th>
                                    <th>Candidate ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach(array_slice($failedRows, 0, 100) as $failure)

                                    @php
                                        $failureData = $failure['data'] ?? [];
                                    @endphp

                                    <tr>

                                        <td>
                                            {{ $failure['row'] ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $failureData['candidate_id'] ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $failureData['first_name'] ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $failureData['last_name'] ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $failureData['email'] ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $failureData['mobile_number'] ?? '—' }}
                                        </td>

                                        <td>
                                            <span class="badge badge-danger">
                                                {{ $failure['reason'] ?? 'Validation failed' }}
                                            </span>
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    @if(count($failedRows) > 100)
                        <div class="file-help">
                            Showing first 100 failed records.
                            Total failed records:
                            {{ count($failedRows) }}.
                        </div>
                    @endif

                </div>

            @endif


            {{-- =================================================
                 DUPLICATE ROW DETAILS
            ================================================== --}}

            @if(count($duplicateRows) > 0)

                <div class="details-section">

                    <h5>
                        <i class="fas fa-copy"></i>
                        Duplicate Records
                    </h5>

                    <div class="table-wrapper">

                        <table class="import-table">

                            <thead>
                                <tr>
                                    <th>Excel Row</th>
                                    <th>Candidate ID</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Matched By</th>
                                    <th>Existing Learner ID</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach(array_slice($duplicateRows, 0, 100) as $duplicate)

                                    <tr>

                                        <td>
                                            {{ $duplicate['row'] ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $duplicate['candidate_id'] ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $duplicate['email'] ?? '—' }}
                                        </td>

                                        <td>
                                            {{ $duplicate['mobile'] ?? '—' }}
                                        </td>

                                        <td>
                                            <span class="badge badge-warning">
                                                {{ $duplicate['reason'] ?? 'Duplicate' }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $duplicate['learner_id'] ?? '—' }}
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    @if(count($duplicateRows) > 100)
                        <div class="file-help">
                            Showing first 100 duplicate records.
                            Total duplicates:
                            {{ count($duplicateRows) }}.
                        </div>
                    @endif

                </div>

            @endif


            {{-- =================================================
                 CONFIRM / CANCEL
            ================================================== --}}

            <div class="preview-actions">

                @if(($summary['successful'] ?? 0) > 0)

                    <form
                        method="POST"
                        action="{{ route('learners.import.confirm') }}"
                        style="display:inline;"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="btn btn-success"
                            onclick="return confirm('Are you sure you want to import the valid learner records?');"
                        >
                            <i class="fas fa-check"></i>
                            Confirm & Import
                        </button>

                    </form>

                @endif


                <form
                    method="POST"
                    action="{{ route('learners.import.cancel') }}"
                    style="display:inline;"
                >
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to cancel this import preview?');"
                    >
                        <i class="fas fa-times"></i>
                        Cancel
                    </button>

                </form>

            </div>

        </div>

    @endif


    {{-- =========================================================
         UPLOAD FORM
    ========================================================== --}}

    @if(!session('preview_ready'))

        <div class="import-card">

            <h4>
                <i class="fas fa-file-import"></i>
                Upload Learner Excel File
            </h4>

            <form
                method="POST"
                action="{{ route('learners.import.preview') }}"
                enctype="multipart/form-data"
            >

                @csrf


                {{-- Program --}}
                <div class="form-group">

                    <label for="program_code">
                        Program Code
                        <span class="required">*</span>
                    </label>

                    <select
                        name="program_code"
                        id="program_code"
                        class="form-control"
                        required
                    >
                        <option value="">Select Program</option>

                        <option
                            value="NMDC"
                            {{ old('program_code') === 'NMDC' ? 'selected' : '' }}
                        >
                            NMDC
                        </option>

                    </select>

                </div>


                {{-- Excel File --}}
                <div class="form-group">

                    <label for="excelFile">
                        Excel File
                        <span class="required">*</span>
                    </label>

                    <input
                        type="file"
                        name="excel_file"
                        id="excelFile"
                        class="form-control"
                        accept=".xlsx,.xls"
                        required
                    >

                    <div class="file-help">
                        Accepted formats: XLSX, XLS.
                        Maximum file size: 10 MB.
                    </div>

                </div>


                {{-- Actions --}}
                <div class="preview-actions">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="fas fa-eye"></i>
                        Preview Import
                    </button>

                    <a
                        href="{{ route('learners.import.template') }}"
                        class="btn btn-template"
                    >
                        <i class="fas fa-download"></i>
                        Download Template
                    </a>

                </div>

            </form>

        </div>

    @endif


    {{-- =========================================================
         RECENT IMPORT HISTORY
    ========================================================== --}}

    <div class="import-card">

        <h4>
            <i class="fas fa-history"></i>
            Recent Imports
        </h4>

        @if(isset($recentImports) && $recentImports->count())

            <div class="table-wrapper">

                <table class="import-table">

                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>File</th>
                            <th>Program</th>
                            <th>Total</th>
                            <th>Successful</th>
                            <th>Duplicates</th>
                            <th>Failed</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($recentImports as $row)

                            <tr>

                                <td>
                                    {{ $row->created_at?->format('d M Y H:i') ?? '—' }}
                                </td>

                                <td>
                                    {{ $row->file_name }}
                                </td>

                                <td>
                                    <span class="badge badge-info">
                                        {{ $row->program_code }}
                                    </span>
                                </td>

                                <td>
                                    {{ number_format($row->total_records) }}
                                </td>

                                <td>
                                    <span class="badge badge-success">
                                        {{ number_format($row->success_count) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge badge-warning">
                                        {{ number_format($row->duplicate_count) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge badge-danger">
                                        {{ number_format($row->failed_count) }}
                                    </span>
                                </td>

                                <td>

                                    @if($row->status === 'completed')

                                        <span class="badge badge-success">
                                            Completed
                                        </span>

                                    @elseif($row->status === 'processing')

                                        <span class="badge badge-warning">
                                            Processing
                                        </span>

                                    @else

                                        <span class="badge badge-danger">
                                            Failed
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-state">
                No learner imports have been recorded yet.
            </div>

        @endif

    </div>

</div>

</x-app-layout>

