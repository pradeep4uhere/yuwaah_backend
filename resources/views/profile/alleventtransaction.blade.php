<x-app-layout>
    <x-slot name="header">
        
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <h2 class="premium-page-title mb-1">
                    {{ __('All Event Transaction') }}
                </h2>
                <p class="premium-page-subtitle mb-0">
                    Review, filter, export and manage all submitted event transactions.
                </p>
            </div>

            <div class="premium-count-badge">
                Total Transactions: {{ $event_transactions->total() }}
            </div>
        </div>
    </x-slot>

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

        .premium-card {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(148, 163, 184, 0.14);
            border-radius: 26px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .premium-card-header {
            padding: 24px 28px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.10);
            background: linear-gradient(180deg, rgba(255,255,255,0.9), rgba(248,250,252,0.85));
        }

        .premium-card-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .premium-card-subtitle {
            font-size: 0.92rem;
            color: #64748b;
            margin-bottom: 0;
        }

        .premium-card-body {
            padding: 24px 28px;
        }

        .premium-form-control {
            width: 100%;
            min-height: 46px;
            padding: 10px 14px;
            font-size: 14px;
            border: 1px solid rgba(148, 163, 184, 0.22);
            border-radius: 14px;
            background: #fff;
            transition: all 0.22s ease-in-out;
            box-shadow: none;
        }

        .premium-form-control:focus {
            border-color: rgba(79, 70, 229, 0.35);
            outline: none;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.10);
        }

        .premium-filter-table {
            width: 100%;
            font-size: 14px;
            white-space: nowrap;
        }

        .premium-filter-table th,
        .premium-filter-table td {
            padding: 10px 8px;
            vertical-align: middle;
            color: #334155;
            border: 0 !important;
        }

        .premium-filter-label {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
        }

        .premium-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
            padding: 10px 16px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            transition: all 0.22s ease;
            white-space: nowrap;
        }

        .premium-btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff;
            box-shadow: 0 12px 24px rgba(79, 70, 229, 0.18);
        }

        .premium-btn-primary:hover {
            color: #fff;
            transform: translateY(-1px);
        }

        .premium-btn-danger {
            background: rgba(239, 68, 68, 0.10);
            color: #b91c1c;
            border: 1px solid rgba(239, 68, 68, 0.12);
        }

        .premium-btn-danger:hover {
            color: #991b1b;
            background: rgba(239, 68, 68, 0.16);
        }

        .premium-btn-export {
            background: linear-gradient(135deg, #fff, #f8fafc);
            color: #0f172a;
            border: 1px solid rgba(148, 163, 184, 0.18);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
        }

        .premium-btn-export:hover {
            color: #111827;
            transform: translateY(-1px);
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
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: 16px 14px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.14);
            white-space: nowrap;
        }

        .premium-table tbody td,
        .premium-table tbody th {
            padding: 16px 14px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.10);
            background: rgba(255,255,255,0.86);
            vertical-align: middle;
            font-size: 14px;
        }

        .premium-table tbody tr:nth-child(even) td,
        .premium-table tbody tr:nth-child(even) th {
            background: rgba(248,250,252,0.76);
        }

        .premium-table tbody tr:hover td,
        .premium-table tbody tr:hover th {
            background: rgba(238,242,255,0.72);
            transition: all .2s ease;
        }

        .premium-summary-table tbody td {
            text-align: center;
            font-weight: 700;
        }

        .premium-status {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .premium-status.open {
            background: rgba(100, 116, 139, 0.12);
            color: #334155;
        }

        .premium-status.pending {
            background: rgba(245, 158, 11, 0.14);
            color: #b45309;
        }

        .premium-status.rejected {
            background: rgba(239, 68, 68, 0.12);
            color: #b91c1c;
        }

        .premium-status.accepted {
            background: rgba(16, 185, 129, 0.12);
            color: #047857;
        }

        .premium-badge {
            display: inline-flex;
            align-items: center;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .premium-badge.success {
            background: rgba(16, 185, 129, 0.12);
            color: #047857;
        }

        .premium-badge.danger {
            background: rgba(239, 68, 68, 0.10);
            color: #b91c1c;
        }

        .premium-id-link {
            font-weight: 800;
            color: #4338ca;
            text-decoration: none;
        }

        .premium-id-link:hover {
            color: #312e81;
        }

        .premium-doc-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            background: rgba(59,130,246,0.10);
            color: #1d4ed8;
            margin-bottom: 6px;
        }

        .premium-doc-link:hover {
            color: #1e40af;
            background: rgba(59,130,246,0.16);
        }

        .premium-view-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 14px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff;
            box-shadow: 0 10px 18px rgba(79, 70, 229, 0.18);
        }

        .premium-view-btn:hover {
            color: #fff;
            transform: translateY(-1px);
        }

        .premium-empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #64748b;
            font-weight: 700;
            background: rgba(248,250,252,0.8) !important;
        }

        .premium-muted {
            color: #64748b;
        }

        @media (max-width: 768px) {
            .premium-card-header,
            .premium-card-body {
                padding: 18px;
            }

            .premium-page-title {
                font-size: 1.35rem;
            }
        }
    </style>

    <style>
        /* =====================================================
   PROGRAM SUMMARY COLLAPSE
===================================================== */

