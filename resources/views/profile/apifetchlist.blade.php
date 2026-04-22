<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <h2 class="premium-page-title mb-1">
                    {{ __('All YuthHub Learner Sync') }}
                </h2>
                <p class="premium-page-subtitle mb-0">
                    View and manage all application users from this panel.
                </p>
            </div>

            <div class="premium-count-badge">
                Total Sync List: {{ $user->count() }}
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="premium-listing-card">

                <!-- Top Bar -->
                <div class="premium-listing-topbar">
                    <div>
                        <h3 class="premium-section-title mb-1">All YuthHub Learner Sync Directory</h3>
                        <p class="premium-section-text mb-0">
                            Monitor users, their roles, status, and account activity.
                        </p>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive premium-table-wrap">
                    <table class="table premium-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Page Number</th>
                                <th>Records Fetched</th>
                                <th>Records Remaining</th>
                                <th>Fetched At</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($user as $item)
                                <tr>
                                    <td>
                                        <span class="premium-id-badge">#{{ $item['id'] }}</span>
                                    </td>

                                    <td nowrap="nowrap">
                                        <div class="premium-name-cell">
                                           
                                            <div>
                                                <div class="premium-name">{{ $item['page_number'] }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td nowrap="nowrap">
                                        <span class="premium-text-dark">{{ $item['records_fetched'] }}</span>
                                    </td>

                                    <td nowrap="nowrap">
                                        <span class="premium-text-muted">{{ $item['total_records'] ?: 'N/A' }}</span>
                                    </td>

                                   

                                    <td nowrap="nowrap">
                                        <span class="premium-text-muted">{{ $item['records_remaining'] }}</span>
                                    </td>

                                    <td nowrap="nowrap">
                                        <span class="premium-text-muted">{{ $item['  fetched_at'] }}</span>
                                    </td>

                                  
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="premium-empty-state">
                                            <div class="premium-empty-icon">👥</div>
                                            <h4>No Users Found</h4>
                                            <p class="mb-0">There are no user records available right now.</p>
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

    <style>
        .premium-page-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
        }

        .premium-page-subtitle {
            color: #64748b;
            font-size: 0.95rem;
        }

        .premium-count-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border-radius: 16px;
            background: linear-gradient(135deg, #ffffff, #eef2ff);
            color: #312e81;
            font-size: 14px;
            font-weight: 700;
            border: 1px solid rgba(99, 102, 241, 0.12);
            box-shadow: 0 10px 25px rgba(79, 70, 229, 0.08);
        }

        .premium-listing-card {
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 28px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .premium-listing-topbar {
            padding: 24px 28px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.12);
            background: linear-gradient(180deg, rgba(255,255,255,0.75), rgba(248,250,252,0.85));
        }

        .premium-section-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
        }

        .premium-section-text {
            color: #64748b;
            font-size: 0.94rem;
        }

        .premium-table-wrap {
            padding: 0;
        }

        .premium-table {
            width: 100%;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .premium-table thead th {
            background: linear-gradient(135deg, #f8fafc, #eef2ff);
            color: #334155;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 18px 16px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.14);
            white-space: nowrap;
        }

        .premium-table tbody td {
            padding: 18px 16px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.10);
            vertical-align: middle;
            background: rgba(255,255,255,0.82);
        }

        .premium-table tbody tr:nth-child(even) td {
            background: rgba(248, 250, 252, 0.72);
        }

        .premium-table tbody tr:hover td {
            background: rgba(238, 242, 255, 0.75);
            transition: all 0.25s ease;
        }

        .premium-id-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            background: #f1f5f9;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
        }

        .premium-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 180px;
        }

        .premium-avatar-sm {
            width: 40px;
            height: 40px;
            border-radius: 14px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.18);
            flex-shrink: 0;
        }

        .premium-name {
            font-weight: 700;
            color: #0f172a;
            font-size: 14px;
        }

        .premium-text-dark {
            color: #0f172a;
            font-weight: 500;
            font-size: 14px;
        }

        .premium-text-muted {
            color: #64748b;
            font-size: 14px;
        }

        .premium-pill {
            display: inline-flex;
            align-items: center;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .premium-pill.admin {
            background: rgba(99, 102, 241, 0.12);
            color: #3730a3;
        }

        .premium-pill.user {
            background: #f1f5f9;
            color: #334155;
        }

        .premium-status {
            display: inline-flex;
            align-items: center;
            padding: 8px 13px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .premium-status.active {
            background: rgba(16, 185, 129, 0.12);
            color: #047857;
        }

        .premium-status.inactive {
            background: rgba(239, 68, 68, 0.10);
            color: #b91c1c;
        }

        .premium-action-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .premium-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 14px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.25s ease;
            white-space: nowrap;
        }

        .premium-action-btn.edit-btn {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff;
            box-shadow: 0 10px 18px rgba(79, 70, 229, 0.18);
        }

        .premium-action-btn.edit-btn:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 14px 22px rgba(79, 70, 229, 0.24);
        }

        .premium-action-btn.delete-btn {
            background: rgba(239, 68, 68, 0.10);
            color: #b91c1c;
            border: 1px solid rgba(239, 68, 68, 0.12);
        }

        .premium-action-btn.delete-btn:hover {
            background: rgba(239, 68, 68, 0.16);
            color: #991b1b;
        }

        .premium-empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .premium-empty-state h4 {
            color: #0f172a;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .premium-empty-icon {
            font-size: 42px;
            margin-bottom: 12px;
        }

        @media (max-width: 768px) {
            .premium-listing-topbar {
                padding: 18px;
            }

            .premium-table thead th,
            .premium-table tbody td {
                padding: 14px 12px;
            }

            .premium-page-title {
                font-size: 1.35rem;
            }

            .premium-action-group {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</x-app-layout>