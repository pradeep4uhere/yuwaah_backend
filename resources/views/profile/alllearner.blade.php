<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <h2 class="premium-page-title mb-1">
                    {{ __('All Learner') }}
                </h2>
                <p class="premium-page-subtitle mb-0">
                    Manage and review all registered learners in one place.
                </p>
            </div>

            <div class="premium-count-badge">
                Total Learners: {{ $user->total() }}
            </div>
        </div>
    </x-slot>

    <div class="py-1">
        <div class="max-w-12xl">
            <div class="premium-listing-card">

                <!-- Top Bar -->
                <div class="premium-listing-topbar">
                    <div>
                        <h3 class="premium-section-title mb-1">Learners</h3>
                        <p class="premium-section-text mb-0">Detailed learner records with status and registration details.</p>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive premium-table-wrap">
                    <table class="table premium-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Program Code</th>
                                <th>Gender</th>
                                <th>Date Of Birth</th>
                              
                                <th>Status</th>
                                <th>Created</th>
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
                                            <div class="premium-avatar-sm">
                                                {{ strtoupper(substr($item['first_name'] ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="premium-name">{{ $item['first_name'] }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td nowrap="nowrap">
                                        <span class="premium-text-dark">{{ $item['email'] }}</span>
                                    </td>

                                    <td nowrap="nowrap">
                                        <span class="premium-text-muted">{{ $item['primary_phone_number'] }}</span>
                                    </td>
                                    <td nowrap="nowrap">
                                        <span class="premium-text-muted">{{ $item['UNIT_INSTITUTE'] }}</span>
                                    </td>

                                    <td nowrap="nowrap">
                                        <span class="premium-pill neutral">{{ $item['gender'] ?: 'N/A' }}</span>
                                    </td>

                                    <td nowrap="nowrap">
                                        <span class="premium-text-muted">{{ $item['yuth_hub_dob'] ?: 'N/A' }}</span>
                                    </td>

                                   

                                    <td nowrap="nowrap">
                                        @if(($item['primary_phone_number'] ?? '') !== '')
                                            <span class="premium-status active">Active</span>
                                        @else
                                            <span class="premium-status inactive">Inactive</span>
                                        @endif
                                    </td>

                                    <td nowrap="nowrap">
                                        <span class="premium-text-muted">{{ $item['created_at'] }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="premium-empty-state">
                                            <div class="premium-empty-icon">📘</div>
                                            <h4>No Learners Found</h4>
                                            <p class="mb-0">There are no learner records available at the moment.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(method_exists($user, 'links'))
                    <div class="premium-pagination-wrap">
                        {{ $user->links() }}
                    </div>
                @endif

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

        .premium-pill.neutral {
            background: #f1f5f9;
            color: #334155;
        }

        .premium-pill.info {
            background: rgba(59, 130, 246, 0.10);
            color: #1d4ed8;
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

        .premium-pagination-wrap {
            padding: 20px 24px 24px;
            background: rgba(255,255,255,0.65);
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
        }
    </style>
</x-app-layout>