.summary-toggle-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid rgba(255, 255, 255, 0.15);
    background: rgba(255, 255, 255, 0.06);
    transition: all 0.25s ease;
    white-space: nowrap;
}

.summary-toggle-btn:hover {
    background: rgba(255, 255, 255, 0.12);
}

.summary-toggle-icon {
    font-size: 10px;
    transition: transform 0.25s ease;
}


/* When opened */

.premium-card-header[aria-expanded="true"] .summary-toggle-text {
    font-size: 0;
}

.premium-card-header[aria-expanded="true"] .summary-toggle-text::after {
    content: "Click to Hide";
    font-size: 12px;
}

.premium-card-header[aria-expanded="true"] .summary-toggle-icon {
    transform: rotate(180deg);
}


/* Mobile */

@media (max-width: 768px) {

    .premium-card-header {
        gap: 12px;
    }

    .premium-card-title {
        font-size: 15px;
    }

    .premium-card-subtitle {
        font-size: 11px;
    }

    .summary-toggle-btn {
        padding: 6px 10px;
        font-size: 11px;
    }

}
    </style>

    <style>
        /* =========================================================
   PREMIUM PROGRAM SUMMARY TABLE
   ========================================================= */

.premium-summary-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 6px;
    margin-top: 5px;
}


/* ---------------------------------------------------------
   HEADER
--------------------------------------------------------- */

.premium-summary-table thead th {
    background: linear-gradient(135deg, #172554, #1e3a8a);
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 13px 12px;
    border: none;
    white-space: nowrap;
}

.premium-summary-table thead th:first-child {
    border-radius: 10px 0 0 10px;
}

.premium-summary-table thead th:last-child {
    border-radius: 0 10px 10px 0;
}


/* ---------------------------------------------------------
   BODY ROW
--------------------------------------------------------- */

.premium-summary-table tbody tr {
    background: #ffffff;
    transition: all 0.25s ease;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
}

.premium-summary-table tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.12);
}


/* ---------------------------------------------------------
   CELLS
--------------------------------------------------------- */

.premium-summary-table tbody td {
    padding: 11px 12px;
    border-top: 1px solid #eef2f7;
    border-bottom: 1px solid #eef2f7;
    color: #334155;
    font-size: 13px;
    vertical-align: middle;
}

.premium-summary-table tbody td:first-child {
    border-left: 1px solid #eef2f7;
    border-radius: 10px 0 0 10px;
}

.premium-summary-table tbody td:last-child {
    border-right: 1px solid #eef2f7;
    border-radius: 0 10px 10px 0;
}


/* ---------------------------------------------------------
   PROGRAM NAME
--------------------------------------------------------- */

.premium-summary-table tbody td:first-child strong {
    color: #172554;
    font-weight: 700;
}


/* ---------------------------------------------------------
   TOTAL EVENT
--------------------------------------------------------- */

