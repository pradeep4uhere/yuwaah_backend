<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600;700;800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --premium-bg-1: #f8fafc;
            --premium-bg-2: #eef2ff;
            --premium-bg-3: #fdf4ff;
            --premium-text: #0f172a;
            --premium-muted: #64748b;
            --premium-border: rgba(148, 163, 184, 0.18);
            --premium-shadow: 0 20px 50px rgba(15, 23, 42, 0.10);
            --premium-shadow-soft: 0 10px 30px rgba(79, 70, 229, 0.10);
            --premium-primary: #4f46e5;
            --premium-secondary: #7c3aed;
        }

        body {
            font-family: 'Figtree', sans-serif;
            color: var(--premium-text);
            background:
                radial-gradient(circle at top left, rgba(99, 102, 241, 0.12), transparent 30%),
                radial-gradient(circle at bottom right, rgba(168, 85, 247, 0.10), transparent 28%),
                linear-gradient(135deg, var(--premium-bg-1) 0%, var(--premium-bg-2) 45%, var(--premium-bg-3) 100%);
            min-height: 100vh;
        }

        .premium-auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
            position: relative;
            overflow: hidden;
        }

        .premium-auth-page::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(255,255,255,0.45), rgba(255,255,255,0.08));
            pointer-events: none;
        }

        .premium-auth-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 460px;
        }

        .premium-auth-logo {
            width: 96px;
            height: 96px;
            margin: 0 auto 22px;
            border-radius: 28px;
            background: linear-gradient(135deg, rgba(255,255,255,0.96), rgba(238,242,255,0.92));
            border: 1px solid rgba(255,255,255,0.75);
            box-shadow: var(--premium-shadow-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            transition: all 0.25s ease;
        }

        .premium-auth-logo:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 38px rgba(79, 70, 229, 0.14);
        }

        .premium-auth-logo svg,
        .premium-auth-logo img {
            width: 54px;
            height: 54px;
        }

        .premium-auth-brand {
            text-align: center;
            margin-bottom: 18px;
        }

        .premium-auth-title {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--premium-text);
            margin-bottom: 6px;
        }

        .premium-auth-subtitle {
            font-size: 0.95rem;
            color: var(--premium-muted);
            margin: 0;
        }

        .premium-auth-card {
            width: 100%;
            padding: 30px 28px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.82);
            border: 1px solid rgba(255,255,255,0.75);
            box-shadow: var(--premium-shadow);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            overflow: hidden;
        }

        .premium-auth-card::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        /* Optional: improve common auth form controls inside slot */
        .premium-auth-card input[type="email"],
        .premium-auth-card input[type="password"],
        .premium-auth-card input[type="text"],
        .premium-auth-card input[type="number"],
        .premium-auth-card input[type="tel"],
        .premium-auth-card select,
        .premium-auth-card textarea {
            border-radius: 14px !important;
            border: 1px solid rgba(148, 163, 184, 0.22) !important;
            min-height: 46px;
            box-shadow: none !important;
        }

        .premium-auth-card input:focus,
        .premium-auth-card select:focus,
        .premium-auth-card textarea:focus {
            border-color: rgba(79, 70, 229, 0.35) !important;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.10) !important;
        }

        .premium-auth-card button,
        .premium-auth-card .btn,
        .premium-auth-card [type="submit"] {
            border-radius: 14px !important;
        }

        @media (max-width: 640px) {
            .premium-auth-wrapper {
                max-width: 100%;
            }

            .premium-auth-card {
                padding: 22px 18px;
                border-radius: 22px;
            }

            .premium-auth-logo {
                width: 82px;
                height: 82px;
                border-radius: 24px;
            }

            .premium-auth-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="premium-auth-page">
        <div class="premium-auth-wrapper">

            <div class="premium-auth-logo">
                <a href="/">
                    <x-application-logo class="fill-current text-indigo-600" />
                </a>
            </div>

            <div class="premium-auth-brand">
                <h1 class="premium-auth-title">{{ config('app.name', 'Laravel') }}</h1>
                <p class="premium-auth-subtitle">Secure access to your premium dashboard</p>
            </div>

            <div class="premium-auth-card">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>