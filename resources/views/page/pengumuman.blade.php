@extends('layouts.landing')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Pengumuman</h1>
    <p class="text-gray-700 leading-relaxed mb-8">
        Di YHS, kami menyediakan berbagai layanan untuk mendukung pertumbuhan rohani dan kebutuhan jemaat kami. Layanan kami dirancang untuk membantu Anda dalam perjalanan iman Anda, memberikan dukungan, dan memperkuat komunitas kita bersama.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Layanan 1 -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Pelayanan Ibadah</h3>
            <p class="text-gray-600 text-sm">
                Kami mengadakan ibadah setiap minggu dengan suasana yang hangat dan penuh semangat. Bergabunglah dengan kami untuk merayakan iman bersama-sama.
            </p>
        </div>

        <!-- Layanan 2 -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Pelayanan Konseling</h3>
            <p class="text-gray-600 text-sm">
                Kami menyediakan layanan konseling pastoral untuk mendukung Anda dalam menghadapi tantangan hidup dan memperkuat iman Anda.
            </p>
        </div>

        <!-- Layanan 3 -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Pelayanan Sosial</h3>
            <p class="text-gray-600 text-sm">
                Kami aktif dalam pelayanan sosial, memberikan bantuan kepada mereka yang membutuhkan di komunitas kita dan di luar sana.
            </p>
        </div>

        <!-- Layanan 4 -->
        <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition">
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Pelayanan Pendidikan</h3>
            <p class="text-gray-600 text-sm">
                Kami menawarkan program pendidikan untuk semua usia, termasuk sekolah minggu, kelas Alkitab, dan seminar pengembangan rohani.
            </p>
        </div>
    </div>
</div>
@endsection
