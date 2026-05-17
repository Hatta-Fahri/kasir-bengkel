<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Kasir Bengkel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 300:'#93c5fd', 400:'#60a5fa', 500:'#3b82f6', 700:'#1d4ed8', 800:'#1e3a8a', 900:'#1e2e5f', 950:'#0f172a' },
                        accent: { 400:'#fbbf24', 500:'#f59e0b', 600:'#d97706' },
                    },
                    fontFamily: { sans: ['Inter','ui-sans-serif','system-ui'] },
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link-active { background:rgba(245,158,11,.15); color:#fbbf24; border-left:2px solid #f59e0b; }
        .sidebar-link { display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:8px; font-size:.875rem; font-weight:500; color:#93c5fd; transition:all .15s; }
        .sidebar-link:hover { background:rgba(255,255,255,.05); color:#fff; }
        .badge { font-size:.65rem; background:rgba(30,58,138,.6); color:#60a5fa; padding:2px 8px; border-radius:9999px; }
    </style>
</head>
<body class="h-full bg-gray-100 flex overflow-hidden">

{{-- ===== SIDEBAR ===== --}}
<aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-brand-950 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static">

    {{-- Brand --}}
    <div class="flex items-center gap-3 px-5 py-4 border-b border-white/10">
        <div class="w-9 h-9 rounded-lg bg-accent-500 flex items-center justify-center shadow flex-shrink-0">
            <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M12 6.75a5.25 5.25 0 0 1 6.775-5.025.75.75 0 0 1 .313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.641l3.318-3.319a.75.75 0 0 1 1.248.313 5.25 5.25 0 0 1-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 1 1 2.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.306Z" clip-rule="evenodd"/>
            </svg>
        </div>
        <div>
            <p class="text-white font-bold text-sm">Kasir Bengkel</p>
            <p class="text-brand-400 text-xs capitalize">{{ auth()->user()->role }}</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

        @if(auth()->user()->isAdmin())
            {{-- ---- ADMIN MENU ---- --}}
            <p class="px-3 pt-2 pb-1.5 text-xs font-semibold text-brand-500 uppercase tracking-widest">Utama</p>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'sidebar-link-active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z" clip-rule="evenodd"/></svg>
                Dashboard
            </a>

            <p class="px-3 pt-4 pb-1.5 text-xs font-semibold text-brand-500 uppercase tracking-widest">Master Data</p>
            <a href="{{ route('admin.spareparts.index') }}" class="sidebar-link {{ request()->routeIs('admin.spareparts.*') ? 'sidebar-link-active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.362 1.093a.75.75 0 0 0-.724 0L2.523 5.018 10 9.143l7.477-4.125-7.115-3.925ZM18 6.443l-7.25 3.998v8.649l6.498-3.582A.75.75 0 0 0 18 14.9V6.443ZM9.25 19.09V10.44L2 6.443V14.9a.75.75 0 0 0 .752.608L9.25 19.09Z"/></svg>
                Master Sparepart
            </a>

            <p class="px-3 pt-4 pb-1.5 text-xs font-semibold text-brand-500 uppercase tracking-widest">Keuangan</p>
            <a href="{{ route('admin.expenses.index') }}" class="sidebar-link {{ request()->routeIs('admin.expenses.*') ? 'sidebar-link-active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M1 4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V4Zm12 4a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM4 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm13-1a1 1 0 1 0-2 0 1 1 0 0 0 2 0Z" clip-rule="evenodd"/></svg>
                Pengeluaran
            </a>
            <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'sidebar-link-active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 0 1 3.5 2h9A1.5 1.5 0 0 1 14 3.5v11.75A2.75 2.75 0 0 0 16.75 18h-12A2.75 2.75 0 0 1 2 15.25V3.5Zm3.75 7a.75.75 0 0 0 0 1.5h4.5a.75.75 0 0 0 0-1.5h-4.5Zm0 3a.75.75 0 0 0 0 1.5h4.5a.75.75 0 0 0 0-1.5h-4.5ZM5 5.75A.75.75 0 0 1 5.75 5h4.5a.75.75 0 0 1 .75.75v2.5a.75.75 0 0 1-.75.75h-4.5A.75.75 0 0 1 5 8.25v-2.5Z" clip-rule="evenodd"/></svg>
                Laporan Keuangan
            </a>

            <p class="px-3 pt-4 pb-1.5 text-xs font-semibold text-brand-500 uppercase tracking-widest">Analitik</p>
            <a href="{{ route('admin.predictions.index') }}" class="sidebar-link {{ request()->routeIs('admin.predictions.*') ? 'sidebar-link-active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M1 2.75A.75.75 0 0 1 1.75 2h16.5a.75.75 0 0 1 0 1.5H18v8.75A2.75 2.75 0 0 1 15.25 15h-1.072l.798 3.06a.75.75 0 0 1-1.452.38L13.41 18H6.59l-.114.44a.75.75 0 0 1-1.452-.38L5.823 15H4.75A2.75 2.75 0 0 1 2 12.25V3.5h-.25A.75.75 0 0 1 1 2.75Z" clip-rule="evenodd"/></svg>
                Prediksi Sparepart
            </a>

        @else
            {{-- ---- KASIR MENU ---- --}}
            <p class="px-3 pt-2 pb-1.5 text-xs font-semibold text-brand-500 uppercase tracking-widest">Utama</p>
            <a href="{{ route('kasir.dashboard') }}" class="sidebar-link {{ request()->routeIs('kasir.dashboard') ? 'sidebar-link-active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z" clip-rule="evenodd"/></svg>
                Dashboard
            </a>

            <p class="px-3 pt-4 pb-1.5 text-xs font-semibold text-brand-500 uppercase tracking-widest">Transaksi</p>
            <a href="{{ route('kasir.transactions.create') }}" class="sidebar-link {{ request()->routeIs('kasir.transactions.create') ? 'sidebar-link-active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M2.879 7.121A3 3 0 0 0 7.5 6.66a2.997 2.997 0 0 0 2.5 1.34 2.997 2.997 0 0 0 2.5-1.34 3 3 0 1 0 4.621-3.78l-1.932-1.932A1.5 1.5 0 0 0 14.128 2H5.872a1.5 1.5 0 0 0-1.06.44L2.879 4.372A3 3 0 0 0 2.879 7.121Z"/><path fill-rule="evenodd" d="M2 10.5a.5.5 0 0 1 .5-.5h15a.5.5 0 0 1 .5.5V17a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-6.5Zm3.75 2.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z" clip-rule="evenodd"/></svg>
                Transaksi Baru
            </a>
            <a href="{{ route('kasir.transactions.index') }}" class="sidebar-link {{ request()->routeIs('kasir.transactions.index') ? 'sidebar-link-active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 0 1 3.5 2h9A1.5 1.5 0 0 1 14 3.5v11.75A2.75 2.75 0 0 0 16.75 18h-12A2.75 2.75 0 0 1 2 15.25V3.5Zm3.75 7a.75.75 0 0 0 0 1.5h4.5a.75.75 0 0 0 0-1.5h-4.5Zm0 3a.75.75 0 0 0 0 1.5h4.5a.75.75 0 0 0 0-1.5h-4.5ZM5 5.75A.75.75 0 0 1 5.75 5h4.5a.75.75 0 0 1 .75.75v2.5a.75.75 0 0 1-.75.75h-4.5A.75.75 0 0 1 5 8.25v-2.5Z" clip-rule="evenodd"/></svg>
                Riwayat Transaksi
            </a>

            <p class="px-3 pt-4 pb-1.5 text-xs font-semibold text-brand-500 uppercase tracking-widest">Informasi</p>
            <a href="{{ route('kasir.spareparts.index') }}" class="sidebar-link {{ request()->routeIs('kasir.spareparts.*') ? 'sidebar-link-active' : '' }}">
                <svg class="w-4 h-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10.362 1.093a.75.75 0 0 0-.724 0L2.523 5.018 10 9.143l7.477-4.125-7.115-3.925ZM18 6.443l-7.25 3.998v8.649l6.498-3.582A.75.75 0 0 0 18 14.9V6.443ZM9.25 19.09V10.44L2 6.443V14.9a.75.75 0 0 0 .752.608L9.25 19.09Z"/></svg>
                Cek Stok
            </a>
        @endif
    </nav>

    {{-- User + Logout --}}
    <div class="border-t border-white/10 p-4">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-8 h-8 rounded-full bg-brand-700 flex items-center justify-center flex-shrink-0">
                <span class="text-white text-xs font-bold uppercase">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <div class="overflow-hidden">
                <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-brand-400 text-xs truncate">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" id="btn-logout"
                class="w-full flex items-center justify-center gap-2 px-3 py-2 text-sm text-brand-300 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-all duration-150">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M6 10a.75.75 0 0 1 .75-.75h9.546l-1.048-.943a.75.75 0 1 1 1.004-1.114l2.5 2.25a.75.75 0 0 1 0 1.114l-2.5 2.25a.75.75 0 1 1-1.004-1.114l1.048-.943H6.75A.75.75 0 0 1 6 10Z" clip-rule="evenodd"/></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- ===== MAIN AREA ===== --}}
<div class="flex-1 flex flex-col overflow-hidden">

    {{-- TOP NAVBAR --}}
    <header class="bg-white border-b border-gray-200 shadow-sm z-20 flex-shrink-0">
        <div class="flex items-center justify-between h-16 px-4 lg:px-6">
            <div class="flex items-center gap-3">
                {{-- Mobile hamburger --}}
                <button id="sidebar-toggle" type="button"
                    class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors"
                    aria-label="Toggle sidebar">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                <h2 class="text-gray-800 font-semibold text-base">@yield('page_title', 'Dashboard')</h2>
            </div>
            <div class="flex items-center gap-3">
                {{-- Notifikasi stok menipis (Admin only) --}}
                @if(auth()->user()->isAdmin())
                    @php $stokMenipisCount = \App\Models\Sparepart::stokMenipis()->count(); @endphp
                    @if($stokMenipisCount > 0)
                    <a href="{{ route('admin.spareparts.index', ['stok_menipis' => 1]) }}"
                       title="{{ $stokMenipisCount }} sparepart stok menipis"
                       class="relative flex items-center justify-center w-9 h-9 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-600 transition-colors">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/></svg>
                        <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] flex items-center justify-center text-[10px] font-bold text-white bg-amber-500 rounded-full px-1">{{ $stokMenipisCount }}</span>
                    </a>
                    @endif
                @endif
                <span class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                    {{ auth()->user()->isAdmin() ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
                <div class="w-8 h-8 rounded-full bg-brand-800 flex items-center justify-center">
                    <span class="text-white text-xs font-bold uppercase">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <span class="hidden md:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
            </div>
        </div>
    </header>

    {{-- Flash Messages --}}
    @if(session('success') || session('error'))
        <div class="px-4 lg:px-6 pt-4 flex-shrink-0">
            @if(session('success'))
                <div role="alert" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div role="alert" class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">
                    <svg class="w-4 h-4 text-red-500 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm-3.536-9.536a.75.75 0 0 0-1.06 1.061L8.94 10l-1.537 1.536a.75.75 0 1 0 1.06 1.06L10 11.06l1.536 1.537a.75.75 0 1 0 1.06-1.061L11.061 10l1.537-1.536a.75.75 0 0 0-1.06-1.06L10 8.939 8.464 7.403Z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>
    @endif

    {{-- CONTENT --}}
    <main class="flex-1 overflow-y-auto p-4 lg:p-6">
        @yield('content')
    </main>
</div>

{{-- Mobile overlay --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 hidden lg:hidden"></div>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const toggleBtn = document.getElementById('sidebar-toggle');
    function openSidebar()  { sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); }
    function closeSidebar() { sidebar.classList.add('-translate-x-full');    overlay.classList.add('hidden'); }
    toggleBtn?.addEventListener('click', () => sidebar.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar());
    overlay?.addEventListener('click', closeSidebar);
    window.addEventListener('resize', () => { if (window.innerWidth >= 1024) closeSidebar(); });
</script>
@stack('scripts')
</body>
</html>
