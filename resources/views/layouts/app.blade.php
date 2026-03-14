<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Permission Manager')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        pm: {
                            bg: '#f8fafc',
                            surface: '#ffffff',
                            ink: '#0f172a',
                            muted: '#64748b',
                            accent: '#4f46e5',
                            'accent-hover': '#4338ca',
                            stroke: '#e2e8f0',
                            danger: '#dc2626',
                        }
                    }
                },
            },
        }
    </script>

    <style>
        :root {
            --pm-bg: #f8fafc;
            --pm-surface: #ffffff;
            --pm-ink: #0f172a;
            --pm-muted: #64748b;
            --pm-accent: #4f46e5;
            --pm-accent-hover: #4338ca;
            --pm-stroke: #e2e8f0;
            --pm-danger: #dc2626;
        }
        * { box-sizing: border-box; }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--pm-accent);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('head')
</head>
<body class="min-h-screen font-sans" style="background:var(--pm-bg);color:var(--pm-ink)">
    <div class="min-h-screen lg:flex">
        @include('permission-manager::partials._nav')

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-[var(--pm-stroke)] px-6 py-4 flex items-center justify-between">
                <h1 class="text-base font-semibold text-[var(--pm-ink)]">@yield('title', 'Permission Manager')</h1>
                <span class="text-xs text-[var(--pm-muted)] bg-slate-100 px-2 py-1 rounded-md">Permission Manager</span>
            </header>
            <main class="flex-1 px-6 py-6 lg:px-8">
                <div class="max-w-6xl mx-auto">
                    @include('permission-manager::partials._flash')
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
