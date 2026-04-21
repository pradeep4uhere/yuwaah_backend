<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Yuwwah Administrator') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600;700;800&display=swap" rel="stylesheet" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        :root {
            --premium-bg: #f6f8fc;
            --premium-surface: rgba(255, 255, 255, 0.88);
            --premium-card: #ffffff;
            --premium-border: rgba(15, 23, 42, 0.08);
            --premium-text: #0f172a;
            --premium-muted: #64748b;
            --premium-primary: #4f46e5;
            --premium-secondary: #7c3aed;
            --premium-gold: #d4a017;
            --premium-shadow: 0 10px 35px rgba(15, 23, 42, 0.08);
            --premium-shadow-soft: 0 6px 18px rgba(15, 23, 42, 0.06);
        }

        body {
            font-family: 'Figtree', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(99, 102, 241, 0.10), transparent 30%),
                radial-gradient(circle at top right, rgba(124, 58, 237, 0.08), transparent 25%),
                linear-gradient(135deg, #f8fafc 0%, #eef2ff 40%, #f8fafc 100%);
            color: var(--premium-text);
        }

        .premium-page {
            min-height: 100vh;
            background: transparent;
            position: relative;
        }

        .premium-page::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background:
                linear-gradient(180deg, rgba(255,255,255,0.65), rgba(255,255,255,0.15));
            z-index: 0;
        }

        .premium-content {
            position: relative;
            z-index: 1;
        }

        .premium-header {
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-bottom: 1px solid var(--premium-border);
            box-shadow: var(--premium-shadow-soft);
            border-radius: 0 0 24px 24px;
            margin: 0 14px;
        }

        .premium-header-inner {
            max-width: 1800px;
            margin: 0 auto;
            padding: 22px 28px;
        }

        .premium-main {
            max-width: 1800px;
            margin: 22px auto 0;
            padding: 0 14px 30px;
        }

        .premium-card {
            background: var(--premium-surface);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--premium-border);
            box-shadow: var(--premium-shadow);
            border-radius: 24px;
            overflow: hidden;
        }

        .premium-section {
            padding: 24px;
        }

        .premium-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--premium-text);
            margin: 0;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .premium-subtitle {
            font-size: 0.95rem;
            color: var(--premium-muted);
            margin-top: 6px;
        }

        .premium-divider {
            width: 74px;
            height: 4px;
            border-radius: 999px;
            background: linear-gradient(90deg, var(--premium-primary), var(--premium-secondary), var(--premium-gold));
            margin-top: 14px;
        }

        .premium-main > * {
            position: relative;
            z-index: 1;
        }

        .table,
        .card,
        .form-control,
        .form-select,
        .dropdown-menu,
        .modal-content {
            border-radius: 16px !important;
        }

        .form-control,
        .form-select {
            border: 1px solid rgba(15, 23, 42, 0.10);
            box-shadow: none;
            min-height: 46px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(79, 70, 229, 0.35);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.10);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--premium-primary), var(--premium-secondary));
            border: none;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.18);
        }

        .btn-primary:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        .shadow,
        .shadow-sm {
            box-shadow: var(--premium-shadow-soft) !important;
        }

        @media (max-width: 768px) {
            .premium-header {
                margin: 0 8px;
                border-radius: 0 0 18px 18px;
            }

            .premium-header-inner,
            .premium-main {
                padding-left: 12px;
                padding-right: 12px;
            }

            .premium-title {
                font-size: 1.3rem;
            }

            .premium-section {
                padding: 18px;
            }
        }

        .premium-navbar {
    position: sticky;
    top: 0;
    z-index: 999;
    margin: 14px;
    background: rgba(255, 255, 255, 0.78);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(148, 163, 184, 0.18);
    border-radius: 24px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
}

.premium-logo-wrap {
    width: 54px;
    height: 54px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #ffffff, #eef2ff);
    border: 1px solid rgba(99, 102, 241, 0.12);
    box-shadow: 0 8px 22px rgba(79, 70, 229, 0.10);
    transition: all 0.25s ease;
}

.premium-logo-wrap:hover {
    transform: translateY(-1px);
    box-shadow: 0 12px 28px rgba(79, 70, 229, 0.14);
}

