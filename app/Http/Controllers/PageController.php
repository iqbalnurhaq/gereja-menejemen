<?php

namespace App\Http\Controllers;

use App\Models\Image;

class PageController extends Controller
{
    public function history() {
        $images = Image::all();
        return view('page.history', compact('images'));
    }

    public function vision() {
        return view('page.vision');
    }

    public function struktur() {
        return view('page.struktur');
    }

    public function layanan() {
        return view('page.layanan');
    }

    public function pengumuman() {
        return view('page.pengumuman');
    }

    public function pastors() {
        $pastors = \App\Models\Pastor::all();
        return view('page.pastors', compact('pastors'));
    }
}