.premium-summary-table tbody td:nth-child(2) strong {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 55px;
    padding: 5px 10px;
    border-radius: 7px;
    background: #eff6ff;
    color: #1d4ed8;
    font-weight: 700;
}


/* ---------------------------------------------------------
   OPEN
--------------------------------------------------------- */

.premium-summary-table tbody td:nth-child(3) strong {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 50px;
    padding: 5px 10px;
    border-radius: 7px;
    background: #fff7ed;
    color: #c2410c;
    font-weight: 700;
}


/* ---------------------------------------------------------
   RETURN
--------------------------------------------------------- */

.premium-summary-table tbody td:nth-child(4) strong {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 50px;
    padding: 5px 10px;
    border-radius: 7px;
    background: #fefce8;
    color: #a16207;
    font-weight: 700;
}


/* ---------------------------------------------------------
   REJECTED
--------------------------------------------------------- */

.premium-summary-table tbody td:nth-child(5) strong {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 50px;
    padding: 5px 10px;
    border-radius: 7px;
    background: #fef2f2;
    color: #dc2626;
    font-weight: 700;
}


/* ---------------------------------------------------------
   ACCEPTED
--------------------------------------------------------- */

.premium-summary-table tbody td:nth-child(6) strong {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 50px;
    padding: 5px 10px;
    border-radius: 7px;
    background: #ecfdf5;
    color: #047857;
    font-weight: 700;
}


/* ---------------------------------------------------------
   FIRST COLUMN EMPHASIS
--------------------------------------------------------- */

.premium-summary-table tbody tr:hover td:first-child {
    border-left: 3px solid #2563eb;
}


/* ---------------------------------------------------------
   HEADER + COLLAPSE BUTTON
--------------------------------------------------------- */

.premium-card-header {
    background: linear-gradient(
        135deg,
        #0f172a 0%,
        #172554 55%,
        #1e3a8a 100%
    );
    color: #ffffff;
    border-radius: 12px;
    padding: 16px 20px;
}

.premium-card-title {
    color: #ffffff;
    font-weight: 700;
}

.premium-card-subtitle {
    color: rgba(255, 255, 255, 0.68);
}


/* ---------------------------------------------------------
   CLICK TO SEE BUTTON
--------------------------------------------------------- */

.summary-toggle-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 13px;

    color: #ffffff;

    background: rgba(255, 255, 255, 0.10);
    border: 1px solid rgba(255, 255, 255, 0.20);

    border-radius: 8px;

    font-size: 11px;
    font-weight: 700;

    backdrop-filter: blur(8px);

    transition: all 0.25s ease;
}

.summary-toggle-btn:hover {
    background: rgba(255, 255, 255, 0.18);
    border-color: rgba(255, 255, 255, 0.35);
    transform: translateY(-1px);
}

.summary-toggle-icon {
    transition: transform 0.25s ease;
}

.premium-card-header[aria-expanded="true"] .summary-toggle-icon {
    transform: rotate(180deg);
}


/* ---------------------------------------------------------
   TABLE CONTAINER
--------------------------------------------------------- */

.premium-card-body {
    padding: 14px;
    background: #f8fafc;
    border-radius: 0 0 12px 12px;
}


/* ---------------------------------------------------------
   MOBILE
--------------------------------------------------------- */

@media (max-width: 768px) {

    .premium-card-header {
        padding: 13px 14px;
    }

    .premium-card-title {
        font-size: 14px;
    }

    .premium-card-subtitle {
        font-size: 10px;
    }

    .summary-toggle-btn {
        padding: 6px 9px;
        font-size: 10px;
    }

    .premium-summary-table thead th {
        font-size: 10px;
        padding: 10px 8px;
    }

    .premium-summary-table tbody td {
        font-size: 12px;
        padding: 9px 8px;
    }
}

/* =========================================================
   EVENT TRANSACTION FILTER - PREMIUM HEADER
========================================================= */

