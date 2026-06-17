@extends('layouts.app')
@section('title', 'Tentang Kami - Tim Pengembang')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="text-center mt-6 mb-10">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-4" style="color:var(--text-primary)">Tim Pengembang</h1>
        <p class="text-lg max-w-2xl mx-auto" style="color:var(--text-secondary)">
            Aplikasi <strong>SISmart (Smart Inventory System)</strong> ini disusun dan dikembangkan oleh <strong>Kelompok 15</strong> sebagai bagian dari dedikasi kami dalam menghadirkan solusi teknologi yang bermanfaat.
        </p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $team = [
                ['name' => 'Vita Fauztina Zabrina', 'nim' => '2402030041', 'role' => 'Anggota Kelompok', 'color' => 'from-pink-500 to-rose-500', 'icon' => 'fa-laptop-code', 'image' => 'vita.webp'],
                ['name' => 'Zahva Bunga Khoirunnisa', 'nim' => '2402030044', 'role' => 'Anggota Kelompok', 'color' => 'from-purple-500 to-indigo-500', 'icon' => 'fa-palette', 'image' => 'zahva.webp'],
                ['name' => 'Lutfia Nur Solihah', 'nim' => '2402030047', 'role' => 'Anggota Kelompok', 'color' => 'from-emerald-500 to-teal-500', 'icon' => 'fa-database', 'image' => 'lutfia.webp'],
                ['name' => 'Widya Kumalasari', 'nim' => '2402030115', 'role' => 'Anggota Kelompok', 'color' => 'from-amber-500 to-orange-500', 'icon' => 'fa-bug', 'image' => 'widya.webp'],
            ];
        @endphp

        @foreach($team as $member)
        <div class="glass relative overflow-hidden rounded-3xl p-6 group transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br {{ $member['color'] }} rounded-bl-full opacity-10 transition-opacity group-hover:opacity-20 -z-10"></div>
            
            <div class="flex flex-col items-center text-center">
                <div class="w-24 h-24 rounded-full mb-4 flex items-center justify-center bg-gradient-to-br {{ $member['color'] }} text-white shadow-lg relative z-10 overflow-hidden">
                    @if(isset($member['image']))
                        <img src="{{ asset('images/' . $member['image']) }}" alt="{{ $member['name'] }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-3xl font-bold">{{ substr($member['name'], 0, 1) }}</span>
                    @endif
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full bg-white dark:bg-slate-800 text-sm flex items-center justify-center shadow" style="color:var(--text-primary)">
                        <i class="fas {{ $member['icon'] }} opacity-80"></i>
                    </div>
                </div>
                
                <h3 class="text-lg font-bold mb-1" style="color:var(--text-primary)">{{ $member['name'] }}</h3>
                <div class="px-3 py-1 mt-2 mb-3 rounded-full text-xs font-semibold font-mono shadow-sm bg-gradient-to-r {{ $member['color'] }} text-white">
                    NPM: {{ $member['nim'] }}
                </div>
                <p class="text-xs font-medium uppercase tracking-widest mt-2" style="color:var(--text-muted)">{{ $member['role'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="glass rounded-3xl p-8 mt-12 text-center max-w-3xl mx-auto relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-fuchsia-500/10 rounded-full blur-3xl"></div>
        
        <i class="fas fa-heart text-rose-500 text-3xl mb-4 animate-pulse"></i>
        <h3 class="text-xl font-bold mb-3" style="color:var(--text-primary)">Terima Kasih Telah Menggunakan SISmart</h3>
        <p class="text-sm leading-relaxed" style="color:var(--text-secondary)">
            Sistem Inventaris Cerdas ini dirancang untuk mempermudah pengelolaan barang, pemantauan stok secara *real-time*, 
            serta memberikan kemudahan dalam pencatatan laporan keuangan dan perpindahan aset. Semoga sistem ini memberikan banyak manfaat!
        </p>
    </div>
</div>
@endsection
