<x-app-layout>

    <x-slot name="header">
        <div class="learner-import-header">
            <div>
                <h2 class="premium-page-title mb-1">
                    <i class="fas fa-file-import me-2"></i>
                    Import Learners
                </h2>

                <p class="premium-page-subtitle mb-0">
                    Upload your daily learner Excel sheet and import records in bulk.
                </p>
            </div>

            <div class="header-actions">
                <span class="today-badge">
                    <i class="fas fa-calendar-day"></i>
                    {{ now()->format('d M Y') }}
                </span>

                <a href="{{ route('learners.import.template') }}"
                   class="template-btn">
                    <i class="fas fa-download"></i>
                    Download Template
                </a>
            </div>
        </div>
    </x-slot>


    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- =====================================================
                 SUMMARY CARDS
            ====================================================== --}}
            <div class="import-summary-grid">

                <div class="summary-card">
                    <div class="summary-icon purple">
                        <i class="fas fa-users"></i>
                    </div>

                    <div>
                        <span class="summary-label">Imported Today</span>
                        <strong class="summary-value">
                            {{ $todayImported ?? 0 }}
                        </strong>
                    </div>
                </div>


                <div class="summary-card">
                    <div class="summary-icon green">
                        <i class="fas fa-check-circle"></i>
                    </div>

                    <div>
                        <span class="summary-label">Successful</span>
                        <strong class="summary-value">
                            {{ $todaySuccessful ?? 0 }}
                        </strong>
                    </div>
                </div>


                <div class="summary-card">
                    <div class="summary-icon orange">
                        <i class="fas fa-copy"></i>
                    </div>

                    <div>
                        <span class="summary-label">Duplicates</span>
                        <strong class="summary-value">
                            {{ $todayDuplicates ?? 0 }}
                        </strong>
                    </div>
                </div>


                <div class="summary-card">
                    <div class="summary-icon red">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>

                    <div>
                        <span class="summary-label">Failed</span>
                        <strong class="summary-value">
                            {{ $todayFailed ?? 0 }}
                        </strong>
                    </div>
                </div>

            </div>


            {{-- =====================================================
                 MAIN IMPORT CARD
            ====================================================== --}}
            <div class="import-main-card">

                <div class="import-card-header">

                    <div>
                        <h3 class="premium-section-title mb-1">
                            Upload Learner Excel
                        </h3>

                        <p class="premium-section-text mb-0">
                            Select the Excel file containing today's learner records.
                        </p>
                    </div>

                    <div class="secure-upload">
                        <i class="fas fa-shield-alt"></i>
                        Secure Import
                    </div>

                </div>


                {{-- FORM --}}
                <form 
                      action="{{ route('learners.import.preview') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      id="learnerImportForm">

                    @csrf

                                        {{-- PROGRAM --}}
                    <div class="program-select-wrapper">

                        <label for="program_code" class="program-label">
                            <i class="fas fa-graduation-cap"></i>
                            Select Program
                            <span>*</span>
                        </label>

                        <select name="program_code"
                                id="program_code"
                                class="program-select"
                                required>

                            <option value="">Select Program</option>

                            <option value="NMDC"
                                {{ old('program_code') === 'NMDC' ? 'selected' : '' }}>
                                NMDC
                            </option>

                            {{-- Future Programs --}}
                            {{-- 
                            <option value="PROGRAM_2">Program 2</option>
                            <option value="PROGRAM_3">Program 3</option>
                            --}}

                        </select>

                        @error('program_code')
                            <div class="field-error">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- DROPZONE --}}
                    <div class="upload-zone" id="uploadZone">

                        <input type="file"
                               name="excel_file"
                               id="excelFile"
                               accept=".xlsx,.xls,.csv"
                               hidden
                               required>

                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>

                        <h4 id="uploadTitle">
                            Drag & Drop your Excel file here
                        </h4>

                        <p id="uploadDescription">
                            or
                            <button type="button"
                                    class="browse-btn"
                                    id="browseBtn">
                                Browse File
                            </button>
                        </p>

                        <div class="upload-info">
                            <span>
                                <i class="fas fa-file-excel"></i>
                                XLSX / XLS 
                            </span>

                            <span>
                                <i class="fas fa-weight-hanging"></i>
                                Maximum 10 MB
                            </span>
                        </div>

                    </div>


                    {{-- SELECTED FILE --}}
                    <div class="selected-file" id="selectedFile" style="display:none;">

                        <div class="selected-file-icon">
                            <i class="fas fa-file-excel"></i>
                        </div>

                        <div class="selected-file-info">
                            <strong id="fileName">Learners.xlsx</strong>

                            <span id="fileSize">
                                0 KB
                            </span>
                        </div>

                        <button type="button"
                                class="remove-file"
                                id="removeFile">
                            <i class="fas fa-times"></i>
                        </button>

                    </div>


                    {{-- IMPORT BUTTON --}}
                    <div class="import-action-row">

                        <div class="import-note">
                            <i class="fas fa-info-circle"></i>

                            <span>
                                Existing learners will not be duplicated.
                                Invalid records will be reported after validation.
                            </span>
                        </div>

                        <button type="submit"
                                class="import-btn"
                                id="importBtn">

                            <i class="fas fa-upload"></i>
                            Analyse & Preview
                        </button>

                    </div>

                </form>

            </div>


            {{-- =====================================================
                 FORMAT + GUIDELINES
            ====================================================== --}}
            <div class="information-grid">

                {{-- Excel Format --}}
                <div class="info-card">

                    <div class="info-card-header">

                        <div class="info-card-icon purple">
                            <i class="fas fa-table"></i>
                        </div>

                        <div>
                            <h4>Excel Format</h4>
                            <p>Required learner columns</p>
                        </div>

                    </div>


                    <div class="column-list">

                    <div class="column-item required">
                        <span>
                            <i class="fas fa-id-card"></i>
                            candidate_id
                        </span>
                        <b>Required</b>
                    </div>

                    <div class="column-item required">
                        <span>
                            <i class="fas fa-building"></i>
                            TP NAME
                        </span>
                        <b>Required</b>
                    </div>

                    <div class="column-item required">
                        <span>
                            <i class="fas fa-user"></i>
                            first_name
                        </span>
                        <b>Required</b>
                    </div>

                    <div class="column-item required">
                        <span>
                            <i class="fas fa-user"></i>
                            last_name
                        </span>
                        <b>Required</b>
                    </div>

                    <div class="column-item required">
                        <span>
                            <i class="fas fa-venus-mars"></i>
                            gender
                        </span>
                        <b>Required</b>
                    </div>

                    <div class="column-item required">
                        <span>
                            <i class="fas fa-map-marker-alt"></i>
                            state
                        </span>
                        <b>Required</b>
                    </div>

                    <div class="column-item required">
                        <span>
                            <i class="fas fa-map"></i>
                            district
                        </span>
                        <b>Required</b>
                    </div>

                    <div class="column-item required">
                        <span>
                            <i class="fas fa-mobile-alt"></i>
                            mobile_number
                        </span>
                        <b>Required</b>
                    </div>

                    <div class="column-item required">
                        <span>
                            <i class="fas fa-envelope"></i>
                            email
                        </span>
                        <b>Required</b>
                    </div>

                    <div class="column-item">
                        <span>
                            <i class="fas fa-certificate"></i>
                            iscertified
                        </span>
                        <b>Optional</b>
                    </div>

                </div>

                </div>


                {{-- Guidelines --}}
                <div class="info-card">

                    <div class="info-card-header">

                        <div class="info-card-icon green">
                            <i class="fas fa-lightbulb"></i>
                        </div>

                        <div>
                            <h4>Import Guidelines</h4>
                            <p>Before uploading your file</p>
                        </div>

                    </div>


                    <div class="guideline-list">

                        <div class="guideline">
                            <span class="guideline-check">
                                <i class="fas fa-check"></i>
                            </span>

                            <span>
                                Keep the first row as column headers.
                            </span>
                        </div>

                        <div class="guideline">
                            <span class="guideline-check">
                                <i class="fas fa-check"></i>
                            </span>

                            <span>
                                One learner should be entered per row.
                            </span>
                        </div>

                        <div class="guideline">
                            <span class="guideline-check">
                                <i class="fas fa-check"></i>
                            </span>

                            <span>
                                Email should be unique for each learner.
                            </span>
                        </div>

                        <div class="guideline">
                            <span class="guideline-check">
                                <i class="fas fa-check"></i>
                            </span>

                            <span>
                                Duplicate learners will be automatically skipped.
                            </span>
                        </div>

                        <div class="guideline">
                            <span class="guideline-check">
                                <i class="fas fa-check"></i>
                            </span>

                            <span>
                                Invalid records will be shown in the import report.
                            </span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 RECENT IMPORTS
            ====================================================== --}}
            <div class="recent-import-card">

                <div class="recent-header">

                    <div>
                        <h3 class="premium-section-title mb-1">
                            Recent Imports
                        </h3>

                        <p class="premium-section-text mb-0">
                            Your latest learner import activity.
                        </p>
                    </div>

                    <a href="{{ route('learners.import.history') }}"
                       class="view-history-btn">
                        View History
                        <i class="fas fa-arrow-right"></i>
                    </a>

                </div>


                <div class="table-responsive">

                    <table class="premium-table align-middle mb-0">

                        <thead>
                            <tr>
                                <th>File</th>
                                <th>Date & Time</th>
                                <th>Total</th>
                                <th>Success</th>
                                <th>Duplicate</th>
                                <th>Failed</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($recentImports ?? [] as $import)

                                <tr>

                                    <td>
                                        <div class="file-cell">

                                            <div class="mini-file-icon">
                                                <i class="fas fa-file-excel"></i>
                                            </div>

                                            <div>
                                                <strong>
                                                    {{ $import->file_name }}
                                                </strong>

                                                <small>
                                                    {{ $import->uploaded_by ?? 'Admin' }}
                                                </small>
                                            </div>

                                        </div>
                                    </td>

                                    <td>
                                        <span class="muted-text">
                                            {{ $import->created_at }}
                                        </span>
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $import->total_records ?? 0 }}
                                        </strong>
                                    </td>

                                    <td>
                                        <span class="result-badge success">
                                            {{ $import->success_count ?? 0 }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="result-badge duplicate">
                                            {{ $import->duplicate_count ?? 0 }}
                                        </span>
                                    </td>

                                    <td>
                                        <span class="result-badge failed">
                                            {{ $import->failed_count ?? 0 }}
                                        </span>
                                    </td>

                                    <td>

                                        @if(($import->status ?? '') === 'completed')

                                            <span class="import-status completed">
                                                <i class="fas fa-check-circle"></i>
                                                Completed
                                            </span>

                                        @elseif(($import->status ?? '') === 'processing')

                                            <span class="import-status processing">
                                                <i class="fas fa-sync-alt"></i>
                                                Processing
                                            </span>

                                        @else

                                            <span class="import-status failed">
                                                <i class="fas fa-times-circle"></i>
                                                Failed
                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7">

                                        <div class="empty-import">

                                            <div class="empty-import-icon">
                                                <i class="fas fa-file-upload"></i>
                                            </div>

                                            <h4>No Imports Yet</h4>

                                            <p>
                                                Your learner import history will appear here.
                                            </p>

                                        </div>

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>
    </div>


    {{-- =========================================================
     IMPORT PREVIEW
========================================================= --}}

@if(session('preview_ready') && session('learner_import_preview'))

    @php
        $preview = session('learner_import_preview');
        $summary = $preview['summary'] ?? [];
    @endphp

    <div class="import-preview-card">

        <div class="import-preview-header">

            <div>
                <div class="import-preview-title">
                    <i class="fas fa-chart-bar"></i>
                    Excel Analysis Complete
                </div>

                <div class="import-preview-file">
                    <i class="fas fa-file-excel"></i>
                    {{ $preview['file_name'] ?? 'Excel File' }}
                </div>
            </div>

            <div class="preview-program">
                <span>Program</span>
                <strong>
                    {{ $preview['program_code'] ?? '-' }}
                </strong>
            </div>

        </div>


        {{-- SUMMARY --}}
        <div class="preview-summary-grid">

            <div class="preview-stat total">
                <span class="preview-stat-icon">
                    <i class="fas fa-list"></i>
                </span>

                <div>
                    <small>Total Records</small>
                    <strong>
                        {{ number_format($summary['total'] ?? 0) }}
                    </strong>
                </div>
            </div>


            <div class="preview-stat success">
                <span class="preview-stat-icon">
                    <i class="fas fa-check-circle"></i>
                </span>

                <div>
                    <small>Valid Records</small>
                    <strong>
                        {{ number_format($summary['successful'] ?? 0) }}
                    </strong>
                </div>
            </div>


            <div class="preview-stat duplicate">
                <span class="preview-stat-icon">
                    <i class="fas fa-copy"></i>
                </span>

                <div>
                    <small>Duplicates</small>
                    <strong>
                        {{ number_format($summary['duplicates'] ?? 0) }}
                    </strong>
                </div>
            </div>


            <div class="preview-stat failed">
                <span class="preview-stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </span>

                <div>
                    <small>Failed</small>
                    <strong>
                        {{ number_format($summary['failed'] ?? 0) }}
                    </strong>
                </div>
            </div>

        </div>


        {{-- WARNING --}}
        @if(($summary['failed'] ?? 0) > 0)

            <div class="preview-warning">

                <i class="fas fa-exclamation-circle"></i>

                <div>
                    <strong>
                        Some records require attention.
                    </strong>

                    <p>
                        Failed records will not be imported.
                        Please review the failed records before confirming.
                    </p>
                </div>

            </div>

        @endif


        {{-- ACTIONS --}}
        <div class="preview-actions">

            <form
                action="{{ route('learners.import.confirm') }}"
                method="POST"
            >
                @csrf

                <button
                    type="submit"
                    class="btn-confirm-import"
                    onclick="return confirm('Are you sure you want to import the valid learners?')"
                >
                    <i class="fas fa-database"></i>
                    Confirm & Import
                </button>
            </form>


            <form
                action="{{ route('learners.import.cancel') }}"
                method="POST"
            >
                @csrf

                <button
                    type="submit"
                    class="btn-cancel-import"
                >
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
            </form>

        </div>

    </div>

@endif

<style>
    /* =========================================================
   IMPORT PREVIEW
========================================================= */

.import-preview-card {
    margin-top: 24px;
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
}

.import-preview-header {
    padding: 22px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    border-bottom: 1px solid #eef0f3;
}

.import-preview-title {
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
}

.import-preview-title i {
    margin-right: 8px;
}

.import-preview-file {
    margin-top: 6px;
    color: #6b7280;
    font-size: 13px;
}

.import-preview-file i {
    margin-right: 6px;
}

.preview-program {
    text-align: right;
}

.preview-program span {
    display: block;
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 3px;
}

.preview-program strong {
    font-size: 15px;
    color: #111827;
}


/* =========================================================
   PREVIEW SUMMARY
========================================================= */

.preview-summary-grid {
    padding: 22px 24px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

.preview-stat {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border-radius: 12px;
    border: 1px solid #edf0f2;
    background: #fafafa;
}

.preview-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;

    display: flex;
    align-items: center;
    justify-content: center;

    background: #f1f3f5;
}

.preview-stat small {
    display: block;
    color: #6b7280;
    font-size: 12px;
    margin-bottom: 3px;
}

.preview-stat strong {
    display: block;
    color: #111827;
    font-size: 21px;
}


/* =========================================================
   WARNING
========================================================= */

.preview-warning {
    margin: 0 24px 20px;
    padding: 14px 16px;

    display: flex;
    align-items: flex-start;
    gap: 12px;

    border: 1px solid #f0d9a6;
    border-radius: 10px;
    background: #fffaf0;
}

.preview-warning > i {
    margin-top: 2px;
}

.preview-warning strong {
    display: block;
    font-size: 13px;
    margin-bottom: 3px;
}

.preview-warning p {
    margin: 0;
    font-size: 12px;
    color: #6b7280;
}


/* =========================================================
   ACTIONS
========================================================= */

.preview-actions {
    padding: 18px 24px;

    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;

    border-top: 1px solid #eef0f3;
}

.preview-actions form {
    margin: 0;
}

.btn-confirm-import,
.btn-cancel-import {
    border: 0;
    border-radius: 8px;
    padding: 11px 18px;

    font-size: 13px;
    font-weight: 600;

    cursor: pointer;
}

.btn-confirm-import {
    background: #198754;
    color: #ffffff;
}

.btn-cancel-import {
    background: #f1f3f5;
    color: #495057;
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media (max-width: 900px) {

    .preview-summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 600px) {

    .import-preview-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .preview-program {
        text-align: left;
    }

    .preview-summary-grid {
        grid-template-columns: 1fr;
    }

    .preview-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .preview-actions form,
    .btn-confirm-import,
    .btn-cancel-import {
        width: 100%;
    }

}
</style>

    <style>

        /* =========================================================
           HEADER
        ========================================================= */

        .learner-import-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .today-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 11px 15px;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 13px;
            font-weight: 700;
        }

        .template-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 16px;
            border-radius: 14px;
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            box-shadow: 0 10px 22px rgba(79,70,229,.18);
            transition: .25s ease;
        }

        .template-btn:hover {
            color: #fff;
            transform: translateY(-1px);
        }


        /* =========================================================
           SUMMARY
        ========================================================= */

        .import-summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 22px;
        }

        .summary-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            border-radius: 20px;
            background: rgba(255,255,255,.9);
            border: 1px solid rgba(148,163,184,.15);
            box-shadow: 0 12px 30px rgba(15,23,42,.06);
        }

        .summary-icon {
            width: 48px;
            height: 48px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .summary-icon.purple,
        .info-card-icon.purple {
            background: rgba(99,102,241,.12);
            color: #4f46e5;
        }

        .summary-icon.green,
        .info-card-icon.green {
            background: rgba(16,185,129,.12);
            color: #059669;
        }

        .summary-icon.orange {
            background: rgba(245,158,11,.12);
            color: #d97706;
        }

        .summary-icon.red {
            background: rgba(239,68,68,.11);
            color: #dc2626;
        }

        .summary-label {
            display: block;
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .summary-value {
            display: block;
            color: #0f172a;
            font-size: 22px;
            font-weight: 800;
        }


        /* =========================================================
           MAIN IMPORT CARD
        ========================================================= */

        .import-main-card,
        .recent-import-card,
        .info-card {
            background: rgba(255,255,255,.9);
            border: 1px solid rgba(148,163,184,.15);
            border-radius: 26px;
            box-shadow: 0 16px 40px rgba(15,23,42,.07);
            overflow: hidden;
        }

        .import-main-card {
            margin-bottom: 22px;
        }

        .import-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 24px 28px;
            border-bottom: 1px solid rgba(148,163,184,.12);
            background: linear-gradient(
                180deg,
                rgba(255,255,255,.8),
                rgba(248,250,252,.8)
            );
        }

        .secure-upload {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(16,185,129,.1);
            color: #047857;
            font-size: 12px;
            font-weight: 800;
        }


        /* =========================================================
           UPLOAD ZONE
        ========================================================= */

        .upload-zone {
            margin: 26px 28px 18px;
            padding: 48px 25px;
            text-align: center;
            border: 2px dashed #cbd5e1;
            border-radius: 22px;
            background: linear-gradient(
                135deg,
                #f8fafc,
                #f5f3ff
            );
            cursor: pointer;
            transition: .25s ease;
        }

        .upload-zone:hover,
        .upload-zone.dragover {
            border-color: #6366f1;
            background: #eef2ff;
            transform: translateY(-1px);
        }

        .upload-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto 16px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #4f46e5;
            background: rgba(99,102,241,.12);
        }

        .upload-zone h4 {
            margin: 0 0 7px;
            color: #0f172a;
            font-size: 17px;
            font-weight: 800;
        }

        .upload-zone p {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 18px;
        }

        .browse-btn {
            border: 0;
            background: transparent;
            color: #4f46e5;
            font-weight: 800;
            cursor: pointer;
        }

        .upload-info {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
        }

        .upload-info span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }


        /* =========================================================
           SELECTED FILE
        ========================================================= */

        .selected-file {
            margin: 0 28px 20px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 16px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .selected-file-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(16,185,129,.12);
            color: #059669;
        }

        .selected-file-info {
            flex: 1;
            min-width: 0;
        }

        .selected-file-info strong {
            display: block;
            color: #0f172a;
            font-size: 13px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .selected-file-info span {
            color: #64748b;
            font-size: 11px;
        }

        .remove-file {
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 10px;
            background: rgba(239,68,68,.1);
            color: #dc2626;
            cursor: pointer;
        }


        /* =========================================================
           ACTION
        ========================================================= */

        .import-action-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 18px 28px 26px;
        }

        .import-note {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            color: #64748b;
            font-size: 12px;
            max-width: 620px;
            line-height: 1.5;
        }

        .import-note i {
            color: #6366f1;
            margin-top: 2px;
        }

        .import-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 180px;
            padding: 13px 20px;
            border: 0;
            border-radius: 14px;
            color: #fff;
            background: linear-gradient(135deg,#4f46e5,#7c3aed);
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 12px 25px rgba(79,70,229,.2);
            transition: .25s ease;
        }

        .import-btn:hover {
            transform: translateY(-1px);
        }


        /* =========================================================
           INFORMATION
        ========================================================= */

        .information-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
            margin-bottom: 22px;
        }

        .info-card {
            padding: 24px;
        }

        .info-card-header {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 20px;
        }

        .info-card-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-card-header h4 {
            color: #0f172a;
            font-size: 15px;
            font-weight: 800;
            margin: 0 0 3px;
        }

        .info-card-header p {
            color: #64748b;
            font-size: 12px;
            margin: 0;
        }


        /* =========================================================
           COLUMNS
        ========================================================= */

        .column-list {
            display: grid;
            gap: 8px;
        }

        .column-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 11px 13px;
            border-radius: 12px;
            background: #f8fafc;
            color: #475569;
            font-size: 12px;
        }

        .column-item span {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .column-item i {
            width: 17px;
            text-align: center;
            color: #64748b;
        }

        .column-item b {
            font-size: 10px;
            text-transform: uppercase;
            color: #94a3b8;
        }

        .column-item.required b {
            color: #dc2626;
        }


        /* =========================================================
           GUIDELINES
        ========================================================= */

        .guideline-list {
            display: grid;
            gap: 13px;
        }

        .guideline {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: #475569;
            font-size: 13px;
            line-height: 1.5;
        }

        .guideline-check {
            width: 20px;
            height: 20px;
            flex: 0 0 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(16,185,129,.12);
            color: #059669;
            font-size: 9px;
        }


        /* =========================================================
           RECENT IMPORTS
        ========================================================= */

        .recent-import-card {
            overflow: hidden;
        }

        .recent-header {
            padding: 24px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            border-bottom: 1px solid rgba(148,163,184,.12);
        }

        .view-history-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #4f46e5;
            text-decoration: none;
            font-size: 12px;
            font-weight: 800;
        }

        .premium-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .premium-table thead th {
            background: #f8fafc;
            color: #475569;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: 15px 18px;
            white-space: nowrap;
        }

        .premium-table tbody td {
            padding: 15px 18px;
            border-top: 1px solid rgba(148,163,184,.1);
            background: #fff;
            font-size: 13px;
        }

        .premium-table tbody tr:hover td {
            background: #f8fafc;
        }

        .file-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mini-file-icon {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(16,185,129,.1);
            color: #059669;
        }

        .file-cell strong {
            display: block;
            color: #0f172a;
            font-size: 13px;
        }

        .file-cell small {
            color: #94a3b8;
            font-size: 10px;
        }

        .muted-text {
            color: #64748b;
            font-size: 12px;
        }


        /* =========================================================
           RESULT BADGES
        ========================================================= */

        .result-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 35px;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
        }

        .result-badge.success {
            background: rgba(16,185,129,.1);
            color: #047857;
        }

        .result-badge.duplicate {
            background: rgba(245,158,11,.1);
            color: #b45309;
        }

        .result-badge.failed {
            background: rgba(239,68,68,.1);
            color: #b91c1c;
        }

        .import-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
        }

        .import-status.completed {
            background: rgba(16,185,129,.1);
            color: #047857;
        }

        .import-status.processing {
            background: rgba(99,102,241,.1);
            color: #4338ca;
        }

        .import-status.failed {
            background: rgba(239,68,68,.1);
            color: #b91c1c;
        }


        /* =========================================================
           EMPTY
        ========================================================= */

        .empty-import {
            text-align: center;
            padding: 45px 20px;
        }

        .empty-import-icon {
            width: 58px;
            height: 58px;
            margin: 0 auto 12px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: #94a3b8;
            font-size: 22px;
        }

        .empty-import h4 {
            margin: 0 0 5px;
            color: #0f172a;
            font-weight: 800;
        }

        .empty-import p {
            margin: 0;
            color: #64748b;
            font-size: 12px;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 992px) {

            .import-summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .information-grid {
                grid-template-columns: 1fr;
            }

        }


        @media (max-width: 768px) {

            .learner-import-header,
            .import-card-header,
            .recent-header,
            .import-action-row {
                align-items: flex-start;
                flex-direction: column;
            }

            .header-actions {
                width: 100%;
            }

            .template-btn,
            .today-badge {
                justify-content: center;
            }

            .import-summary-grid {
                grid-template-columns: 1fr 1fr;
            }

            .upload-zone {
                margin: 20px 16px;
                padding: 35px 15px;
            }

            .upload-info {
                flex-direction: column;
                gap: 7px;
            }

            .import-action-row {
                padding: 15px 18px 22px;
            }

            .import-btn {
                width: 100%;
            }

        }


        @media (max-width: 480px) {

            .import-summary-grid {
                grid-template-columns: 1fr;
            }

            .header-actions {
                flex-direction: column;
                align-items: stretch;
            }

        }

    </style>
    <style>
        /* =========================================================
   PROGRAM SELECT
========================================================= */

