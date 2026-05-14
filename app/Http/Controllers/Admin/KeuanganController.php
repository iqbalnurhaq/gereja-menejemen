<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use App\Models\Jemaat;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    /**
     * Menampilkan list keuangan
     */
    public function index()
    {
        $keuangan = Keuangan::with('jemaat')
            ->orderBy('tanggal_transaksi', 'desc')
            ->paginate(15);

        return view('admin.keuangan.index', compact('keuangan'));
    }

    /**
     * Menampilkan form tambah keuangan
     */
    public function create()
    {
        $jemaat = Jemaat::orderBy('nama_lengkap')->get();
        return view('admin.keuangan.create', compact('jemaat'));
    }

    /**
     * Menyimpan keuangan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jemaat_id' => 'nullable|exists:jemaats,id',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'kategori' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        Keuangan::create($validated);

        return redirect()->route('admin.keuangan.index')
            ->with('success', 'Data keuangan berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit keuangan
     */
    public function edit(Keuangan $keuangan)
    {
        $jemaat = Jemaat::orderBy('nama_lengkap')->get();
        return view('admin.keuangan.edit', compact('keuangan', 'jemaat'));
    }

    /**
     * Update keuangan
     */
    public function update(Request $request, Keuangan $keuangan)
    {
        $validated = $request->validate([
            'jemaat_id' => 'nullable|exists:jemaats,id',
            'tipe' => 'required|in:pemasukan,pengeluaran',
            'kategori' => 'required|string',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_transaksi' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $keuangan->update($validated);

        return redirect()->route('admin.keuangan.index')
            ->with('success', 'Data keuangan berhasil diperbarui');
    }

    /**
     * Menghapus keuangan
     */
    public function destroy(Keuangan $keuangan)
    {
        $keuangan->delete();

        return redirect()->route('admin.keuangan.index')
            ->with('success', 'Data keuangan berhasil dihapus');
    }

    /**
     * Export ke laporan (opsional)
     */
    public function report()
    {
        $tahun = request('tahun', now()->year);
        $bulan = request('bulan', null);

        $query = Keuangan::whereYear('tanggal_transaksi', $tahun);

        if ($bulan) {
            $query->whereMonth('tanggal_transaksi', $bulan);
        }

        $keuangan = $query->orderBy('tanggal_transaksi', 'desc')->get();

        $pemasukan = $keuangan->where('tipe', 'pemasukan')->sum('jumlah');
        $pengeluaran = $keuangan->where('tipe', 'pengeluaran')->sum('jumlah');
        $saldo = $pemasukan - $pengeluaran;

        return view('admin.keuangan.report', [
            'keuangan' => $keuangan,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'saldo' => $saldo,
            'tahun' => $tahun,
            'bulan' => $bulan,
        ]);
    }
}
