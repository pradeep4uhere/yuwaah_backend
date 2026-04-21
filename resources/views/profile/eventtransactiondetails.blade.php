<x-app-layout>
    <x-slot name="header">
        <div class="event-premium-page-header">
            <div>
                <h2 class="event-premium-title">{{ __('Event Details') }}</h2>
                <p class="event-premium-subtitle">Review event details, update status, and manage comments.</p>
            </div>
        </div>
    </x-slot>

    <style>
        .event-premium-shell {
            position: relative;
        }

        .event-premium-shell::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at top left, rgba(99, 102, 241, 0.10), transparent 28%),
                radial-gradient(circle at bottom right, rgba(168, 85, 247, 0.08), transparent 24%);
        }

        .event-premium-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .event-premium-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.03em;
            margin: 0;
        }

        .event-premium-subtitle {
            margin: 6px 0 0;
            color: #64748b;
            font-size: 0.95rem;
        }

        .event-premium-card {
            position: relative;
            background: linear-gradient(180deg, rgba(255,255,255,0.96), rgba(248,250,252,0.93));
            border: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 30px;
            box-shadow:
                0 24px 60px rgba(15, 23, 42, 0.08),
                0 8px 20px rgba(99, 102, 241, 0.05);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .event-premium-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            /*background: linear-gradient(90deg, #4f46e5, #7c3aed, #c084fc);*/
        }

        .event-premium-card-body {
            position: relative;
            z-index: 1;
        }

        .event-premium-alert {
            border: 0;
            border-radius: 18px;
            padding: 15px 18px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 12px 26px rgba(15, 23, 42, 0.06);
        }

        .event-premium-alert-danger {
            background: linear-gradient(135deg, rgba(254, 242, 242, 1), rgba(254, 226, 226, 0.9));
            color: #b91c1c;
        }

        .event-premium-alert-success {
            background: linear-gradient(135deg, rgba(236, 253, 245, 1), rgba(209, 250, 229, 0.9));
            color: #047857;
        }

        .event-premium-form-table,
        .event-premium-comment-table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            border-radius: 22px;
            border: 1px solid rgba(148, 163, 184, 0.14);
            background: rgba(255,255,255,0.88);
        }

        .event-premium-form-table td,
        .event-premium-comment-table td,
        .event-premium-comment-table th {
            padding: 16px 14px !important;
            vertical-align: top;
            border-color: rgba(148, 163, 184, 0.10) !important;
        }

        .event-premium-form-table tr:first-child td {
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.92));
        }

        .event-premium-comment-table tr:first-child td {
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
            color: #334155;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .event-premium-section-heading {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin: 22px 0 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .event-premium-section-heading::before {
            content: "";
            width: 12px;
            height: 12px;
            border-radius: 999px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            box-shadow: 0 0 0 6px rgba(79, 70, 229, 0.10);
        }

        .event-premium-label {
            display: inline-block;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #475569;
        }

        .event-premium-input,
        .event-premium-select {
            width: 100%;
            min-height: 46px;
            padding: 10px 14px;
            border-radius: 14px !important;
            border: 1px solid rgba(148, 163, 184, 0.18) !important;
            background: rgba(255,255,255,0.96) !important;
            font-size: 14px;
            color: #0f172a;
            transition: all 0.22s ease;
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.9),
                0 3px 10px rgba(15, 23, 42, 0.03);
        }

        .event-premium-input:focus,
        .event-premium-select:focus {
            border-color: rgba(99, 102, 241, 0.34) !important;
            box-shadow:
                0 0 0 4px rgba(99, 102, 241, 0.12),
                0 10px 24px rgba(99, 102, 241, 0.08) !important;
            outline: none;
        }

        .event-premium-submit {
            min-width: 120px;
            min-height: 46px;
            border: none;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.02em;
            color: #fff;
            background: linear-gradient(135deg, #4338ca, #7c3aed);
            box-shadow: 0 16px 28px rgba(79, 70, 229, 0.22);
            transition: all 0.22s ease;
        }

        .event-premium-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 20px 34px rgba(79, 70, 229, 0.28);
        }

        .event-premium-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .event-premium-status.open {
            background: rgba(100, 116, 139, 0.10);
            color: #334155;
        }

        .event-premium-status.return {
            background: rgba(245, 158, 11, 0.12);
            color: #b45309;
        }

        .event-premium-status.rejected {
            background: rgba(239, 68, 68, 0.12);
            color: #b91c1c;
        }

        .event-premium-status.accepted {
            background: rgba(16, 185, 129, 0.12);
            color: #047857;
        }

        .event-premium-comment-type {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 11px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            background: #f1f5f9;
            color: #334155;
        }

        .event-premium-comment-type.agent {
            background: linear-gradient(135deg, rgba(254, 226, 226, 1), rgba(255, 214, 214, 0.95));
            color: #b91c1c;
        }

        .event-premium-comment-table tr:not(:first-child):hover td {
            background: rgba(238, 242, 255, 0.55);
            transition: all 0.18s ease;
        }

        .event-premium-agent-row td {
            background: linear-gradient(135deg, rgba(255, 241, 242, 0.95), rgba(255, 228, 230, 0.95)) !important;
        }

        .event-premium-sn {
            font-weight: 800;
            color: #0f172a;
        }

        @media (max-width: 992px) {
            .event-premium-form-table,
            .event-premium-comment-table {
                font-size: 13px;
            }

            .event-premium-title {
                font-size: 1.45rem;
            }
        }
    </style>

    <div class="py-12 event-premium-shell">
        <div class="max-w-12xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg event-premium-card">
                <div class="max-w-12xl event-premium-card-body">
                    @include('profile.partials.event-details')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg event-premium-card">
                <div class="max-w-12xl event-premium-card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger event-premium-alert event-premium-alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show event-premium-alert event-premium-alert-success" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show event-premium-alert event-premium-alert-danger" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{route('event.savecomment')}}" method="post">
                        @csrf

                        <table class="table table-striped table-bordered event-premium-form-table">
                            <tbody>
                                <tr style="font-size:13px">
                                    <td nowrap="nowrap">
                                        <label class="event-premium-label"><strong>Enter Comment</strong></label>
                                        <input type="text" name="comment" class="form-control form-control-sm event-premium-input" placeholder="Enter comment">
                                        <input type="hidden" name="event_transaction_id" value="{{$event_transaction_id}}"/>
                                        <input type="hidden" name="user_id" value="{{$event_transaction_id}}"/>
                                        <input type="hidden" name="user_name" value="{{Auth::user()->name}}"/>
                                        <input type="hidden" name="agent_id" value="{{$event_transactions->ys_id}}"/>
                                    </td>

                                    <td nowrap="nowrap" align="left">
                                        <label class="event-premium-label"><strong>Status</strong></label>
                                        <select name="review_status" id="review_status" class="form-select form-select-sm event-premium-select">
                                            <option value="Open">Open</option>
                                            <option value="Return">Return</option>
                                            <option value="Rejected">Rejected</option>
                                            <option value="Accepted">Accepted</option>
                                        </select>
                                    </td>

                                    <td>
                                        <label class="event-premium-label"><strong>Comment Type</strong></label>
                                        <select name="comment_type" class="form-select form-select-sm event-premium-select">
                                            <option value="internal">Internal</option>
                                            <option value="external">External</option>
                                        </select>
                                    </td>

                                    <td>
                                        <label class="event-premium-label"><strong>Function Type</strong></label>
                                        <select name="field_type" class="form-control event-premium-select" id="function_type">
                                            <option value="">Select Function</option>
                                            @foreach(($functions ?? []) as $fn)
                                                <option value="{{ $fn }}" @if($event_transactions->field_type == $fn) selected="selected" @endif>{{ $fn }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <label class="event-premium-label"><strong>Industry Type</strong></label>
                                        <select name="industry_type" class="form-control event-premium-select" id="industry_type">
                                            <option value="">Select Industry</option>
                                            @foreach($industries as $industry)
                                                <option value="{{ $industry }}" @if($event_transactions->industry_type == $industry) selected="selected" @endif>{{ $industry }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td nowrap="nowrap" class="align-middle">
                                        <label class="event-premium-label"><strong>&nbsp;</strong></label>
                                        <button type="submit" class="btn btn-sm btn-primary event-premium-submit">Submit</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <h2 class="text-lg font-medium text-gray-900 event-premium-section-heading">
                            All Comments
                        </h2>

                        <table class="table table-striped table-bordered event-premium-comment-table">
                            <tbody>
                                @if($commentList)
                                    <tr style="font-size:13px">
                                        <td nowrap="nowrap" align="left" width="1%"><strong>{{'SN'}}</strong></td>
                                        <td nowrap="nowrap" align="left" width="5%"><strong>Status</strong></td>
                                        <td nowrap="nowrap" align="left" width="5%"><strong>Comment Type</strong></td>
                                        <td nowrap="nowrap" class="align-middle" colspan="3"><strong>Comment</strong></td>
                                        <td nowrap="nowrap" align="right" width="5%"><strong>Comment By</strong></td>
                                        <td nowrap="nowrap" align="right" width="5%"><strong>Last Updated</strong></td>
                                    </tr>

                                    <?php $count=1; ?>
                                    @foreach($commentList as $item)
                                        @php
                                            $statusClass = 'open';
                                            if ($item['status'] == 'Accepted') $statusClass = 'accepted';
                                            elseif ($item['status'] == 'Rejected') $statusClass = 'rejected';
                                            elseif ($item['status'] == 'Return' || $item['status'] == 'Pending') $statusClass = 'return';
                                        @endphp

                                        <tr class="{{ $item['comment_type'] == 'agent' ? 'event-premium-agent-row' : '' }}" style="font-size:13px;">
                                            <td nowrap="nowrap" align="left" width="1%">
                                                <strong class="event-premium-sn">{{$count}}</strong>
                                            </td>

                                            <td nowrap="nowrap">
                                                <span class="event-premium-status {{ $statusClass }}">
                                                    {{ $item['status'] }}
                                                </span>
                                            </td>

                                            <td nowrap="nowrap">
                                                <span class="event-premium-comment-type {{ $item['comment_type'] == 'agent' ? 'agent' : '' }}">
                                                    {{ $item['comment_type'] }}
                                                </span>
                                            </td>

                                            <td nowrap="nowrap" colspan="3">{{ $item['comment'] }}</td>
                                            <td nowrap="nowrap" align="right">{{ $item['comment_type'] == 'agent' ? 'Agent- ' : '' }}{{ $item['user_name'] }}</td>
                                            <td nowrap="nowrap" align="right">{{ $item['created_at'] }}</td>
                                        </tr>
                                        <?php $count++;?>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
        $("#review_status").on("change", function () {
            let status = $(this).val();
            let eventName = "{{ $event_transactions->event_name }}";

            $("#function_type").hide().prop("disabled", true);
            $("#industry_type").hide().prop("disabled", true);

            if (status === "Accepted") {
                if (eventName === "Social Protection") {
                    $("#industry_type").show().prop("disabled", false);
                } else {
                    $("#function_type").show().prop("disabled", false);
                }
            }
        });
    </script>
    @endsection
</x-app-layout>