.premium-card-header {
    position: relative;
    padding: 18px 22px;

    background: linear-gradient(
        135deg,
        #0f172a 0%,
        #172554 50%,
        #1e40af 100%
    );

    border-radius: 12px 12px 0 0;

    border-bottom: 1px solid rgba(255, 255, 255, 0.10);

    overflow: hidden;
}


/* Decorative glow */

.premium-card-header::before {
    content: "";
    position: absolute;

    width: 180px;
    height: 180px;

    right: -70px;
    top: -100px;

    background: rgba(59, 130, 246, 0.18);

    border-radius: 50%;

    filter: blur(20px);

    pointer-events: none;
}


/* Small accent line */

.premium-card-header::after {
    content: "";

    position: absolute;

    left: 0;
    top: 0;

    width: 4px;
    height: 100%;

    background: linear-gradient(
        180deg,
        #60a5fa,
        #2563eb
    );

    border-radius: 12px 0 0 12px;
}


/* =========================================================
   TITLE
========================================================= */

.premium-card-title {
    position: relative;
    z-index: 2;

    margin: 0;

    color: #ffffff;

    font-size: 18px;
    font-weight: 700;

    letter-spacing: 0.2px;

    line-height: 1.4;
}


/* =========================================================
   SUBTITLE
========================================================= */

.premium-card-subtitle {
    position: relative;
    z-index: 2;

    margin: 5px 0 0;

    color: rgba(255, 255, 255, 0.68);

    font-size: 12px;

    font-weight: 400;

    line-height: 1.5;
}


/* =========================================================
   HOVER EFFECT
========================================================= */

.premium-card-header {
    transition:
        box-shadow 0.25s ease,
        transform 0.25s ease;
}

