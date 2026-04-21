@if(session('success_value'))
    <div class="premium-inline-alert premium-inline-alert-success mb-4">
        {{ session('success_value') }}
    </div>
@endif

<section class="space-y-6">
    <style>
        .premium-inline-alert {
            border-radius: 16px;
            padding: 14px 16px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid transparent;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        }

        .premium-inline-alert-success {
            background: rgba(16, 185, 129, 0.10);
            color: #047857;
            border-color: rgba(16, 185, 129, 0.12);
        }

        .premium-detail-header h2 {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 6px;
            letter-spacing: -0.02em;
        }

        .premium-detail-header p {
            margin: 0;
            color: #64748b;
            font-size: 0.93rem;
        }

        .premium-detail-wrap {
            overflow-x: auto;
            border-radius: 22px;
            border: 1px solid rgba(148, 163, 184, 0.14);
            background: rgba(255, 255, 255, 0.86);
            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.06);
        }

        .premium-detail-table {
            width: 100%;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .premium-detail-table td {
            padding: 16px 18px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.10);
            vertical-align: top;
            font-size: 14px;
            background: rgba(255,255,255,0.90);
        }

        .premium-detail-table tr:nth-child(even) td {
            background: rgba(248, 250, 252, 0.78);
        }

        .premium-label {
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #475569;
            white-space: nowrap;
            min-width: 170px;
        }

        .premium-value {
            color: #0f172a;
            font-weight: 500;
        }

        .premium-muted {
            color: #64748b;
        }

        .premium-doc-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            margin: 0 8px 8px 0;
            border-radius: 999px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            background: rgba(59, 130, 246, 0.10);
            color: #1d4ed8;
        }

        .premium-doc-link:hover {
            color: #1e40af;
            background: rgba(59, 130, 246, 0.16);
        }

        .premium-status-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 13px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .premium-status-open {
            background: rgba(100, 116, 139, 0.12);
            color: #334155;
        }

        .premium-status-return {
            background: rgba(245, 158, 11, 0.14);
            color: #b45309;
        }

        .premium-status-rejected {
            background: rgba(239, 68, 68, 0.12);
            color: #b91c1c;
        }

        .premium-status-accepted {
            background: rgba(16, 185, 129, 0.12);
            color: #047857;
        }

        .premium-sub-card {
            padding: 14px;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(255,255,255,0.92), rgba(248,250,252,0.9));
            border: 1px solid rgba(148, 163, 184, 0.12);
        }

        .premium-inline-form {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .premium-input {
            min-height: 42px;
            padding: 9px 12px;
            border: 1px solid rgba(148, 163, 184, 0.22);
            border-radius: 12px;
            font-size: 14px;
            min-width: 180px;
            background: #fff;
        }

        .premium-input:focus {
            border-color: rgba(79, 70, 229, 0.35);
            outline: none;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.10);
        }

        .premium-btn-sm {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            border: none;
            text-decoration: none;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.18);
        }

        .premium-btn-sm:hover {
            color: #fff;
            transform: translateY(-1px);
        }

        .premium-spacer-row td {
            padding: 8px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9) !important;
        }

        @media (max-width: 768px) {
            .premium-detail-table td {
                padding: 14px 12px;
                font-size: 13px;
            }

            .premium-label {
                min-width: 130px;
            }
        }
    </style>

 

    @php
        $documents = [];
        if (!empty($event_transactions->uploaded_doc_links)) {
            $decoded = json_decode($event_transactions->uploaded_doc_links, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $documents = $decoded;
            } else {
                $documents = [$event_transactions->uploaded_doc_links];
            }
        }

        $remoteBaseUrl = env('FRONT_END_URL');

        $status = $event_transactions->review_status ?: 'Open';

        $statusClass = match($status) {
            'Accepted' => 'premium-status-accepted',
            'Rejected' => 'premium-status-rejected',
            'Pending', 'Return' => 'premium-status-return',
            default => 'premium-status-open'
        };
    @endphp

    <div class="premium-detail-wrap">
        <table class="premium-detail-table">
            <tbody>
                <tr>
                    <td class="premium-label"><strong>Event Name</strong></td>
                    <td class="premium-value">{{ $event_transactions->event_name ?: 'N/A' }}</td>
                    <td class="premium-label"><strong>Beneficiary Name</strong></td>
                    <td class="premium-value">{{ $event_transactions->beneficiary_name ?: 'N/A' }}</td>
                </tr>

                <tr>
                    <td class="premium-label"><strong>Event Category</strong></td>
                    <td class="premium-value">{{ $event_transactions->event_master_category ?: 'N/A' }}</td>
                    <td class="premium-label"><strong>Beneficiary Number</strong></td>
                    <td class="premium-value">{{ $event_transactions->beneficiary_phone_number ?: 'N/A' }}</td>
                </tr>

                <tr>
                    <td class="premium-label"><strong>Event Value</strong></td>
                    <td class="premium-value">₹ {{ number_format((float) $event_transactions->event_value, 2) }}</td>
                    <td class="premium-label"><strong>Beneficiary State</strong></td>
                    <td class="premium-value">{{ $event_transactions->PROGRAM_STATE ?: 'N/A' }}</td>
                </tr>

                <tr>
                    <td class="premium-label"><strong>Monthly Salary / Income</strong></td>
                    <td class="premium-value">₹ {{ number_format((float) $event_transactions->event_value, 2) }}</td>
                    <td class="premium-label"><strong>Beneficiary District</strong></td>
                    <td class="premium-value">{{ $event_transactions->PROGRAM_DISTRICT ?: 'N/A' }}</td>
                </tr>

                <tr class="premium-spacer-row">
                    <td colspan="4"></td>
                </tr>

                <tr>
                    <td class="premium-label"><strong>Document Required</strong></td>
                    <td class="premium-value">
                        {{ collect([$event_transactions->document_1, $event_transactions->document_2, $event_transactions->document_3])->filter()->implode(', ') ?: 'N/A' }}
                    </td>
                    <td class="premium-label"><strong>Uploaded Document</strong></td>
                    <td class="premium-value">
                        @if(count($documents) > 0)
                            @foreach($documents as $key => $doc)
                                <a href="{{ $remoteBaseUrl . ltrim('storage/'.$doc, '/') }}"
                                   target="_blank"
                                   class="premium-doc-link">
                                    {{ is_string($key) ? $key : 'Document' }} 📎
                                </a>
                            @endforeach
                        @else
                            <span class="premium-muted">No Documents</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td class="premium-label"><strong>Event Created</strong></td>
                    <td class="premium-value">{{ $event_transactions->event_date_created ?: 'N/A' }}</td>
                    <td class="premium-label"><strong>Event Submitted</strong></td>
                    <td class="premium-value">
                        @if($event_transactions->event_date_submitted)
                            <span class="premium-status-badge premium-status-accepted">
                                {{ $event_transactions->event_date_submitted }} &nbsp;✅
                            </span>
                        @else
                            <span class="premium-status-badge premium-status-open">Not Submitted</span>
                        @endif
                    </td>
                </tr>

                <tr class="premium-spacer-row">
                    <td colspan="4"></td>
                </tr>

                <tr>
                    <td class="premium-label"><strong>Field Agent Name</strong></td>
                    <td class="premium-value">
                        {{ $event_transactions->field_agent_name ?: 'N/A' }}
                        @if($event_transactions->field_agent_id)
                            [ {{ $event_transactions->field_agent_id }} ]
                        @endif
                    </td>
                    <td class="premium-label"><strong>Field Agent Number</strong></td>
                    <td class="premium-value">{{ $event_transactions->field_agent_contact ?: 'N/A' }}</td>
                </tr>

                <tr>
                    <td class="premium-label"><strong>Program Code</strong></td>
                    <td class="premium-value">{{ $event_transactions->PROGRAM_CODE ?: 'N/A' }}</td>
                    <td class="premium-label"><strong>&nbsp;</strong></td>
                    <td class="premium-value">&nbsp;</td>
                </tr>

                <tr>
                    <td class="premium-label"><strong>Function Type</strong></td>
                    <td class="premium-value">{{ $event_transactions->field_type ?: 'N/A' }}</td>
                    <td class="premium-label"><strong>Industry Type</strong></td>
                    <td class="premium-value">{{ $event_transactions->industry_type ?: 'N/A' }}</td>
                </tr>

                <tr>
                    <td class="premium-label"><strong>Event Transaction Status</strong></td>
                    <td class="premium-value">
                        <span class="premium-status-badge {{ $statusClass }}">
                            {{ $status == 'Pending' ? 'Return' : $status }}
                        </span>
                    </td>
                    <td class="premium-label"><strong>Update Monthly Salary / Income</strong></td>
                    <td class="premium-value">
                        <div class="premium-sub-card">
                            <form action="" method="post" class="premium-inline-form">
                                @csrf
                                <input type="number"
                                       name="fee_per_completed_transaction"
                                       class="premium-input"
                                       value="{{ $event_transactions->event_value }}"
                                       placeholder="Enter updated value" />
                                <button type="submit" class="premium-btn-sm">
                                    Update Value
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>