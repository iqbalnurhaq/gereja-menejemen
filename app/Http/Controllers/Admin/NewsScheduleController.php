<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::withCount('absensis')
            ->orderBy('order')
            ->orderBy('day')
            ->get();

        $stats = [
            'total'  => $schedules->count(),
            'aktif'  => $schedules->where('is_active', true)->count(),
            'rutin'  => $schedules->where('is_recurring', true)->count(),
            'absensi'=> Absensi::count(),
        ];

        return view('admin.schedules.index', compact('schedules', 'stats'));
    }

    public function create()
    {
        return view('admin.schedules.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'emoji'        => 'nullable|string|max:20',
            'description'  => 'required|string',
            'day'          => 'nullable|string|max:50',
            'location'     => 'nullable|string|max:255',
            'tanggal'      => 'nullable|date',
            'is_recurring' => 'boolean',
            'start_time'   => 'nullable|date_format:H:i',
            'end_time'     => 'nullable|date_format:H:i',
            'order'        => 'nullable|integer|min:0',
            'is_active'    => 'boolean',
        ]);

        $data['is_active']    = $request->boolean('is_active', true);
        $data['is_recurring'] = $request->boolean('is_recurring', true);

        Schedule::create($data);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal ibadah berhasil ditambahkan.');
    }

    public function show(Schedule $schedule)
    {
        $absensis = Absensi::where('schedule_id', $schedule->id)
            ->with('jemaat')
            ->latest('tanggal')
            ->paginate(15);

        $absensiStats = [
            'total'   => Absensi::where('schedule_id', $schedule->id)->where('approval_status', 'approved')->count(),
            'hadir'   => Absensi::where('schedule_id', $schedule->id)->where('status', 'hadir')->where('approval_status', 'approved')->count(),
            'izin'    => Absensi::where('schedule_id', $schedule->id)->where('status', 'izin')->where('approval_status', 'approved')->count(),
            'pending' => Absensi::where('schedule_id', $schedule->id)->where('approval_status', 'pending')->count(),
        ];

        return view('admin.schedules.show', compact('schedule', 'absensis', 'absensiStats'));
    }

    public function edit(Schedule $schedule)
    {
        return view('admin.schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'emoji'        => 'nullable|string|max:20',
            'description'  => 'required|string',
            'day'          => 'nullable|string|max:50',
            'location'     => 'nullable|string|max:255',
            'tanggal'      => 'nullable|date',
            'is_recurring' => 'boolean',
            'start_time'   => 'nullable|date_format:H:i',
            'end_time'     => 'nullable|date_format:H:i',
            'order'        => 'nullable|integer|min:0',
            'is_active'    => 'boolean',
        ]);

        $data['is_active']    = $request->boolean('is_active');
        $data['is_recurring'] = $request->boolean('is_recurring');

        $schedule->update($data);

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal ibadah berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal ibadah berhasil dihapus.');
    }

    public function toggleActive(Schedule $schedule)
    {
        $schedule->update(['is_active' => ! $schedule->is_active]);
        return back()->with('success', 'Status jadwal berhasil diubah.');
    }
}