.program-select-wrapper {
    margin: 24px 28px 0;
}

.program-label {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 8px;

    color: #334155;
    font-size: 13px;
    font-weight: 800;
}

.program-label i {
    color: #4f46e5;
}

.program-label span {
    color: #dc2626;
}

.program-select {
    width: 100%;
    height: 46px;

    padding: 0 14px;

    border: 1px solid #dbe3ee;
    border-radius: 13px;

    background: #fff;

    color: #0f172a;
    font-size: 13px;
    font-weight: 600;

    outline: none;

    transition: .2s ease;
}

.program-select:focus {
    border-color: #6366f1;

    box-shadow:
        0 0 0 3px rgba(99,102,241,.10);
}

.field-error {
    display: flex;
    align-items: center;
    gap: 6px;

    margin-top: 7px;

    color: #dc2626;
    font-size: 11px;
    font-weight: 600;
}
    </style>


    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const fileInput = document.getElementById('excelFile');
            const uploadZone = document.getElementById('uploadZone');
            const browseBtn = document.getElementById('browseBtn');
            const selectedFile = document.getElementById('selectedFile');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            const removeFile = document.getElementById('removeFile');
            const uploadTitle = document.getElementById('uploadTitle');
            const uploadDescription = document.getElementById('uploadDescription');

            browseBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                fileInput.click();
            });

            uploadZone.addEventListener('click', function () {
                fileInput.click();
            });


            fileInput.addEventListener('change', function () {

                if (this.files.length) {
                    showSelectedFile(this.files[0]);
                }

            });


            uploadZone.addEventListener('dragover', function (e) {

                e.preventDefault();

                uploadZone.classList.add('dragover');

            });


            uploadZone.addEventListener('dragleave', function () {

                uploadZone.classList.remove('dragover');

            });


            uploadZone.addEventListener('drop', function (e) {

                e.preventDefault();

                uploadZone.classList.remove('dragover');

                if (e.dataTransfer.files.length) {

                    const file = e.dataTransfer.files[0];

                    const allowedExtensions = [
                        'xlsx',
                        'xls'
                    ];

                    const extension = file.name
                        .split('.')
                        .pop()
                        .toLowerCase();

                    if (!allowedExtensions.includes(extension)) {

                        alert('Please upload an Excel file.');

                        return;
                    }

                    fileInput.files = e.dataTransfer.files;

                    showSelectedFile(file);
                }

            });


            removeFile.addEventListener('click', function () {

                fileInput.value = '';

                selectedFile.style.display = 'none';

                uploadZone.style.display = 'block';

            });


            function showSelectedFile(file) {

                fileName.textContent = file.name;

                fileSize.textContent = formatFileSize(file.size);

                selectedFile.style.display = 'flex';

                uploadZone.style.display = 'none';

            }


            function formatFileSize(bytes) {

                if (bytes === 0) return '0 KB';

                const units = [
                    'Bytes',
                    'KB',
                    'MB',
                    'GB'
                ];

                const i = Math.floor(
                    Math.log(bytes) / Math.log(1024)
                );

                return (
                    bytes / Math.pow(1024, i)
                ).toFixed(1) + ' ' + units[i];

            }

        });

    </script>

</x-app-layout>

