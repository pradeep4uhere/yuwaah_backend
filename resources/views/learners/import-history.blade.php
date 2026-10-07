<x-app-layout>

    <x-slot name="header">
        <div class="learner-import-header">
            <div>
                <h2>Learner Import History</h2>
                <p>View previous learner Excel imports and their results.</p>
            </div>

            <div>
                <a href="{{ route('learners.import.form') }}"
                   class="cp-btn cp-btn-primary">
                    <i class="fas fa-file-import"></i>
                    New Import
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .import-history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .import-history-table th,
        .import-history-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .import-history-table th {
            background: #f8fafc;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #64748b;
        }

        .import-history-table td {
            font-size: 14px;
            color: #334155;
        }

        .import-file-name {
            font-weight: 600;
            color: #0f172a;
        }

        .import-program {
            display: inline-flex;
            padding: 4px 9px;
            border-radius: 6px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 12px;
            font-weight: 700;
        }

        .import-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .import-status.completed {
            background: #ecfdf5;
            color: #047857;
        }

        .import-status.processing {
            background: #fffbeb;
            color: #b45309;
        }

        .import-status.failed {
            background: #fef2f2;
            color: #b91c1c;
        }

        .import-number {
            font-weight: 700;
        }

        .import-success {
            color: #047857;
        }

        .import-duplicate {
            color: #b45309;
        }

        .import-failed {
            color: #b91c1c;
        }

        .import-action {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 7px 11px;
            border-radius: 6px;
            background: #f1f5f9;
            color: #334155;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
        }

        .import-action:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .import-empty {
            text-align: center;
            padding: 50px 20px;
            color: #64748b;
        }

        .import-empty i {
            font-size: 38px;
            margin-bottom: 12px;
            opacity: .5;
        }

        .import-table-wrapper {
            overflow-x: auto;
        }
    </style>

    <div class="import-table-wrapper">

        @if($imports->count())

            <table class="import-history-table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>File</th>
                        <th>Program</th>
                        <th>Uploaded By</th>
                        <th>Total</th>
                        <th>Success</th>
                        <th>Duplicate</th>
                        <th>Failed</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($imports as $import)

                        <tr>

                            <td>
                                {{ $imports->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="import-file-name">
                                    {{ $import->file_name }}
                                </div>
                            </td>

                            <td>
                                <span class="import-program">
                                    {{ $import->program_code }}
                                </span>
                            </td>

                            <td>
                                {{ $import->uploader?->name ?? 'System' }}
                            </td>

                            <td>
                                <span class="import-number">
                                    {{ number_format($import->total_records) }}
                                </span>
                            </td>

                            <td>
                                <span class="import-number import-success">
                                    {{ number_format($import->success_count) }}
                                </span>
                            </td>

                            <td>
                                <span class="import-number import-duplicate">
                                    {{ number_format($import->duplicate_count) }}
                                </span>
                            </td>

                            <td>
                                <span class="import-number import-failed">
                                    {{ number_format($import->failed_count) }}
                                </span>
                            </td>

                            <td>

                                @if($import->status === 'completed')

                                    <span class="import-status completed">
                                        <i class="fas fa-check-circle"></i>
                                        Completed
                                    </span>

                                @elseif($import->status === 'processing')

                                    <span class="import-status processing">
                                        <i class="fas fa-spinner"></i>
                                        Processing
                                    </span>

                                @else

                                    <span class="import-status failed">
                                        <i class="fas fa-times-circle"></i>
                                        Failed
                                    </span>

                                @endif

                            </td>

                            <td>
                                {{ $import->created_at?->format('d M Y, h:i A') }}
                            </td>

                            <td>

                                <a href="{{ route('learners.import.show', $import) }}"
                                   class="import-action">
                                    <i class="fas fa-eye"></i>
                                    View
                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

            @if($imports->hasPages())
                <div style="margin-top:20px;">
                    {{ $imports->links() }}
                </div>
            @endif

        @else

            <div class="import-empty">

                <i class="fas fa-history"></i>

                <div>
                    <strong>No import history found.</strong>
                </div>

                <div style="margin-top:6px;">
                    Your learner Excel imports will appear here.
                </div>

            </div>

        @endif

    </div>

</x-app-layout>