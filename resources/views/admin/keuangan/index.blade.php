{{-- @extends('admin.layouts.app')

@section('page-title', 'Manajemen Keuangan')

@section('content')

<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; color: #2d3748;">List Keuangan</h2>
        <a href="{{ route('admin.keuangan.create') }}" class="btn btn-primary">+ Tambah Transaksi</a>
    </div>

    <!-- Filter -->
    <form method="GET" style="margin-bottom: 20px; display: flex; gap: 10px;">
        <input type="text" name="search" placeholder="Cari kategori..." value="{{ request('search') }}" style="flex: 1; padding: 8px 12px; border: 1px solid #cbd5e0; border-radius: 4px;">
        <select name="tipe" style="padding: 8px 12px; border: 1px solid #cbd5e0; border-radius: 4px;">
            <option value="">Semua Tipe</option>
            <option value="pemasukan" @selected(request('tipe') == 'pemasukan')>Pemasukan</option>
            <option value="pengeluaran" @selected(request('tipe') == 'pengeluaran')>Pengeluaran</option>
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
    </form>

    @if($keuangan->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Tipe</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($keuangan as $item)
                    <tr>
                        <td>{{ $item->tanggal_transaksi->format('d M Y') }}</td>
                        <td>
                            <span class="badge @if($item->tipe == 'pemasukan') badge-success @else badge-warning @endif">
                                {{ ucfirst($item->tipe) }}
                            </span>
                        </td>
                        <td>{{ $item->kategori }}</td>
                        <td>
                            <strong style="@if($item->tipe == 'pengeluaran') color: #f56565; @else color: #48bb78; @endif">
                                @if($item->tipe == 'pengeluaran') - @endif Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </strong>
                        </td>
                        <td>{{ Str::limit($item->keterangan, 40) }}</td>
                        <td>
                            <a href="{{ route('admin.keuangan.edit', $item) }}" class="btn btn-secondary">Edit</a>
                            <form action="{{ route('admin.keuangan.destroy', $item) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus transaksi ini? Aksi ini tidak bisa dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div style="margin-top: 20px; text-align: center;">
            {{ $keuangan->links() }}
        </div>
    @else
        <p style="color: #718096; text-align: center;">Belum ada data keuangan. <a href="{{ route('admin.keuangan.create') }}">Tambah sekarang</a></p>
    @endif
</div>

@endsection --}}
