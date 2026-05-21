<!DOCTYPE html>
<html lang="id" x-data="themeManager()" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" :content="darkMode ? '#0f0a2e' : '#f0f2f5'">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>@yield('title', 'Dashboard') - SISmart</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('js/lang.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        * { font-family: 'Inter', sans-serif; }
        html { scroll-behavior: smooth; }

        /* Hide Edge native password reveal */
        input::-ms-reveal,
        input::-ms-clear { display: none !important; }

        :root {
            --bg-body: #f1f5f9;
            --bg-sidebar: #ffffff;
            --bg-card: #ffffff;
            --bg-input: #f1f5f9;
            --border-color: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --text-label: #334155;
            --hover-bg: rgba(99,102,241,0.06);
        }
        .dark {
            --bg-body: #0f0a2e;
            --bg-sidebar: #150f3a;
            --bg-card: rgba(255,255,255,0.04);
            --bg-input: rgba(255,255,255,0.06);
            --border-color: rgba(255,255,255,0.08);
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --text-label: #e2e8f0;
            --hover-bg: rgba(255,255,255,0.06);
        }

        .sidebar-link.active { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white !important; box-shadow: 0 4px 15px rgba(99,102,241,0.3); }
        .sidebar-link:hover:not(.active) { background: var(--hover-bg); }

        .glass { background: var(--bg-card); border: 1px solid var(--border-color); }
        .dark .glass { backdrop-filter: blur(20px); }

        /* Stat card — NO shadow by default, only on desktop hover */
        .stat-card { transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
        @media (min-width: 1024px) {
            .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(99,102,241,0.12); }
            .dark .stat-card:hover { box-shadow: 0 12px 30px rgba(0,0,0,0.3); }
        }

        .gradient-text { background: linear-gradient(135deg, #6366f1, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .pulse-dot { animation: pulse-dot 2s infinite; }
        @keyframes pulse-dot { 0%,100% { opacity:1; } 50% { opacity:0.3; } }

        /* Form styling */
        .form-input {
            width: 100%; border-radius: 0.75rem; padding: 0.6rem 0.875rem; font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: var(--bg-input); border: 1px solid var(--border-color); color: var(--text-primary);
        }
        .form-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.12); }
        .form-input::placeholder { color: var(--text-muted); }
        .form-label { display:block; font-size:0.8125rem; font-weight:500; color: var(--text-label); margin-bottom:0.375rem; }
        .form-label i { margin-right: 0.25rem; }

        /* Buttons — NO shadow on mobile, shadow only on sm+ */
        .btn-primary { background: linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; padding:0.6rem 1.25rem; border-radius:0.75rem; font-weight:500; font-size:0.875rem; transition:all .2s; display:inline-flex; align-items:center; gap:0.5rem; white-space:nowrap; }
        .btn-success { background: linear-gradient(135deg,#10b981,#059669); color:#fff; padding:0.6rem 1.25rem; border-radius:0.75rem; font-weight:500; font-size:0.875rem; transition:all .2s; display:inline-flex; align-items:center; gap:0.5rem; white-space:nowrap; }
        .btn-danger  { background: linear-gradient(135deg,#ef4444,#dc2626); color:#fff; padding:0.6rem 1.25rem; border-radius:0.75rem; font-weight:500; font-size:0.875rem; transition:all .2s; display:inline-flex; align-items:center; gap:0.5rem; white-space:nowrap; }
        .btn-outline { background:transparent; border:1px solid var(--border-color); color:var(--text-secondary); padding:0.6rem 1.25rem; border-radius:0.75rem; font-weight:500; font-size:0.875rem; transition:all .2s; display:inline-flex; align-items:center; gap:0.5rem; white-space:nowrap; }
        .btn-outline:hover { border-color:#6366f1; color:#6366f1; }
        @media (min-width: 640px) {
            .btn-primary { box-shadow:0 4px 12px rgba(99,102,241,0.25); }
            .btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 16px rgba(99,102,241,0.35); }
            .btn-success { box-shadow:0 4px 12px rgba(16,185,129,0.25); }
            .btn-danger  { box-shadow:0 4px 12px rgba(239,68,68,0.25); }
        }

        .table-responsive { overflow-x:auto; -webkit-overflow-scrolling:touch; }
        .sidebar-overlay { background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); }

        ::-webkit-scrollbar { width:5px; height:5px; }
        ::-webkit-scrollbar-track { background: var(--bg-body); }
        ::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius:3px; }
        .dark ::-webkit-scrollbar-thumb { background: #4338ca; }

        @media print { .no-print { display:none !important; } body { background:#fff !important; color:#000 !important; } .glass { border:1px solid #ddd !important; background:#fff !important; } }
        @supports (padding: max(0px)) { .mobile-safe { padding-bottom: max(0.75rem, env(safe-area-inset-bottom)); } }

        /* Back to top */
        .back-to-top { opacity:0; transform:translateY(20px); transition:all .3s; pointer-events:none; }
        .back-to-top.visible { opacity:1; transform:translateY(0); pointer-events:auto; }

        /* Mobile layout fixes */
        @media (max-width: 640px) {
            .glass { border-radius: 1rem; }
        }

        /* Language toggle styles */
        .lang-toggle { transition: all 0.3s ease; }
        .lang-toggle-dot { transition: transform 0.3s ease; }
    </style>
</head>
<body class="min-h-screen transition-colors duration-300" style="background:var(--bg-body); color:var(--text-primary);"
      x-data="{ sidebarOpen: window.innerWidth >= 1024, mobileMenu: false }"
      @resize.window="sidebarOpen = window.innerWidth >= 1024; if(window.innerWidth >= 1024) mobileMenu = false">

    <div class="flex min-h-screen">
        {{-- Mobile overlay --}}
        <div x-show="mobileMenu" x-transition.opacity @click="mobileMenu = false" class="fixed inset-0 z-40 sidebar-overlay lg:hidden no-print" x-cloak></div>

        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-50 flex flex-col transition-all duration-300 no-print border-r"
               :class="[ mobileMenu ? 'translate-x-0 w-72' : '-translate-x-full lg:translate-x-0', sidebarOpen ? 'lg:w-64' : 'lg:w-20' ]"
               style="background:var(--bg-sidebar); border-color:var(--border-color);">
            <div class="flex items-center h-16 px-4 border-b" style="border-color:var(--border-color);">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-400 flex items-center justify-center shadow-lg shadow-indigo-500/30 shrink-0">
                        <i class="fas fa-boxes-stacked text-white text-lg"></i>
                    </div>
                    <div x-show="sidebarOpen || mobileMenu" x-transition class="overflow-hidden min-w-0">
                        <h1 class="text-lg font-bold gradient-text leading-tight">SISmart</h1>
                        <p class="text-[10px] -mt-0.5" style="color:var(--text-muted)" data-lang="nav.smart_inventory">Smart Inventory</p>
                    </div>
                </div>
                <button @click="mobileMenu = false" class="ml-auto lg:hidden p-2" style="color:var(--text-muted)"><i class="fas fa-times"></i></button>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                @php
                    $nav = [
                        ['nav.menu_utama', 'Menu Utama', [
                            ['dashboard', 'fas fa-th-large', 'nav.dashboard', 'Dashboard', 'dashboard'],
                        ]],
                        ['nav.master_data', 'Master Data', [
                            ['kategori.*', 'fas fa-tags', 'nav.kategori', 'Kategori', 'kategori.index'],
                            ['barang.*', 'fas fa-cubes', 'nav.data_barang', 'Data Barang', 'barang.index'],
                        ]],
                        ['nav.transaksi', 'Transaksi', [
                            ['barang-masuk.*', 'fas fa-arrow-down text-emerald-500', 'nav.barang_masuk', 'Barang Masuk', 'barang-masuk.index'],
                            ['barang-keluar.*', 'fas fa-arrow-up text-rose-500', 'nav.barang_keluar', 'Barang Keluar', 'barang-keluar.index'],
                        ]],
                        ['nav.laporan', 'Laporan', [
                            ['laporan.stok', 'fas fa-warehouse', 'nav.laporan_stok', 'Laporan Stok', 'laporan.stok'],
                            ['laporan.transaksi', 'fas fa-exchange-alt', 'nav.laporan_transaksi', 'Laporan Transaksi', 'laporan.transaksi'],
                            ['laporan.perputaran', 'fas fa-sync-alt', 'nav.perputaran_stok', 'Perputaran Stok', 'laporan.perputaran'],
                        ]],
                        ['nav.keuangan', 'Keuangan', [
                            ['keuangan.jurnal', 'fas fa-book', 'nav.jurnal_umum', 'Jurnal Umum', 'keuangan.jurnal'],
                            ['keuangan.laba-rugi', 'fas fa-chart-line', 'nav.laba_rugi', 'Laba Rugi', 'keuangan.laba-rugi'],
                            ['keuangan.neraca', 'fas fa-balance-scale', 'nav.neraca', 'Neraca', 'keuangan.neraca'],
                        ]],
                        ['nav.bantuan', 'Bantuan', [
                            ['manual-book', 'fas fa-book-reader', 'nav.manual_book', 'Manual Book', 'manual-book'],
                            ['tentang-kami', 'fas fa-users', 'nav.tentang_kami', 'Tentang Kami', 'tentang-kami'],
                        ]],
                    ];
                @endphp
                @foreach($nav as [$labelKey, $labelFallback, $items])
                    <p class="px-3 text-[10px] uppercase tracking-widest mb-2 {{ !$loop->first ? 'mt-5' : '' }}" style="color:var(--text-muted)" x-show="sidebarOpen || mobileMenu" data-lang="{{ $labelKey }}">{{ $labelFallback }}</p>
                    @foreach($items as [$route, $icon, $textKey, $textFallback, $href])
                        <a href="{{ route($href) }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs($route) ? 'active' : '' }}" style="{{ !request()->routeIs($route) ? 'color:var(--text-secondary)' : '' }}">
                            <i class="{{ $icon }} w-5 text-center"></i>
                            <span x-show="sidebarOpen || mobileMenu" data-lang="{{ $textKey }}">{{ $textFallback }}</span>
                        </a>
                    @endforeach
                @endforeach
            </nav>

            <div class="p-3 border-t mobile-safe" style="border-color:var(--border-color);">
                <div class="flex items-center gap-3 px-3 py-2">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-sm font-bold text-white shrink-0">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <div x-show="sidebarOpen || mobileMenu" class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[10px]" style="color:var(--text-muted)" data-lang="nav.administrator">Administrator</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" x-show="sidebarOpen || mobileMenu">@csrf
                        <button type="submit" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm transition-all duration-200 hover:bg-rose-500/15 hover:text-rose-500 group" style="color:var(--text-muted)" title="Logout">
                            <i class="fas fa-sign-out-alt transition-transform duration-200 group-hover:translate-x-0.5"></i>
                            <span class="text-xs font-medium" data-lang="nav.keluar">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main --}}
        <main class="flex-1 transition-all duration-300 lg:ml-64" :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-20'">
            <header class="sticky top-0 z-30 h-16 flex items-center justify-between px-4 sm:px-6 no-print border-b transition-colors duration-300" style="background:var(--bg-body); border-color:var(--border-color);">
                <div class="flex items-center gap-3">
                    <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 -ml-2 rounded-lg transition-colors" style="color:var(--text-secondary)"><i class="fas fa-bars text-lg"></i></button>
                    <button @click="sidebarOpen = !sidebarOpen" class="hidden lg:block transition-colors" style="color:var(--text-secondary)"><i class="fas fa-bars text-lg"></i></button>
                    <h2 class="text-base sm:text-lg font-semibold truncate">@yield('title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-1.5 sm:gap-2">
                    {{-- Quick Search --}}
                    <button @click="$dispatch('open-search')" class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs border transition-colors" style="color:var(--text-muted); border-color:var(--border-color)" title="Ctrl+K">
                        <i class="fas fa-search"></i> <span data-lang="header.cari">Cari...</span> <kbd class="ml-1 px-1.5 py-0.5 rounded text-[10px] border" style="border-color:var(--border-color)">⌘K</kbd>
                    </button>

                    {{-- Language Toggle --}}
                    <button @click="switchLang()" class="relative flex items-center gap-1 sm:gap-1.5 px-2 sm:px-2.5 py-1.5 rounded-xl transition-all hover:bg-indigo-500/10 lang-toggle" :title="lang === 'id' ? 'Switch to English' : 'Ganti ke Bahasa Indonesia'">
                        <span class="text-[10px] sm:text-xs font-bold transition-all duration-300" :class="lang === 'id' ? 'text-indigo-600 dark:text-indigo-400' : 'opacity-30'" style="line-height:1">ID</span>
                        <div class="w-7 sm:w-8 h-3.5 sm:h-4 rounded-full relative transition-colors duration-300" :class="lang === 'en' ? 'bg-indigo-500' : 'bg-gray-300 dark:bg-gray-600'">
                            <div class="w-2.5 sm:w-3 h-2.5 sm:h-3 rounded-full bg-white shadow-sm absolute top-0.5 transition-transform duration-300 lang-toggle-dot" :class="lang === 'en' ? 'translate-x-3.5 sm:translate-x-4' : 'translate-x-0.5'"></div>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold transition-all duration-300" :class="lang === 'en' ? 'text-indigo-600 dark:text-indigo-400' : 'opacity-30'" style="line-height:1">EN</span>
                    </button>

                    {{-- Theme Toggle --}}
                    <button @click="toggleTheme()" class="relative w-10 h-10 flex items-center justify-center rounded-xl transition-all hover:bg-indigo-500/10" data-lang-title="header.ganti_tema" title="Ganti Tema">
                        <i x-show="darkMode" class="fas fa-sun text-amber-400 text-lg" x-transition></i>
                        <i x-show="!darkMode" class="fas fa-moon text-indigo-600 text-lg" x-transition x-cloak></i>
                    </button>

                    {{-- Fullscreen --}}
                    <button x-data="{fs:false}" @click="fs=!fs; fs ? document.documentElement.requestFullscreen() : document.exitFullscreen()" class="hidden sm:flex w-10 h-10 items-center justify-center rounded-xl transition-all hover:bg-indigo-500/10" style="color:var(--text-muted)" title="Fullscreen">
                        <i class="fas" :class="fs ? 'fa-compress' : 'fa-expand'"></i>
                    </button>

                    {{-- Live Clock --}}
                    <span x-data="{time:'',init(){this.tick();setInterval(()=>this.tick(),1000)},tick(){const d=new Date();const dd=String(d.getDate()).padStart(2,'0');const mm=['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'][d.getMonth()];const hh=String(d.getHours()).padStart(2,'0');const mi=String(d.getMinutes()).padStart(2,'0');this.time=dd+' '+mm+' '+d.getFullYear()+' • '+hh+':'+mi}}" class="text-[11px] hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg" style="color:var(--text-muted);background:var(--hover-bg)">
                        <i class="fas fa-clock text-[10px]"></i> <span x-text="time"></span>
                    </span>
                </div>
            </header>

            <div class="p-4 sm:p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center gap-3 text-emerald-700 dark:text-emerald-400" x-data="{ show:true }" x-show="show" x-transition>
                        <i class="fas fa-check-circle text-lg shrink-0"></i><span class="flex-1 text-sm">{{ session('success') }}</span>
                        <button @click="show=false" class="shrink-0 opacity-60 hover:opacity-100"><i class="fas fa-times"></i></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 flex items-center gap-3 text-rose-700 dark:text-rose-400" x-data="{ show:true }" x-show="show" x-transition>
                        <i class="fas fa-exclamation-circle text-lg shrink-0"></i><span class="flex-1 text-sm">{{ session('error') }}</span>
                        <button @click="show=false" class="shrink-0 opacity-60 hover:opacity-100"><i class="fas fa-times"></i></button>
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-4 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-700 dark:text-rose-400">
                        <ul class="list-disc list-inside text-sm space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Quick Search Modal --}}
    <div x-data="{ open: false }" @open-search.window="open = true" @keydown.window.ctrl.k.prevent="open = true" @keydown.window.meta.k.prevent="open = true" @keydown.escape.window="open = false" x-show="open" x-cloak class="fixed inset-0 z-[60] flex items-start justify-center pt-[15vh] px-4">
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="open = false"></div>
        <div x-show="open" x-transition.scale.origin.top class="relative w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden" style="background:var(--bg-sidebar); border:1px solid var(--border-color);">
            <div class="flex items-center gap-3 px-5 py-4 border-b" style="border-color:var(--border-color)">
                <i class="fas fa-search text-indigo-500"></i>
                <input type="text" class="flex-1 bg-transparent outline-none text-sm" style="color:var(--text-primary)" autofocus
                    data-lang-placeholder="search.placeholder" placeholder="Cari halaman, barang, laporan..."
                    x-ref="searchInput" @keydown.enter="
                        let q = $refs.searchInput.value.toLowerCase();
                        let routes = {dashboard:'{{ route("dashboard") }}',barang:'{{ route("barang.index") }}',kategori:'{{ route("kategori.index") }}',masuk:'{{ route("barang-masuk.index") }}',keluar:'{{ route("barang-keluar.index") }}',stok:'{{ route("laporan.stok") }}',transaksi:'{{ route("laporan.transaksi") }}',perputaran:'{{ route("laporan.perputaran") }}',jurnal:'{{ route("keuangan.jurnal") }}','laba rugi':'{{ route("keuangan.laba-rugi") }}',neraca:'{{ route("keuangan.neraca") }}',items:'{{ route("barang.index") }}',categories:'{{ route("kategori.index") }}',stock:'{{ route("laporan.stok") }}',journal:'{{ route("keuangan.jurnal") }}',income:'{{ route("keuangan.laba-rugi") }}',balance:'{{ route("keuangan.neraca") }}'};
                        for(let [k,v] of Object.entries(routes)) { if(k.includes(q)) { window.location.href=v; break; } }
                    ">
                <kbd class="px-2 py-1 rounded text-[10px] border" style="color:var(--text-muted); border-color:var(--border-color)">ESC</kbd>
            </div>
            <div class="p-3 text-xs" style="color:var(--text-muted)">
                <p><i class="fas fa-lightbulb text-amber-500 mr-1"></i> <span data-lang="search.hint">Ketik nama halaman lalu Enter. Contoh: "barang", "jurnal", "neraca"</span></p>
            </div>
        </div>
    </div>

    {{-- Keyboard Shortcuts Modal (press ?) --}}
    <div x-data="{ open: false }" @keydown.window.slash.prevent="if(!$event.ctrlKey && !$event.metaKey && $event.target.tagName!=='INPUT' && $event.target.tagName!=='TEXTAREA'){open=true}" @keydown.escape.window="open=false" x-show="open" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center px-4">
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="open=false"></div>
        <div x-show="open" x-transition.scale class="relative w-full max-w-md rounded-2xl shadow-2xl overflow-hidden" style="background:var(--bg-sidebar);border:1px solid var(--border-color)">
            <div class="flex items-center justify-between px-5 py-4 border-b" style="border-color:var(--border-color)">
                <h3 class="text-sm font-bold flex items-center gap-2" style="color:var(--text-primary)"><i class="fas fa-keyboard text-indigo-500"></i> <span data-lang="shortcut.title">Pintasan Keyboard</span></h3>
                <button @click="open=false" class="text-sm" style="color:var(--text-muted)"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-5 space-y-3 text-sm">
                @foreach([
                    ['Ctrl + K','shortcut.search','Pencarian cepat','fa-search'],
                    ['?','shortcut.keyboard','Pintasan keyboard','fa-keyboard'],
                    ['Esc','shortcut.close','Tutup modal','fa-times-circle'],
                ] as $s)
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-2" style="color:var(--text-secondary)"><i class="fas {{ $s[3] }} text-xs text-indigo-400 w-4"></i> <span data-lang="{{ $s[1] }}">{{ $s[2] }}</span></span>
                    <kbd class="px-2.5 py-1 rounded-lg text-[11px] font-mono font-semibold" style="color:var(--text-muted);background:var(--bg-input);border:1px solid var(--border-color)">{{ $s[0] }}</kbd>
                </div>
                @endforeach
            </div>
            <div class="px-5 py-3 border-t text-[11px]" style="border-color:var(--border-color);color:var(--text-muted)">
                <i class="fas fa-lightbulb text-amber-500 mr-1"></i> <span data-lang="shortcut.hint">Tekan</span> <kbd class="px-1.5 py-0.5 rounded text-[10px] font-mono" style="background:var(--bg-input);border:1px solid var(--border-color)">?</kbd> <span data-lang="shortcut.hint2">kapan saja untuk melihat pintasan ini</span>
            </div>
        </div>
    </div>
    {{-- Global Delete Confirmation Modal --}}
    <div x-data="{ open: false, form: null, message: '' }"
         x-init="message = t('delete.message', lang)"
         @open-delete-modal.window="open = true; form = $event.detail.form; message = $event.detail.message || t('delete.message', lang)"
         @keydown.escape.window="open = false"
         class="relative z-[100] no-print" x-cloak>
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div x-show="open" x-transition.scale.origin.center class="fixed inset-0 flex items-center justify-center p-4">
            <div @click.outside="open = false" class="w-full max-w-sm rounded-2xl shadow-2xl p-6 text-center transform transition-all" style="background:var(--bg-card);border:1px solid var(--border-color)">
                <div class="w-16 h-16 rounded-full bg-rose-500/10 text-rose-500 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-trash-alt text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold mb-2" style="color:var(--text-primary)" data-lang="delete.title">Konfirmasi Hapus</h3>
                <p class="text-sm mb-6" style="color:var(--text-secondary)" x-text="message"></p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button @click="open = false" type="button" class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-medium transition-colors hover:opacity-80" style="background:var(--bg-input);color:var(--text-primary);border:1px solid var(--border-color)" data-lang="delete.cancel">Batal</button>
                    <button @click="form.submit()" type="button" class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-medium bg-rose-500 text-white shadow-lg shadow-rose-500/30 hover:bg-rose-600 transition-colors" data-lang="delete.confirm">Ya, Hapus!</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function themeManager() {
            return {
                darkMode: localStorage.getItem('sismart-theme') === 'dark' || (!localStorage.getItem('sismart-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
                lang: localStorage.getItem('sismart-lang') || 'id',
                init() {
                    this.$watch('darkMode', val => localStorage.setItem('sismart-theme', val ? 'dark' : 'light'));
                    // Apply language on load
                    this.$nextTick(() => applyLang(this.lang));
                },
                toggleTheme() { this.darkMode = !this.darkMode; },
                switchLang() {
                    this.lang = this.lang === 'id' ? 'en' : 'id';
                    localStorage.setItem('sismart-lang', this.lang);
                    applyLang(this.lang);
                    // Sync to backend session for PDF/Excel exports
                    fetch('/set-lang/' + this.lang, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    }).catch(() => {});
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
