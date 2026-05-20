@extends('layouts.app')
@section('title', 'Data Kategori')
@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <h2 class="text-lg sm:text-xl font-bold">Kategori Barang</h2>
        <button x-data @click="$dispatch('open-modal', 'modal-create')" class="btn-primary text-xs sm:text-sm w-full sm:w-auto justify-center"><i class="fas fa-plus"></i> Tambah Kategori</button>
    </div>

    {{-- Mobile Cards --}}
    <div class="sm:hidden space-y-3">
        @forelse($kategoris as $k)
        <div class="glass rounded-xl p-4">
            <div class="flex items-start justify-between mb-2">
                <p class="font-medium text-sm">{{ $k->nama }}</p>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium text-indigo-700 dark:text-indigo-400 bg-indigo-500/10">{{ $k->barangs_count }} barang</span>
            </div>
            <p class="text-xs mb-3" style="color:var(--text-muted)">{{ $k->deskripsi ?? 'Tidak ada deskripsi' }}</p>
            <div class="flex gap-2 justify-end">
                <button x-data @click="$dispatch('edit-kategori', { id: {{ $k->id }}, nama: '{{ $k->nama }}', deskripsi: '{{ $k->deskripsi }}' })" class="p-2 rounded-lg text-cyan-600 dark:text-cyan-400 hover:bg-cyan-500/10 text-xs"><i class="fas fa-edit"></i></button>
                <form action="{{ route('kategori.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE')
                    <button type="submit" class="p-2 rounded-lg text-rose-600 dark:text-rose-400 hover:bg-rose-500/10 text-xs"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </div>
        @empty
        <div class="glass rounded-xl p-8 text-center" style="color:var(--text-muted)"><i class="fas fa-tags text-3xl mb-2 opacity-30"></i><p class="text-sm">Belum ada kategori</p></div>
        @endforelse
    </div>

    {{-- Desktop Table --}}
    <div class="glass rounded-2xl overflow-hidden hidden sm:block">
        <div class="table-responsive">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase border-b" style="color:var(--text-muted); background:var(--bg-input); border-color:var(--border-color)">
                    <tr><th class="px-6 py-4">Nama Kategori</th><th class="px-6 py-4">Deskripsi</th><th class="px-6 py-4 text-center">Jumlah Barang</th><th class="px-6 py-4 text-right">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $k)
                    <tr class="border-b hover:bg-indigo-500/5 dark:hover:bg-white/5 transition-colors" style="border-color:var(--border-color)">
                        <td class="px-6 py-4 font-medium">{{ $k->nama }}</td>
                        <td class="px-6 py-4" style="color:var(--text-secondary)">{{ $k->deskripsi ?? '-' }}</td>
                        <td class="px-6 py-4 text-center"><span class="bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 py-1 px-3 rounded-full text-xs">{{ $k->barangs_count }} barang</span></td>
                        <td class="px-6 py-4 text-right">
                            <button x-data @click="$dispatch('edit-kategori', { id: {{ $k->id }}, nama: '{{ $k->nama }}', deskripsi: '{{ $k->deskripsi }}' })" class="p-2 rounded-lg text-cyan-600 dark:text-cyan-400 hover:bg-cyan-500/10 transition-colors"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('kategori.destroy', $k->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg text-rose-600 dark:text-rose-400 hover:bg-rose-500/10 transition-colors"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center" style="color:var(--text-muted)">Tidak ada data kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t" style="border-color:var(--border-color)">{{ $kategoris->links() }}</div>
    </div>
</div>

{{-- Modal Create --}}
<div x-data="{ show:false }" x-show="show" @open-modal.window="if($event.detail==='modal-create') show=true" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="show=false"></div>
        <div x-show="show" x-transition.scale class="relative w-full max-w-lg glass rounded-2xl shadow-2xl border border-indigo-500/30 overflow-hidden">
            <div class="px-6 pt-6 pb-4">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-semibold">Tambah Kategori</h3>
                    <button @click="show=false" style="color:var(--text-muted)" class="hover:opacity-70"><i class="fas fa-times"></i></button>
                </div>
                <form action="{{ route('kategori.store') }}" method="POST" class="space-y-4">@csrf
                    <div>
                        <label class="form-label"><i class="fas fa-tag text-indigo-500"></i> Nama Kategori <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" required class="form-input" placeholder="Masukkan nama kategori">
                    </div>
                    <div>
                        <label class="form-label"><i class="fas fa-align-left text-indigo-500"></i> Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="form-input" placeholder="Deskripsi opsional"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="show=false" class="btn-outline">Batal</button>
                        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div x-data="{ show:false, id:'', nama:'', deskripsi:'' }" x-show="show" @edit-kategori.window="show=true; id=$event.detail.id; nama=$event.detail.nama; deskripsi=$event.detail.deskripsi" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="show=false"></div>
        <div x-show="show" x-transition.scale class="relative w-full max-w-lg glass rounded-2xl shadow-2xl border border-indigo-500/30 overflow-hidden">
            <div class="px-6 pt-6 pb-4">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg font-semibold">Edit Kategori</h3>
                    <button @click="show=false" style="color:var(--text-muted)" class="hover:opacity-70"><i class="fas fa-times"></i></button>
                </div>
                <form :action="'{{ url('kategori') }}/'+id" method="POST" class="space-y-4">@csrf @method('PUT')
                    <div>
                        <label class="form-label"><i class="fas fa-tag text-indigo-500"></i> Nama Kategori <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" x-model="nama" required class="form-input">
                    </div>
                    <div>
                        <label class="form-label"><i class="fas fa-align-left text-indigo-500"></i> Deskripsi</label>
                        <textarea name="deskripsi" x-model="deskripsi" rows="3" class="form-input"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="show=false" class="btn-outline">Batal</button>
                        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