.premium-nav-link {
    position: relative;
    padding: 10px 16px;
    border-radius: 14px;
    font-size: 14px;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    transition: all 0.25s ease;
}

.premium-nav-link:hover {
    color: #4338ca;
    background: rgba(99, 102, 241, 0.08);
}

.premium-nav-link.active {
    color: #312e81;
    background: linear-gradient(135deg, rgba(99,102,241,0.14), rgba(168,85,247,0.10));
    box-shadow: inset 0 0 0 1px rgba(99, 102, 241, 0.08);
}

.premium-user-btn {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 8px 14px 8px 10px;
    border-radius: 18px;
    border: 1px solid rgba(148, 163, 184, 0.18);
    background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(248,250,252,0.92));
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
    transition: all 0.25s ease;
}

.premium-user-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.10);
}

.premium-user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 14px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 15px;
    box-shadow: 0 8px 18px rgba(79, 70, 229, 0.22);
}

.premium-user-name {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.1;
}

.premium-user-role {
    font-size: 12px;
    color: #64748b;
    margin-top: 2px;
}

.premium-mobile-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    border-radius: 14px;
    color: #475569;
    background: #ffffff;
    border: 1px solid rgba(148, 163, 184, 0.18);
    box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
    transition: all 0.25s ease;
}

.premium-mobile-btn:hover {
    color: #4338ca;
    background: #f8fafc;
}

.premium-mobile-menu {
    padding-bottom: 12px;
}

.premium-mobile-link {
    display: block;
    width: 100%;
    padding: 12px 14px;
    border-radius: 14px;
    text-decoration: none;
    color: #475569;
    font-weight: 600;
    transition: all 0.25s ease;
}

.premium-mobile-link:hover {
    background: rgba(99, 102, 241, 0.08);
    color: #4338ca;
}

.premium-mobile-link.active {
    background: linear-gradient(135deg, rgba(99,102,241,0.14), rgba(168,85,247,0.10));
    color: #312e81;
}

@media (max-width: 640px) {
    .premium-navbar {
        margin: 8px;
        border-radius: 18px;
    }
}


    </style>

<style>
        .premium-welcome-card {
    background: rgba(255,255,255,0.9);
    border-radius: 24px;
    padding: 40px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    max-width: 500px;
    width: 100%;
}

.premium-welcome-title {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
}

.premium-welcome-subtitle {
    font-size: 14px;
    color: #64748b;
    margin-top: 8px;
}

.premium-welcome-img {
    max-height: 260px;
    width: auto;
    margin: auto;
}

/* Overlay version */
.premium-image-wrapper {
    position: relative;
    max-width: 500px;
    text-align: center;
}

.premium-overlay-text {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(8px);
    padding: 14px 20px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.premium-overlay-text h2 {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
}

.premium-overlay-text p {
    font-size: 13px;
    color: #555;
    margin: 2px 0 0;
}
</style>
<style>
    
</style>
</head>

<body class="font-sans antialiased">
    @php
        date_default_timezone_set('Asia/Kolkata');
    @endphp

    <div class="premium-page">
        <div class="premium-content">

            @include('layouts.navigation')

            @isset($header)
                <header class="premium-header mt-3">
                    <div class="premium-header-inner">
                        <div class="premium-card">
                            <div class="premium-section">
                                <div class="d-flex flex-column">
                                    <div>
                                        {{ $header }}
                                    </div>
                                    <div class="premium-divider"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
            @endisset

            <main class="premium-main">
                <div class="premium-card">
                    <div class="premium-section">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $('#event_type').on('change', function () {
            let eventTypeId = $(this).val();

            $('#event_category').html('<option value="">Loading...</option>');

            $.ajax({
                url: "{{ route('get.categories') }}",
                type: "GET",
                data: { event_type_id: eventTypeId },
                success: function (response) {
                    let options = '<option value="">All Categories</option>';

                    $.each(response, function (key, category) {
                        options += `<option value="${category.id}">${category.event_category}</option>`;
                    });

                    $('#event_category').html(options);
                }
            });
        });
    </script>

</body>
</html>