.premium-card-header:hover {
    box-shadow:
        0 8px 25px rgba(15, 23, 42, 0.18);
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 768px) {

    .premium-card-header {
        padding: 15px 16px;
    }

    .premium-card-title {
        font-size: 15px;
    }

    .premium-card-subtitle {
        font-size: 10.5px;
        margin-top: 4px;
    }

}
        </style>

    {{-- Filter Section --}}
    <div class="py-4">
        <div class="max-w-12xl ">
            <div class="premium-card">
                <div class="premium-card-header">
                    <h3 class="premium-card-title">Event Transaction Filter</h3>
                    <p class="premium-card-subtitle">Use filters below to narrow down the records and export matching results.</p>
                </div>

                <div class="premium-card-body">
                    <div style="overflow-x:auto;">
                        <form action="" method="get">
                            @csrf

                            <table class="premium-filter-table">
                                <tr>
                                    <th class="premium-filter-label">Status</th>
                                    <th class="premium-filter-label">Event Type</th>
                                    <th class="premium-filter-label">Event Category</th>
                                    <th class="premium-filter-label">Submitted Date From</th>
                                    <th class="premium-filter-label">Submitted Date To</th>
                                    <th class="premium-filter-label">Beneficiary Name</th>
                                    <th class="premium-filter-label">Beneficiary Number</th>
                                    <th></th>
                                    <th></th>
                                </tr>

                                <tr>
                                    <td>
                                        <select class="premium-form-control" name="status">
                                            <option value="">All</option>
                                            <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                                            <option value="Return" {{ request('status') == 'Return' ? 'selected' : '' }}>Return</option>
                                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                            <option value="Accepted" {{ request('status') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                        </select>
                                    </td>

                                    <td>
                                        <select class="premium-form-control" name="event_type" id="event_type">
                                            <option value="">All</option>
                                            @foreach($eventTypeArray as $item)
                                                <option value="{{ $item->id }}" {{ request('event_type') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <select class="premium-form-control" name="event_category" id="event_category" style="min-width:280px">
                                            <option value="">All</option>
                                            @foreach($eventCategoryArray ?? [] as $cat)
                                                <option value="{{ $cat->id }}" {{ request('event_category') == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->event_category }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td><input type="date" name="from_date" class="premium-form-control" value="{{ request('from_date') }}" /></td>
                                    <td><input type="date" name="to_date" class="premium-form-control" value="{{ request('to_date') }}" /></td>
                                    <td><input type="text" class="premium-form-control" name="benificiery_name" value="{{ request('benificiery_name') }}" /></td>
                                    <td><input type="text" class="premium-form-control" name="benificiery_mobile" value="{{ request('benificiery_mobile') }}" /></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <th class="premium-filter-label">Agent ID</th>
                                    <th class="premium-filter-label">Program Code</th>
                                    <th class="premium-filter-label">Event Number</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>

                                <tr>
                                    <td>
                                        <input class="premium-form-control" type="text" name="sakhi_id" value="{{ request('sakhi_id') }}" />
                                    </td>

                                    <td>
                                        <select name="program_code" class="premium-form-control">
                                            <option value="">All Program Code</option>
                                            @foreach($programCode as $item)
                                                <option value="{{ $item->name }}" @selected(request('program_code') == $item->name)>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <input class="premium-form-control" type="text" name="id" value="{{ request('id') }}" />
                                    </td>

                                    <td></td>
                                    <td></td>

                                    <td nowrap="nowrap">
                                        <input type="submit" name="submit" class="premium-btn premium-btn-primary" value="Search"/>
                                        <a href="{{ url()->current() }}" class="premium-btn premium-btn-danger">Clear Filter</a>
                                    </td>

                                    <td>
                                        <a href="{{ route('export-event-transactions',request()->query()) }}" class="premium-btn premium-btn-export">
                                            <img src="{{ asset('download.png') }}" height="22" width="22" />
                                            Export Event Transaction
                                        </a>
                                    </td>

                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Table --}}
    <div class="py-2">
    <div class="max-w-6xl">

        <div class="premium-card">

            <!-- Header / Click Area -->
            <div
                class="premium-card-header d-flex justify-content-between align-items-center"
                data-bs-toggle="collapse"
                data-bs-target="#programWiseSummary"
                aria-expanded="false"
                aria-controls="programWiseSummary"
                style="cursor:pointer;"
            >

                <div>
                    <h3 class="premium-card-title mb-1">
                        Program Wise Summary
                    </h3>

                    <p class="premium-card-subtitle mb-0">
                        Quick overview of event counts by program code and review status.
                    </p>
                </div>

                <!-- Click to See -->
                <div class="summary-toggle-btn">
                    <span class="summary-toggle-text">
                        Click to See
                    </span>

                    <span class="summary-toggle-icon">
                        ▼
                    </span>
                </div>

            </div>


            <!-- Collapsible Content -->
            <div
                class="collapse"
                id="programWiseSummary"
            >

                <div class="premium-card-body" style="overflow-x:auto;">

                    <table class="table premium-table premium-summary-table mb-0">

                        <thead>
                            <tr>
                                <th style="text-align:center;">
                                    Program Code
                                </th>

                                <th style="text-align:center;">
                                    Total Event
                                </th>

                                <th style="text-align:center;">
                                    Total Open
                                </th>

                                <th style="text-align:center;">
                                    Total Return
                                </th>

                                <th style="text-align:center;">
                                    Total Rejected
                                </th>

                                <th style="text-align:center;">
                                    Total Accepted
                                </th>
                            </tr>
                        </thead>


                        <tbody>

                            @foreach($statusCounts as $item)

                                <tr style="font-size:13px">

                                    <td align="center">
                                        <strong>
                                            {{ $item->program_name }}
                                        </strong>
                                    </td>

                                    <td align="center">
                                        <strong>
                                            {{ $item->total }}
                                        </strong>
                                    </td>

                                    <td align="center">
                                        <strong>
                                            {{ $item->open_count }}
                                        </strong>
                                    </td>

                                    <td align="center">
                                        <strong>
                                            {{ $item->return_count }}
                                        </strong>
                                    </td>

                                    <td align="center">
                                        <strong>
                                            {{ $item->rejected_count }}
                                        </strong>
                                    </td>

                                    <td align="center">
                                        <strong>
                                            {{ $item->accepted_count }}
                                        </strong>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
</div>

    {{-- Main Listing --}}
    <div class="py-2">
        <div class="max-w-12xl">
            <div class="premium-card">
                <div class="premium-card-header">
                    <h3 class="premium-card-title">Event Transactions Listing</h3>
                    <p class="premium-card-subtitle">Complete listing of all event transactions with documents, comments and status.</p>
                </div>

                <div class="premium-card-body">
                    <div style="overflow-x:auto;">
                        <table class="table premium-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Event Status</th>
                                    <th>Program Code</th>
                                    <th>Event Name</th>
                                    <th nowrap="nowrap">Event Category</th>
                                    <th>Field Agent</th>
                                    <th>Field Agent ID</th>
                                    <th>Required Docs</th>
                                    <th>Number</th>
                                    <th>Beneficiary Name</th>
                                    <th>Beneficiary State</th>
                                    <th>Beneficiary District</th>
                                    <th>Event Created</th>
                                    <th>Event Submitted</th>
                                    <th>Event Value</th>
                                    <th>Document</th>
                                    <th>Comment</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($event_transactions as $item)
                                    @php
                                        $status = $item->review_status;

                                        $statusClass = match($status) {
                                            'Accepted' => 'accepted',
                                            'Rejected' => 'rejected',
                                            'Return' => 'return',
                                            'Pending'  => 'pending',
                                             default    => 'open'
                                        };

                                        $documents = [];
                                        if (!empty($item->uploaded_doc_links)) {
                                            $decoded = json_decode($item->uploaded_doc_links, true);
                                            $documents = is_array($decoded) ? $decoded : [$item->uploaded_doc_links];
                                        }

                                        $remoteBaseUrl = 'https://youthhubpartner.org/';
                                    @endphp

                                    <tr class="align-middle">
                                        <th scope="row">
                                            <a href="{{ route('event.edit',['id'=>$item->id]) }}" class="premium-id-link">
                                                {{ $item->id }}
                                            </a>
                                        </th>

                                        <td>
                                            <span class="premium-status {{ $statusClass }}">
                                                {{ $status == 'Pending' ? 'Return' : $status }}
                                            </span>
                                        </td>

                                        <td>{{ $item->PROGRAM_CODE }}</td>
                                        <td>{{ $item->event_name }}</td>
                                        <td nowrap="nowrap">{{ $item->event_master_category }}</td>
                                        <td nowrap="nowrap">{{ $item->field_agent_name }}</td>
                                        <td nowrap="nowrap">{{ $item->sakhi_id }}</td>

                                        <td >
                                            {{ $item->document_1 }}<br>
                                            {{ $item->document_2 }}<br>
                                            {{ $item->document_3 }}
                                        </td>

                                        <td>{{ $item->beneficiary_phone_number }}</td>
                                        <td>{{ $item->beneficiary_name }}</td>
                                        <td>{{ $item->PROGRAM_STATE }}</td>
                                        <td>{{ $item->PROGRAM_DISTRICT }}</td>
                                        <td>{{ $item->updated_at }}</td>

                                        <td>
                                            @if($item->event_date_submitted)
                                                <span class="premium-badge success">{{ $item->event_date_submitted }}</span>
                                            @else
                                                <span class="premium-badge danger">NA</span>
                                            @endif
                                        </td>

                                        <td>₹ {{ number_format($item->event_value, 2) }}</td>

                                        <td>
                                            @if(count($documents))
                                                @foreach($documents as $doc)
                                                    <a href="{{ $remoteBaseUrl . 'storage/' . $doc }}"
                                                       target="_blank"
                                                       class="premium-doc-link">
                                                        📎 View
                                                    </a>
                                                @endforeach
                                            @else
                                                <span class="premium-muted">No Documents</span>
                                            @endif
                                        </td>

                                        <td>{{ $item->comment }}</td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($item->created_at)->timezone('Asia/Kolkata')->format('d M Y h:i A') }}
                                        </td>

                                        <td>
                                            <a href="{{ route('event.edit',['id'=>$item->id]) }}" class="premium-view-btn">
                                                View Event
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="20" class="premium-empty-state">
                                            No Records Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $event_transactions->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>