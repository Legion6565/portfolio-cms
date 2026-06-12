<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Portfolio CMS' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">

@if(session('success'))

    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition
        class="fixed top-6 right-6 z-[999]"
    >

        <div class="bg-slate-900 text-white px-6 py-4 rounded-2xl shadow-2xl">

            {{ session('success') }}

        </div>

    </div>

@endif

<nav class="bg-white/80 backdrop-blur sticky top-0 z-50 border-b border-slate-200">

    <div class="max-w-7xl mx-auto px-6">

        <div class="flex justify-between items-center h-16">

            <a href="/"
               class="flex items-center gap-3 font-bold text-xl">

                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600"></div>

                Portfolio CMS

            </a>

            <div class="flex items-center gap-4">

                <a href="/about"
                   class="text-slate-600 hover:text-slate-900 transition">
                    Обо мне
                </a>

                @auth

                    <a href="/admin"
                       class="bg-slate-900 text-white px-4 py-2 rounded-xl hover:bg-slate-700 transition">
                        Админка
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="border border-slate-300 px-4 py-2 rounded-xl hover:bg-slate-100 transition"
                        >
                            Выйти
                        </button>

                    </form>

                @else

                    <a href="/login"
                       class="bg-slate-900 text-white px-4 py-2 rounded-xl hover:bg-slate-700 transition">
                        Войти
                    </a>

                @endauth

            </div>

        </div>

    </div>

</nav>

<main>

    @yield('content')

</main>

</body>
</html>