<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login ke Sistem Kasir Bengkel — Akses khusus pegawai.">
    <title>Login — Sistem Kasir Bengkel</title>

    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#eff6ff',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            700: '#1d4ed8',
                            800: '#1e3a8a',
                            900: '#1e2e5f',
                            950: '#111827',
                        },
                        accent: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass { background: rgba(255,255,255,0.08); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.15); }
        input::placeholder { color: rgba(147,197,253,0.5); }
        input:focus { outline: none; }
    </style>
</head>
<body class="h-full bg-gradient-to-br from-gray-950 via-brand-900 to-brand-800 flex items-center justify-center p-4 min-h-screen">

    {{-- Dekorasi background --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-blue-600/20 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-amber-500/10 blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">

        {{-- Logo / Brand --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-accent-500 shadow-lg shadow-amber-500/30 mb-4">
                <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path fill-rule="evenodd" d="M12 6.75a5.25 5.25 0 0 1 6.775-5.025.75.75 0 0 1 .313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.641l3.318-3.319a.75.75 0 0 1 1.248.313 5.25 5.25 0 0 1-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 1 1 2.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.306Z" clip-rule="evenodd" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-tight">Sistem Kasir Bengkel</h1>
            <p class="text-brand-300 text-sm mt-1">Masuk untuk melanjutkan ke dashboard Anda</p>
        </div>

        {{-- Login Card --}}
        <div class="glass rounded-2xl shadow-2xl p-8">

            {{-- Error dari session (misal dari RoleMiddleware) --}}
            @if(session('error'))
                <div class="mb-5 flex items-start gap-3 bg-red-500/20 border border-red-400/30 rounded-xl p-4" role="alert">
                    <svg class="w-5 h-5 text-red-300 flex-shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm-3.536-9.536a.75.75 0 0 0-1.06 1.061L8.94 10l-1.537 1.536a.75.75 0 1 0 1.06 1.06L10 11.06l1.536 1.537a.75.75 0 1 0 1.06-1.06L11.061 10l1.537-1.536a.75.75 0 0 0-1.06-1.061L10 8.939 8.464 7.403Z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-red-200 text-sm">{{ session('error') }}</p>
                </div>
            @endif

            <form id="login-form" method="POST" action="{{ route('login.store') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-blue-100 mb-2">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3 4a2 2 0 0 0-2 2v1.161l8.441 4.221a1.25 1.25 0 0 0 1.118 0L19 7.162V6a2 2 0 0 0-2-2H3Z" />
                                <path d="m19 8.839-7.77 3.885a2.75 2.75 0 0 1-2.46 0L1 8.839V14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.839Z" />
                            </svg>
                        </div>
                        <input
                            id="email" name="email" type="email"
                            autocomplete="email"
                            value="{{ old('email') }}"
                            placeholder="nama@bengkel.com"
                            class="w-full pl-10 pr-4 py-3 bg-white/10 border {{ $errors->has('email') ? 'border-red-400' : 'border-white/20' }} rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition duration-200"
                        >
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-blue-100 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1zm3 8V5.5a3 3 0 1 0-6 0V9h6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input
                            id="password" name="password" type="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full pl-10 pr-12 py-3 bg-white/10 border {{ $errors->has('password') ? 'border-red-400' : 'border-white/20' }} rounded-xl text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition duration-200"
                        >
                        <button type="button" id="toggle-password"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-blue-400 hover:text-white transition-colors"
                            aria-label="Toggle password">
                            <svg id="eye-show" class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />
                                <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 0 1 0-1.186A10.004 10.004 0 0 1 10 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0 1 10 17c-4.257 0-7.893-2.66-9.336-6.41ZM14 10a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" clip-rule="evenodd" />
                            </svg>
                            <svg id="eye-hide" class="w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3.28 2.22a.75.75 0 0 0-1.06 1.06l14.5 14.5a.75.75 0 1 0 1.06-1.06l-1.745-1.745a10.029 10.029 0 0 0 3.3-4.38 1.651 1.651 0 0 0 0-1.185A10.004 10.004 0 0 0 9.999 3a9.956 9.956 0 0 0-4.744 1.194L3.28 2.22ZM7.752 6.69l1.092 1.092a2.5 2.5 0 0 1 3.374 3.373l1.091 1.092a4 4 0 0 0-5.557-5.557Z" clip-rule="evenodd" />
                                <path d="m10.748 13.93 2.523 2.524a9.987 9.987 0 0 1-3.27.547c-4.258 0-7.894-2.66-9.337-6.41a1.651 1.651 0 0 1 0-1.186A10.007 10.007 0 0 1 2.839 6.02L6.07 9.252a4 4 0 0 0 4.678 4.678Z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button id="btn-login" type="submit"
                    class="w-full py-3 px-6 bg-amber-500 hover:bg-amber-600 active:scale-95 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/30 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    Masuk ke Sistem
                </button>
            </form>
        </div>

        <p class="text-center text-blue-400/60 text-xs mt-6">
            &copy; {{ date('Y') }} Sistem Kasir Bengkel &mdash; Hak akses terbatas
        </p>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggle-password');
        const pwInput   = document.getElementById('password');
        const eyeShow   = document.getElementById('eye-show');
        const eyeHide   = document.getElementById('eye-hide');
        toggleBtn.addEventListener('click', () => {
            const isPw = pwInput.type === 'password';
            pwInput.type = isPw ? 'text' : 'password';
            eyeShow.classList.toggle('hidden', isPw);
            eyeHide.classList.toggle('hidden', !isPw);
        });
    </script>
</body>
</html>
