<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: localStorage.getItem('sismart-theme')==='dark'||(!localStorage.getItem('sismart-theme')&&window.matchMedia('(prefers-color-scheme:dark)').matches), toggle(){ this.darkMode=!this.darkMode; localStorage.setItem('sismart-theme',this.darkMode?'dark':'light'); }, lang: localStorage.getItem('sismart-lang')||'id', switchLang(){ this.lang=this.lang==='id'?'en':'id'; localStorage.setItem('sismart-lang',this.lang); applyLang(this.lang); fetch('/set-lang/'+this.lang,{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Accept':'application/json'}}).catch(()=>{}); } }" :class="{ 'dark': darkMode }" x-init="$nextTick(()=>applyLang(lang))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — Kedana Kedini</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="{{ asset('js/lang.js') }}"></script>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        [x-cloak]{display:none!important}
        *{font-family:'Inter',system-ui,sans-serif;box-sizing:border-box;margin:0;padding:0}
        input::-ms-reveal,input::-ms-clear{display:none!important}

        /* ══════ WRAPPER ══════ */
        .login-wrap{display:flex;min-height:100vh;min-height:100dvh;transition:background .4s}

        /* ══════ LEFT PANEL ══════ */
        .left-panel{
            display:none;position:relative;overflow:hidden;
            width:50%;flex-shrink:0;
            padding:3rem;
            align-items:center;justify-content:center;
        }
        @media(min-width:1024px){.left-panel{display:flex}}
        .left-panel{background:linear-gradient(160deg,#4338ca 0%,#6366f1 40%,#818cf8 100%)}
        .dark .left-panel{background:linear-gradient(160deg,#0f0a2e 0%,#1a1145 40%,#312e81 100%)}

        .left-panel::before{
            content:'';position:absolute;inset:0;
            background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        /* Floating shapes */
        .shape{position:absolute;border-radius:50%;opacity:.12;animation:drift 10s ease-in-out infinite}
        .shape-1{width:300px;height:300px;background:#c7d2fe;top:-50px;left:-50px;animation-delay:0s}
        .shape-2{width:200px;height:200px;background:#a5b4fc;bottom:10%;right:-30px;animation-delay:3s}
        .shape-3{width:160px;height:160px;background:#e0e7ff;top:40%;left:15%;animation-delay:6s}
        .dark .shape{opacity:.06}
        @keyframes drift{0%,100%{transform:translate(0,0) scale(1)}50%{transform:translate(20px,-15px) scale(1.08)}}

        /* Left content */
        .left-content{position:relative;z-index:2;text-center;max-width:400px;color:#fff}
        .left-content h2{font-size:2rem;font-weight:800;line-height:1.2;margin-bottom:.75rem}
        .left-content p{font-size:.9rem;line-height:1.7;color:rgba(255,255,255,.7)}

        .feature-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-top:2.5rem}
        .feature-card{
            background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.1);
            border-radius:1rem;padding:1.25rem .75rem;text-align:center;
            transition:all .3s;backdrop-filter:blur(8px);
        }
        .feature-card:hover{background:rgba(255,255,255,.14);transform:translateY(-3px)}
        .feature-card i{font-size:1.25rem;margin-bottom:.5rem;display:block}
        .feature-card span{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:rgba(255,255,255,.7)}

        /* ══════ RIGHT PANEL ══════ */
        .right-panel{
            flex:1;display:flex;align-items:center;justify-content:center;
            padding:2rem 1.5rem;position:relative;transition:background .4s;
        }
        .right-panel{background:#ffffff}
        .dark .right-panel{background:#0f0a2e}

        /* ══════ FORM STYLES ══════ */
        .form-wrap{width:100%;max-width:400px}
        .f-label{display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;margin-bottom:8px;color:#374151}
        .f-label i{font-size:11px;color:#6366f1}
        .dark .f-label{color:#c7d2fe}
        .dark .f-label i{color:#a5b4fc}
        .f-input{
            width:100%;padding:12px 16px;font-size:14px;
            border:1.5px solid #d1d5db;border-radius:12px;
            background:#f9fafb;color:#111827;outline:none;transition:all .2s;
        }
        .f-input:focus{border-color:#6366f1;box-shadow:0 0 0 4px rgba(99,102,241,.1);background:#fff}
        .f-input::placeholder{color:#9ca3af}
        .dark .f-input{background:rgba(255,255,255,.05);border-color:rgba(255,255,255,.1);color:#f1f5f9}
        .dark .f-input:focus{border-color:#818cf8;box-shadow:0 0 0 4px rgba(129,140,248,.12);background:rgba(255,255,255,.08)}
        .dark .f-input::placeholder{color:#6b7280}

        .eye-btn{position:absolute;right:0;top:0;bottom:0;width:48px;display:flex;align-items:center;justify-content:center;background:none;border:none;cursor:pointer;color:#9ca3af;transition:color .2s}
        .eye-btn:hover{color:#6366f1}
        .dark .eye-btn{color:#6b7280}
        .dark .eye-btn:hover{color:#a5b4fc}

        .submit-btn{
            width:100%;padding:14px;border:none;border-radius:12px;
            font-size:14px;font-weight:700;color:#fff;cursor:pointer;
            background:linear-gradient(135deg,#6366f1,#8b5cf6);
            box-shadow:0 8px 24px rgba(99,102,241,.3);
            transition:all .25s;display:flex;align-items:center;justify-content:center;gap:8px;
        }
        .submit-btn:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(99,102,241,.4)}
        .submit-btn:active{transform:translateY(0)}

        .sub-text{color:#64748b}.dark .sub-text{color:#94a3b8}
        .foot-text{color:#94a3b8}.dark .foot-text{color:#4b5563}
        .cb-text{color:#4b5563;font-size:13px}.dark .cb-text{color:#9ca3af}
        .sep{border-top:1px solid #e5e7eb}.dark .sep{border-color:rgba(255,255,255,.06)}

        .theme-btn{
            position:absolute;top:1.25rem;right:1.25rem;z-index:50;
            width:42px;height:42px;border-radius:12px;border:none;cursor:pointer;
            display:flex;align-items:center;justify-content:center;font-size:17px;
            transition:all .2s;
        }
        .theme-btn{background:rgba(99,102,241,.08);color:#6366f1}
        .theme-btn:hover{background:rgba(99,102,241,.15);transform:scale(1.05)}
        .dark .theme-btn{background:rgba(255,255,255,.06);color:#fbbf24;border:1px solid rgba(255,255,255,.08)}

        .lang-btn{
            position:absolute;top:1.25rem;right:4.5rem;z-index:50;
            height:42px;border-radius:12px;border:none;cursor:pointer;
            display:flex;align-items:center;justify-content:center;gap:6px;
            padding:0 12px;font-size:12px;font-weight:700;
            transition:all .2s;
        }
        .lang-btn{background:rgba(99,102,241,.08);color:#6366f1}
        .lang-btn:hover{background:rgba(99,102,241,.15);transform:scale(1.05)}
        .dark .lang-btn{background:rgba(255,255,255,.06);color:#a5b4fc;border:1px solid rgba(255,255,255,.08)}

        @keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
        .fade-up{animation:fadeUp .5s ease-out}

        @supports(padding:max(0px)){.safe-b{padding-bottom:max(1rem,env(safe-area-inset-bottom))}}

        /* Mobile branding */
        .mobile-brand{display:none;text-align:center;margin-bottom:2rem}
        @media(max-width:1023px){.mobile-brand{display:block}}
    </style>
</head>
<body>
<div class="login-wrap" x-cloak>
    <!-- ══════════ LEFT — BRANDING ══════════ -->
    <div class="left-panel">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>

        <div class="left-content">
            <div style="margin-bottom:2rem">
                <div style="width:72px;height:72px;border-radius:20px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.15);margin:0 auto 1.5rem;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(10px)">
                    <i class="fas fa-boxes-stacked" style="font-size:30px;color:#fff"></i>
                </div>
                <h2>Smart Inventory<br>System</h2>
                <p><span data-lang="login.kelola">Kelola persediaan barang dengan cerdas.</span><br><span data-lang="login.kelola2">Pantau stok, transaksi, dan keuangan dalam satu platform terintegrasi.</span></p>
            </div>

            <div class="feature-grid">
                <div class="feature-card">
                    <i class="fas fa-warehouse text-indigo-200"></i>
                    <span data-lang="login.inventori">Inventori</span>
                </div>
                <div class="feature-card">
                    <i class="fas fa-chart-line text-cyan-200"></i>
                    <span data-lang="login.analisis">Analisis</span>
                </div>
                <div class="feature-card">
                    <i class="fas fa-coins text-amber-200"></i>
                    <span data-lang="login.keuangan">Keuangan</span>
                </div>
            </div>

            <p style="margin-top:3rem;font-size:.7rem;color:rgba(255,255,255,.3);letter-spacing:1px">&copy; {{ date('Y') }} Kedana Kedini — All rights reserved</p>
        </div>
    </div>

    <!-- ══════════ RIGHT — FORM ══════════ -->
    <div class="right-panel safe-b">
        {{-- Language Toggle --}}
        <button @click="switchLang()" class="lang-btn" :title="lang === 'id' ? 'Switch to English' : 'Ganti ke Bahasa Indonesia'">
            <i class="fas fa-globe"></i>
            <span x-text="lang === 'id' ? 'EN' : 'ID'"></span>
        </button>

        <button @click="toggle()" class="theme-btn">
            <i x-show="darkMode" class="fas fa-sun" x-transition></i>
            <i x-show="!darkMode" class="fas fa-moon" x-transition x-cloak></i>
        </button>

        <div class="form-wrap fade-up">
            <!-- Mobile brand (shown only < 1024px) -->
            <div class="mobile-brand">
                <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#6366f1,#06b6d4);margin:0 auto 1rem;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(99,102,241,.25)">
                    <i class="fas fa-boxes-stacked" style="color:#fff;font-size:22px"></i>
                </div>
                <h1 style="font-size:24px;font-weight:800">
                    <span style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent">Kedana Kedini</span>
                </h1>
                <p class="sub-text" style="font-size:13px;margin-top:4px">Smart Inventory System</p>
            </div>

            <!-- Desktop title -->
            <div class="hidden lg:block" style="margin-bottom:2.5rem">
                <h1 style="font-size:26px;font-weight:800;letter-spacing:-.5px">
                    <span style="background:linear-gradient(135deg,#6366f1,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent" data-lang="login.selamat_datang">Selamat Datang</span>
                </h1>
                <p class="sub-text" style="font-size:14px;margin-top:6px" data-lang="login.subtitle">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            <!-- Error -->
            @if($errors->any())
            <div style="margin-bottom:1.5rem;padding:14px 16px;border-radius:12px;font-size:13px;display:flex;align-items:flex-start;gap:10px;border:1px solid rgba(239,68,68,.2);background:rgba(239,68,68,.06);color:#dc2626">
                <i class="fas fa-exclamation-circle" style="margin-top:2px;flex-shrink:0"></i>
                <span style="font-weight:500">{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" autocomplete="on">
                @csrf

                <div style="margin-bottom:1.25rem">
                    <label class="f-label"><i class="fas fa-envelope"></i> <span data-lang="login.email">Email</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="nama@perusahaan.com" class="f-input">
                </div>

                <div style="margin-bottom:1.25rem" x-data="{show:false}">
                    <label class="f-label"><i class="fas fa-lock"></i> <span data-lang="login.password">Password</span></label>
                    <div style="position:relative">
                        <input :type="show?'text':'password'" name="password" required autocomplete="current-password" placeholder="••••••••" class="f-input" style="padding-right:48px">
                        <button type="button" @click="show=!show" class="eye-btn" tabindex="-1">
                            <i :class="show?'fas fa-eye-slash':'fas fa-eye'" style="font-size:14px"></i>
                        </button>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:8px;margin-bottom:1.75rem">
                    <input type="checkbox" name="remember" id="remember" style="width:16px;height:16px;accent-color:#6366f1;cursor:pointer">
                    <label for="remember" class="cb-text" style="cursor:pointer;user-select:none" data-lang="login.ingat_saya">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fas fa-right-to-bracket"></i> <span data-lang="login.masuk">Masuk Sistem</span>
                </button>
            </form>

            <div class="sep" style="margin-top:2rem;padding-top:1.25rem">
                <p class="foot-text" style="text-align:center;font-size:11px;display:flex;align-items:center;justify-content:center;gap:6px">
                    <i class="fas fa-shield-halved" style="opacity:.6"></i> Kedana Kedini v1.0 &copy; {{ date('Y') }}
                </p>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
