@extends('layouts.app')
@section('title', 'Data Barang')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <h2 class="text-lg sm:text-xl font-bold" data-lang="brg.title">Data Barang</h2>
        <a href="{{ route('barang.create') }}" class="btn-primary text-xs sm:text-sm w-full sm:w-auto justify-center"><i class="fas fa-plus"></i> <span data-lang="brg.tambah">Tambah Barang</span></a>
    </div>

    {{-- Mobile Cards --}}
    <div class="sm:hidden space-y-3">
        @forelse($barangs as $b)
            @php $status = $b->status_stok; $color = match($status) { 'Aman'=>'text-emerald-600 dark:text-emerald-400 bg-emerald-500/10','Kritis'=>'text-amber-600 dark:text-amber-400 bg-amber-500/10','Reorder'=>'text-indigo-600 dark:text-indigo-400 bg-indigo-500/10','Habis'=>'text-rose-600 dark:text-rose-400 bg-rose-500/10' }; @endphp
            <a href="{{ route('barang.show', $b->id) }}" class="glass rounded-xl p-4 block hover:shadow-lg transition-all">
                <div class="flex items-start justify-between mb-2">
                    <div><p class="font-medium text-sm">{{ $b->nama }}</p><p class="text-xs font-mono text-indigo-600 dark:text-indigo-400">{{ $b->kode }}</p></div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] {{ $color }}">{{ $status }}</span>
                </div>
                <div class="flex items-center justify-between text-xs" style="color:var(--text-muted)">
                    <span><i class="fas fa-cube mr-1"></i>{{ $b->stok }} {{ $b->satuan }}</span>
                    <span class="text-cyan-600 dark:text-cyan-400 font-medium">Rp {{ number_format($b->total_nilai,0,',','.') }}</span>
                </div>
            </a>
        @empty
            <div class="glass rounded-xl p-8 text-center" style="color:var(--text-muted)"><i class="fas fa-box-open text-3xl mb-2 opacity-30"></i><p class="text-sm">Tidak ada data barang</p></div>
        @endforelse
    </div>

    {{-- Desktop Table --}}
    <div class="glass rounded-2xl overflow-hidden hidden sm:block">
        <div class="table-responsive">
            <table class="w-full text-sm text-left">
                <thead><tr style="background:var(--bg-input)">
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)" data-lang="brg.kode">Kode</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider" style="color:var(--text-muted)" data-lang="brg.nama">Nama Barang</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider hidden lg:table-cell" style="color:var(--text-muted)" data-lang="brg.kategori">Kategori</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-center" style="color:var(--text-muted)" data-lang="brg.metode">Metode</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-right" style="color:var(--text-muted)" data-lang="brg.stok">Stok</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-right hidden sm:table-cell" style="color:var(--text-muted)" data-lang="brg.total_nilai">Total Nilai</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-center" style="color:var(--text-muted)" data-lang="brg.status">Status</th>
                    <th class="px-5 py-3 text-[11px] font-semibold uppercase tracking-wider text-center" style="color:var(--text-muted)" data-lang="common.aksi">Aksi</th>
                </tr></thead>
                <tbody>
                    @forelse($barangs as $b)
                        @php $status = $b->status_stok; $color = match($status) { 'Aman'=>'text-emerald-600 dark:text-emerald-400 bg-emerald-500/10','Kritis'=>'text-amber-600 dark:text-amber-400 bg-amber-500/10','Reorder'=>'text-indigo-600 dark:text-indigo-400 bg-indigo-500/10','Habis'=>'text-rose-600 dark:text-rose-400 bg-rose-500/10' }; @endphp
                        <tr class="border-t hover:bg-indigo-500/[.03] dark:hover:bg-white/[.03] transition-colors" style="border-color:var(--border-color)">
                            <td class="px-5 py-3 font-mono text-xs text-indigo-600 dark:text-indigo-400">{{ $b->kode }}</td>
                            <td class="px-5 py-3"><a href="{{ route('barang.show', $b->id) }}" class="font-medium hover:text-indigo-500 transition-colors">{{ $b->nama }}</a><p class="text-[10px]" style="color:var(--text-muted)">{{ $b->satuan }}</p></td>
                            <td class="px-5 py-3 hidden lg:table-cell" style="color:var(--text-secondary)">{{ $b->kategori->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-center text-[10px] uppercase font-bold tracking-wider {{ $b->metode_stok == 'fifo' ? 'text-cyan-600 dark:text-cyan-400' : 'text-fuchsia-600 dark:text-fuchsia-400' }}">{{ $b->metode_stok }}</td>
                            <td class="px-5 py-3 text-right font-bold">{{ number_format($b->stok) }}</td>
                            <td class="px-5 py-3 text-right text-cyan-600 dark:text-cyan-400 font-medium hidden sm:table-cell">Rp {{ number_format($b->total_nilai,0,',','.') }}</td>
                            <td class="px-5 py-3 text-center"><span class="px-2 py-0.5 rounded-full text-[10px] {{ $color }}">{{ $status }}</span></td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('barang.show', $b->id) }}" class="p-1.5 rounded-lg hover:bg-indigo-500/10 text-indigo-500 transition-colors" title="Detail"><i class="fas fa-eye text-xs"></i></a>
                                    <a href="{{ route('barang.edit', $b->id) }}" class="p-1.5 rounded-lg hover:bg-cyan-500/10 text-cyan-500 transition-colors" title="Edit"><i class="fas fa-edit text-xs"></i></a>
                                    <form action="{{ route('barang.destroy', $b->id) }}" method="POST" class="inline" x-data @submit.prevent="$dispatch('open-delete-modal', { form: $el, message: 'Hapus barang ini secara permanen? Semua riwayat stok & transaksi akan ikut hilang.' })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg hover:bg-rose-500/10 text-rose-500 transition-colors" title="Hapus"><i class="fas fa-trash-alt text-xs"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-5 py-12 text-center" style="color:var(--text-muted)"><i class="fas fa-inbox text-4xl mb-3 opacity-20"></i><p data-lang="brg.tidak_ada">Tidak ada data barang</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($barangs, 'links'))<div class="p-4 border-t" style="border-color:var(--border-color)">{{ $barangs->links() }}</div>@endif
    </div>
</div>
@endsection
