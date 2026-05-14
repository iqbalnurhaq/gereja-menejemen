@extends('admin.layouts.app')

@section('page-title', 'Manajemen Notifikasi')

@section('content')

<div class="content-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0; color: #2d3748;">List Notifikasi</h2>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.notifikasi.create') }}" class="btn btn-primary">+ Buat Notifikasi</a>
            <button type="button" class="btn btn-primary" onclick="showSendAllModal();">📢 Kirim ke Semua</button>
        </div>
    </div>

    @if($notifikasi->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Tipe</th>
                    <th>Tanggal Kirim</th>
                    <th>Untuk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notifikasi as $notif)
                    <tr>
                        <td><strong>{{ $notif->judul }}</strong></td>
                        <td>
                            <span class="badge @if($notif->tipe == 'penting') badge-warning @elseif($notif->tipe == 'event') badge-info @else badge-success @endif">
                                {{ ucfirst($notif->tipe) }}
                            </span>
                        </td>
                        <td>{{ $notif->tanggal_kirim->format('d M Y H:i') }}</td>
                        <td>
                            @if($notif->jemaat_id)
                                {{ $notif->jemaat->nama_lengkap ?? 'Dihapus' }}
                            @else
                                <em>Semua Jemaat</em>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.notifikasi.edit', $notif) }}" class="btn btn-secondary">Edit</a>
                            <form action="{{ route('admin.notifikasi.destroy', $notif) }}" method="POST" style="display: inline;" onsubmit="return confirm('Hapus notifikasi ini?');">
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
            {{ $notifikasi->links() }}
        </div>
    @else
        <p style="color: #718096; text-align: center;">Belum ada notifikasi. <a href="{{ route('admin.notifikasi.create') }}">Buat sekarang</a></p>
    @endif
</div>

<!-- Modal Kirim ke Semua -->
<div id="sendAllModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div class="content-card" style="max-width: 500px;">
        <h3 style="margin-top: 0;">Kirim Notifikasi ke Semua Jemaat</h3>
        <form action="{{ route('admin.notifikasi.sendToAll') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="modal_judul">Judul *</label>
                <input type="text" name="judul" id="modal_judul" required placeholder="Judul notifikasi">
            </div>

            <div class="form-group">
                <label for="modal_isi">Isi Notifikasi *</label>
                <textarea name="isi" id="modal_isi" required placeholder="Isi pesan notifikasi..."></textarea>
            </div>

            <div class="form-group">
                <label for="modal_tipe">Tipe *</label>
                <select name="tipe" id="modal_tipe" required>
                    <option value="pengumuman">Pengumuman</option>
                    <option value="penting">Penting</option>
                    <option value="event">Event</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">📤 Kirim ke Semua</button>
                <button type="button" class="btn btn-secondary" onclick="hideSendAllModal();">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function showSendAllModal() {
    document.getElementById('sendAllModal').style.display = 'flex';
}

function hideSendAllModal() {
    document.getElementById('sendAllModal').style.display = 'none';
}

document.getElementById('sendAllModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideSendAllModal();
    }
});
</script>

@endsection
