@extends('layouts.dashboard')
@section('title', 'Persembahan Online')
@section('page-title', 'Manajemen Persembahan Online')

@section('content')

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    @foreach([
        ['Total Terkumpul',  'Rp '.number_format($stats['total_lunas'],0,',','.'), 'fas fa-donate',      '#d4af37', '#fdf8e7'],
        ['Transaksi Lunas',  $stats['total_transaksi'].' transaksi',               'fas fa-check-circle', '#059669', '#d1fae5'],
        ['Bulan Ini',        'Rp '.number_format($stats['bulan_ini'],0,',','.'),   'fas fa-calendar',     '#2563eb', '#dbeafe'],
        ['Menunggu',         $stats['pending'].' transaksi',                        'fas fa-clock',        '#d97706', '#fef3c7'],
    ] as [$label, $val, $icon, $color, $bg])
    <div style="background:#fff;border-radius:12px;padding:18px;box-shadow:var(--shadow);border-left:4px solid {{ $color }};display:flex;align-items:center;gap:14px;">
        <div style="width:42px;height:42px;border-radius:10px;background:{{ $bg }};color:{{ $color }};display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0;">
            <i class="{{ $icon }}"></i>
        </div>
        <div>
            <div style="font-size:11px;font-weight:600;color:var(--text-mid);text-transform:uppercase;letter-spacing:.4px;">{{ $label }}</div>
            <div style="font-size:16px;font-weight:700;color:var(--text-dark);margin-top:2px;">{{ $val }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filter + Table --}}
<div style="background:#fff;border-radius:12px;box-shadow:var(--shadow);overflow:hidden;">
    <div style="padding:16px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
        <div style="font-family:'Playfair Display',serif;font-size:15px;font-weight:700;">Daftar Transaksi</div>
        <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;">
            <select name="status" class="psb-filter" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="settlement" {{ request('status')==='settlement'?'selected':'' }}>Lunas</option>
                <option value="pending"    {{ request('status')==='pending'?'selected':'' }}>Pending</option>
                <option value="expire"     {{ request('status')==='expire'?'selected':'' }}>Kedaluwarsa</option>
            </select>
            <select name="jenis" class="psb-filter" onchange="this.form.submit()">
                <option value="">Semua Jenis</option>
                @foreach(['persembahan'=>'Persembahan','perpuluhan'=>'Perpuluhan','diakonia'=>'Diakonia','misi'=>'Dana Misi','lainnya'=>'Lainnya'] as $k=>$v)
                <option value="{{ $k }}" {{ request('jenis')===$k?'selected':'' }}>{{ $v }}</option>
                @endforeach
            </select>
            <input type="date" name="tanggal" class="psb-filter" value="{{ request('tanggal') }}" onchange="this.form.submit()">
        </form>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#faf8f5;">
                    @foreach(['Waktu','Nama','Jenis','Jumlah','Metode','Status'] as $h)
                    <th style="text-align:left;padding:11px 16px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-mid);border-bottom:1px solid var(--border);">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($persembahans as $p)
                <tr style="border-bottom:1px solid #f3f1ec;">
                    <td style="padding:13px 16px;font-size:12px;color:var(--text-mid);">{{ $p->created_at->format('d M Y H:i') }}</td>
                    <td style="padding:13px 16px;">
                        <div style="font-size:13px;font-weight:600;color:var(--text-dark);">{{ $p->nama_pemberi }}</div>
                        <div style="font-size:11px;color:var(--text-light);">{{ $p->email }}</div>
                    </td>
                    <td style="padding:13px 16px;font-size:13px;">{{ $p->jenis_label }}</td>
                    <td style="padding:13px 16px;font-size:14px;font-weight:700;color:var(--text-dark);">Rp {{ number_format($p->jumlah,0,',','.') }}</td>
                    <td style="padding:13px 16px;font-size:12px;color:var(--text-mid);">{{ $p->payment_type ? strtoupper($p->payment_type) : '—' }}</td>
                    <td style="padding:13px 16px;">
                        @php $colors = ['settlement'=>['#d1fae5','#065f46'],'pending'=>['#fef3c7','#92400e'],'expire'=>['#fee2e2','#991b1b'],'cancel'=>['#fee2e2','#991b1b'],'deny'=>['#fee2e2','#991b1b']]; $c = $colors[$p->status] ?? ['#f3f4f6','#666']; @endphp
                        <span style="background:{{ $c[0] }};color:{{ $c[1] }};padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">{{ $p->status_label }}</span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="padding:40px;text-align:center;color:var(--text-light);">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding:16px 22px;">{{ $persembahans->links() }}</div>
</div>

<style>
.psb-filter { border:1.5px solid #e5e0d5;border-radius:8px;padding:7px 12px;font-size:12.5px;outline:none;background:#fff;cursor:pointer; }
.psb-filter:focus { border-color:var(--gold); }
</style>
@endsection