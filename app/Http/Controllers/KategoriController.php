<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('barangs')->latest()->paginate(15);
        return view('kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama',
            'deskripsi' => 'nullable|string|max:500',
        ]);

        Kategori::create($validated);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama,' . $kategori->id,
            'deskripsi' => 'nullable|string|max:500',
        ]);

        $kategori->update($validated);

        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->barangs()->count() > 0) {
            return back()->with('error', 'Kategori masih memiliki barang!');
        }

        $kategori->delete();
        return redirect()->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
