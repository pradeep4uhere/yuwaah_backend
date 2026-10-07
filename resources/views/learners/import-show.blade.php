<x-app-layout>

    <x-slot name="header">
        <div class="li-header">
            <div>
                <div class="li-header-eyebrow">
                    <i class="fas fa-file-import"></i>
                    Learner Management
                </div>

                <h2 class="li-header-title">
                    Import Details
                </h2>

                <p class="li-header-subtitle">
                    Review the complete result and processing details of this learner import.
                </p>
            </div>

            <a href="{{ route('learners.import.history') }}"
               class="li-back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Import History</span>
            </a>
        </div>
    </x-slot>


    <style>

        /* =========================================================
           LEARNER IMPORT DETAILS
        ========================================================= */

        .li-page {
            max-width: 1400px;
            margin: 0 auto;
            padding: 28px 24px 50px;
        }

        /* =========================================================
           HEADER
        ========================================================= */

        .li-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .li-header-eyebrow {
            display: flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 5px;

            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;

            color: #64748b;
        }

        .li-header-title {
            margin: 0;

            font-size: 24px;
            line-height: 1.25;
            font-weight: 750;

            color: #0f172a;
        }

        .li-header-subtitle {
            margin: 7px 0 0;

            font-size: 13px;
            color: #64748b;
        }

        .li-back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            min-height: 40px;
            padding: 0 15px;

            border: 1px solid #e2e8f0;
            border-radius: 8px;

            background: #ffffff;
            color: #334155;

            text-decoration: none;

            font-size: 13px;
            font-weight: 650;

            transition:
                background .18s ease,
                border-color .18s ease,
                color .18s ease,
                box-shadow .18s ease;
        }

        .li-back-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;

            box-shadow: 0 2px 8px rgba(15, 23, 42, .06);
        }


        /* =========================================================
           MAIN CARD
        ========================================================= */

        .li-main-card {
            margin-top: 24px;

            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;

            box-shadow:
                0 1px 2px rgba(15, 23, 42, .03),
                0 4px 18px rgba(15, 23, 42, .035);

            overflow: hidden;
        }


        /* =========================================================
           FILE SUMMARY
        ========================================================= */

        .li-file-summary {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;

            padding: 22px 24px;

            border-bottom: 1px solid #eef2f7;
            background: #fcfdff;
        }

        .li-file-info {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .li-file-icon {
            width: 46px;
            height: 46px;

            flex: 0 0 46px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 10px;

            background: #eff6ff;
            color: #2563eb;

            font-size: 19px;
        }

        .li-file-details {
            min-width: 0;
        }

        .li-file-name {
            margin: 0;

            font-size: 15px;
            font-weight: 700;

            color: #0f172a;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .li-file-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 7px;

            margin-top: 5px;

            font-size: 12px;
            color: #64748b;
        }

        .li-meta-dot {
            color: #cbd5e1;
        }


        /* =========================================================
           STATUS BADGES
        ========================================================= */

        .li-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;

            padding: 6px 11px;

            border-radius: 999px;

            font-size: 11px;
            font-weight: 750;
            letter-spacing: .01em;

            white-space: nowrap;
        }

        .li-status-completed {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #d1fae5;
        }

        .li-status-processing {
            background: #fffbeb;
            color: #b45309;
            border: 1px solid #fef3c7;
        }

        .li-status-failed {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fee2e2;
        }


        /* =========================================================
           STATISTICS
        ========================================================= */

        .li-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));

            gap: 14px;

            padding: 20px 24px;

            border-bottom: 1px solid #eef2f7;
        }

        .li-stat {
            position: relative;

            padding: 17px 18px;

            border: 1px solid #e8edf3;
            border-radius: 10px;

            background: #ffffff;

            transition:
                transform .18s ease,
                box-shadow .18s ease,
                border-color .18s ease;
        }

        .li-stat:hover {
            transform: translateY(-1px);

            border-color: #dbe3ec;

            box-shadow: 0 5px 18px rgba(15, 23, 42, .06);
        }

        .li-stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .li-stat-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .045em;

            color: #64748b;
        }

        .li-stat-icon {
            width: 30px;
            height: 30px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 7px;

            font-size: 12px;
        }

        .li-stat-value {
            margin-top: 10px;

            font-size: 24px;
            line-height: 1;

            font-weight: 750;

            color: #0f172a;
        }

        .li-stat-total .li-stat-icon {
            background: #eff6ff;
            color: #2563eb;
        }

        .li-stat-success .li-stat-icon {
            background: #ecfdf5;
            color: #059669;
        }

        .li-stat-success .li-stat-value {
            color: #047857;
        }

        .li-stat-duplicate .li-stat-icon {
            background: #fffbeb;
            color: #d97706;
        }

        .li-stat-duplicate .li-stat-value {
            color: #b45309;
        }

        .li-stat-failed .li-stat-icon {
            background: #fef2f2;
            color: #dc2626;
        }

        .li-stat-failed .li-stat-value {
            color: #b91c1c;
        }


        /* =========================================================
           CONTENT GRID
        ========================================================= */

        .li-content {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 330px;

            gap: 20px;

            padding: 24px;
        }

        .li-section {
            border: 1px solid #e5e7eb;
            border-radius: 10px;

            background: #ffffff;

            overflow: hidden;
        }

        .li-section-header {
            display: flex;
            align-items: center;
            gap: 9px;

            padding: 16px 18px;

            border-bottom: 1px solid #eef2f7;

            background: #fcfdff;
        }

        .li-section-header i {
            width: 28px;
            height: 28px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 7px;

            background: #eff6ff;
            color: #2563eb;

            font-size: 12px;
        }

        .li-section-title {
            margin: 0;

            font-size: 13px;
            font-weight: 750;

            color: #0f172a;
        }

        .li-section-body {
            padding: 4px 18px 10px;
        }


        /* =========================================================
           INFORMATION ROWS
        ========================================================= */

        .li-info-row {
            display: grid;
            grid-template-columns: 160px minmax(0, 1fr);

            gap: 20px;

            padding: 13px 0;

            border-bottom: 1px solid #f1f5f9;
        }

        .li-info-row:last-child {
            border-bottom: 0;
        }

        .li-info-label {
            font-size: 12px;
            font-weight: 650;

            color: #64748b;
        }

        .li-info-value {
            min-width: 0;

            font-size: 13px;
            font-weight: 600;

            color: #334155;

            word-break: break-word;
        }

        .li-program-badge {
            display: inline-flex;
            align-items: center;

            padding: 4px 9px;

            border-radius: 6px;

            background: #eff6ff;
            color: #2563eb;

            font-size: 11px;
            font-weight: 750;
        }


        /* =========================================================
           TIMELINE
        ========================================================= */

        .li-timeline {
            padding: 8px 18px 18px;
        }

        .li-timeline-item {
            position: relative;

            display: flex;
            gap: 12px;

            padding: 10px 0;
        }

        .li-timeline-item:not(:last-child)::before {
            content: "";

            position: absolute;

            left: 6px;
            top: 25px;
            bottom: -8px;

            width: 1px;

            background: #e2e8f0;
        }

        .li-timeline-dot {
            position: relative;
            z-index: 1;

            width: 13px;
            height: 13px;

            flex: 0 0 13px;

            margin-top: 3px;

            border-radius: 50%;

            background: #2563eb;

            box-shadow: 0 0 0 4px #eff6ff;
        }

        .li-timeline-dot.success {
            background: #059669;
            box-shadow: 0 0 0 4px #ecfdf5;
        }

        .li-timeline-content {
            min-width: 0;
        }

        .li-timeline-label {
            font-size: 11px;
            font-weight: 650;

            color: #64748b;
        }

        .li-timeline-value {
            margin-top: 2px;

            font-size: 12px;
            font-weight: 650;

            color: #334155;
        }


        /* =========================================================
           ERROR
        ========================================================= */

        .li-error-section {
            margin: 0 24px 24px;

            border: 1px solid #fecaca;
            border-radius: 10px;

            background: #fffafa;

            overflow: hidden;
        }

        .li-error-header {
            display: flex;
            align-items: center;
            gap: 9px;

            padding: 14px 17px;

            background: #fef2f2;

            border-bottom: 1px solid #fecaca;
        }

        .li-error-header i {
            color: #dc2626;
        }

        .li-error-title {
            margin: 0;

            font-size: 13px;
            font-weight: 750;

            color: #991b1b;
        }

        .li-error-body {
            padding: 15px 17px;

            font-size: 12px;
            line-height: 1.6;

            color: #991b1b;

            white-space: pre-wrap;
            word-break: break-word;
        }


        /* =========================================================
           ACTIONS
        ========================================================= */

        .li-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 9px;

            padding: 18px 24px;

            border-top: 1px solid #eef2f7;

            background: #fcfdff;
        }

        .li-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;

            min-height: 38px;
            padding: 0 13px;

            border-radius: 7px;

            font-size: 12px;
            font-weight: 700;

            text-decoration: none;

            transition:
                background .18s ease,
                border-color .18s ease,
                color .18s ease,
                box-shadow .18s ease;
        }

        .li-action-secondary {
            border: 1px solid #e2e8f0;

            background: #ffffff;
            color: #334155;
        }

        .li-action-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .li-action-danger {
            border: 1px solid #fecaca;

            background: #fff;
            color: #b91c1c;
        }

        .li-action-danger:hover {
            background: #fef2f2;
            border-color: #fca5a5;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1100px) {

            .li-content {
                grid-template-columns: 1fr;
            }

        }

        @media (max-width: 800px) {

            .li-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .li-back-btn {
                width: 100%;
            }

            .li-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

        }

        @media (max-width: 600px) {

            .li-page {
                padding: 18px 14px 35px;
            }

            .li-file-summary {
                align-items: flex-start;
                flex-direction: column;
            }

            .li-stats {
                grid-template-columns: 1fr;
                padding: 14px;
            }

            .li-content {
                padding: 14px;
            }

            .li-info-row {
                grid-template-columns: 1fr;
                gap: 4px;
            }

            .li-error-section {
                margin: 0 14px 14px;
            }

            .li-actions {
                align-items: stretch;
                flex-direction: column;
                padding: 14px;
            }

            .li-action-btn {
                width: 100%;
            }

        }

    </style>


    <div class="li-page">

        {{-- =====================================================
             MAIN CARD
        ====================================================== --}}
        <div class="li-main-card">


            {{-- =================================================
                 FILE SUMMARY
            ================================================== --}}
            <div class="li-file-summary">

                <div class="li-file-info">

                    <div class="li-file-icon">
                        <i class="fas fa-file-excel"></i>
                    </div>

                    <div class="li-file-details">

                        <h3 class="li-file-name">
                            {{ $import->file_name }}
                        </h3>

                        <div class="li-file-meta">

                            <span>
                                Import #{{ $import->id }}
                            </span>

                            <span class="li-meta-dot">•</span>

                            <span>
                                {{ $import->created_at?->format('d M Y, h:i A') ?? '-' }}
                            </span>

                            @if($import->program_code)
                                <span class="li-meta-dot">•</span>

                                <span>
                                    {{ $import->program_code }}
                                </span>
                            @endif

                        </div>

                    </div>

                </div>


                {{-- STATUS --}}
                <div>

                    @if($import->status === 'completed')

                        <span class="li-status li-status-completed">
                            <i class="fas fa-check-circle"></i>
                            Completed
                        </span>

                    @elseif($import->status === 'processing')

                        <span class="li-status li-status-processing">
                            <i class="fas fa-spinner"></i>
                            Processing
                        </span>

                    @else

                        <span class="li-status li-status-failed">
                            <i class="fas fa-times-circle"></i>
                            Failed
                        </span>

                    @endif

                </div>

            </div>


            {{-- =================================================
                 STATISTICS
            ================================================== --}}
            <div class="li-stats">

                <div class="li-stat li-stat-total">

                    <div class="li-stat-top">

                        <span class="li-stat-label">
                            Total Records
                        </span>

                        <span class="li-stat-icon">
                            <i class="fas fa-layer-group"></i>
                        </span>

                    </div>

                    <div class="li-stat-value">
                        {{ number_format($import->total_records) }}
                    </div>

                </div>


                <div class="li-stat li-stat-success">

                    <div class="li-stat-top">

                        <span class="li-stat-label">
                            Successful
                        </span>

                        <span class="li-stat-icon">
                            <i class="fas fa-check"></i>
                        </span>

                    </div>

                    <div class="li-stat-value">
                        {{ number_format($import->success_count) }}
                    </div>

                </div>


                <div class="li-stat li-stat-duplicate">

                    <div class="li-stat-top">

                        <span class="li-stat-label">
                            Duplicates
                        </span>

                        <span class="li-stat-icon">
                            <i class="fas fa-copy"></i>
                        </span>

                    </div>

                    <div class="li-stat-value">
                        {{ number_format($import->duplicate_count) }}
                    </div>

                </div>


                <div class="li-stat li-stat-failed">

                    <div class="li-stat-top">

                        <span class="li-stat-label">
                            Failed
                        </span>

                        <span class="li-stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </span>

                    </div>

                    <div class="li-stat-value">
                        {{ number_format($import->failed_count) }}
                    </div>

                </div>

            </div>


            {{-- =================================================
                 CONTENT
            ================================================== --}}
            <div class="li-content">


                {{-- IMPORT INFORMATION --}}
                <div class="li-section">

                    <div class="li-section-header">

                        <i class="fas fa-info"></i>

                        <h3 class="li-section-title">
                            Import Information
                        </h3>

                    </div>


                    <div class="li-section-body">

                        <div class="li-info-row">

                            <div class="li-info-label">
                                File Name
                            </div>

                            <div class="li-info-value">
                                {{ $import->file_name }}
                            </div>

                        </div>


                        <div class="li-info-row">

                            <div class="li-info-label">
                                Program
                            </div>

                            <div class="li-info-value">

                                <span class="li-program-badge">
                                    {{ $import->program_code }}
                                </span>

                            </div>

                        </div>


                        <div class="li-info-row">

                            <div class="li-info-label">
                                Uploaded By
                            </div>

                            <div class="li-info-value">
                                {{ optional($import->uploader)->name ?? 'System' }}
                            </div>

                        </div>


                        <div class="li-info-row">

                            <div class="li-info-label">
                                Status
                            </div>

                            <div class="li-info-value">

                                @if($import->status === 'completed')

                                    <span class="li-status li-status-completed">
                                        <i class="fas fa-check-circle"></i>
                                        Completed
                                    </span>

                                @elseif($import->status === 'processing')

                                    <span class="li-status li-status-processing">
                                        <i class="fas fa-spinner"></i>
                                        Processing
                                    </span>

                                @else

                                    <span class="li-status li-status-failed">
                                        <i class="fas fa-times-circle"></i>
                                        Failed
                                    </span>

                                @endif

                            </div>

                        </div>


                        <div class="li-info-row">

                            <div class="li-info-label">
                                Started At
                            </div>

                            <div class="li-info-value">
                                {{ $import->started_at?->format('d M Y, h:i A') ?? '-' }}
                            </div>

                        </div>


                        <div class="li-info-row">

                            <div class="li-info-label">
                                Completed At
                            </div>

                            <div class="li-info-value">
                                {{ $import->completed_at?->format('d M Y, h:i A') ?? '-' }}
                            </div>

                        </div>


                        <div class="li-info-row">

                            <div class="li-info-label">
                                Created At
                            </div>

                            <div class="li-info-value">
                                {{ $import->created_at?->format('d M Y, h:i A') ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- PROCESSING TIMELINE --}}
                <div class="li-section">

                    <div class="li-section-header">

                        <i class="fas fa-history"></i>

                        <h3 class="li-section-title">
                            Processing Timeline
                        </h3>

                    </div>


                    <div class="li-timeline">

                        <div class="li-timeline-item">

                            <div class="li-timeline-dot"></div>

                            <div class="li-timeline-content">

                                <div class="li-timeline-label">
                                    Import Started
                                </div>

                                <div class="li-timeline-value">
                                    {{ $import->started_at?->format('d M Y, h:i A') ?? '-' }}
                                </div>

                            </div>

                        </div>


                        <div class="li-timeline-item">

                            <div class="li-timeline-dot success"></div>

                            <div class="li-timeline-content">

                                <div class="li-timeline-label">
                                    Import Completed
                                </div>

                                <div class="li-timeline-value">
                                    {{ $import->completed_at?->format('d M Y, h:i A') ?? 'Not completed' }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                 ERROR MESSAGE
            ================================================== --}}
            @if($import->error_message)

                <div class="li-error-section">

                    <div class="li-error-header">

                        <i class="fas fa-exclamation-triangle"></i>

                        <h3 class="li-error-title">
                            Import Error
                        </h3>

                    </div>

                    <div class="li-error-body">
                        {{ $import->error_message }}
                    </div>

                </div>

            @endif


            {{-- =================================================
                 ACTIONS
            ================================================== --}}
            <div class="li-actions">

                <a href="{{ route('learners.import.history') }}"
                   class="li-action-btn li-action-secondary">

                    <i class="fas fa-arrow-left"></i>
                    Back to History

                </a>


                @if($import->failed_count > 0)

                    <a href="{{ route('learners.import.failed', $import) }}"
                       class="li-action-btn li-action-danger">

                        <i class="fas fa-download"></i>
                        Download Failed Records

                    </a>

                @endif

            </div>


        </div>

    </div>

</x-app-layout>