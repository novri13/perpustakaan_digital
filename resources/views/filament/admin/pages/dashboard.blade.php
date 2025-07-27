<x-filament-panels::page>

{{-- Selamat Datang + Jam & Tanggal --}}
<div class="mb-5">
    <div class="p-4 rounded-lg bg-gradient-to-r from-blue-200 to-blue-400 text-blue-900 shadow flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">
                👋 Selamat Datang, {{ auth()->user()->name }}
            </h1>
            <p class="text-base opacity-90">
                Semoga harimu menyenangkan! Berikut statistik perpustakaan hari ini.
            </p>
        </div>
        <div class="text-right">
            <div id="tanggal-sekarang" class="text-lg font-semibold"></div>
            <div id="jam-sekarang" class="digital-clock"></div>
        </div>
    </div>
</div>

{{-- Tulisan Berjalan --}}
<div class="mt-3 overflow-hidden relative h-8 bg-blue-50 rounded shadow-inner">
    <div class="absolute whitespace-nowrap text-blue-900 font-semibold text-lg animate-marquee hover:pause-marquee">
        🎉 Selamat datang di <span class="font-bold">SIPERTAL</span> (Sistem Informasi Perpustakaan Digital) SMA Negeri 1 Bengkulu Selatan 🎉
    </div>
</div>

{{-- Statistik --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 my-6">
    <!-- Total Buku -->
    <x-filament::card class="flex items-center gap-4">
        <x-heroicon-o-book-open class="w-10 h-10 text-primary-600" />
        <div>
            <div class="text-sm text-gray-500">Total Buku</div>
            <div class="text-2xl font-bold">{{ \App\Models\Buku::count() }}</div>
        </div>
    </x-filament::card>

    <!-- Total Anggota -->
    <x-filament::card class="flex items-center gap-4">
        <x-heroicon-o-user-group class="w-10 h-10 text-primary-600" />
        <div>
            <div class="text-sm text-gray-500">Total Anggota</div>
            <div class="text-2xl font-bold">{{ \App\Models\Anggota::count() }}</div>
        </div>
    </x-filament::card>

    <!-- Transaksi Peminjaman -->
    <x-filament::card class="flex items-center gap-4">
        <x-heroicon-o-arrow-down-tray class="w-10 h-10 text-primary-600" />
        <div>
            <div class="text-sm text-gray-500">Transaksi Peminjaman</div>
            <div class="text-2xl font-bold">{{ \App\Models\Peminjaman::where('status', 'dipinjam')->count() }}</div>
        </div>
    </x-filament::card>

    <!-- Booking Buku -->
    <x-filament::card class="flex items-center gap-4">
        <x-heroicon-o-calendar-days class="w-10 h-10 text-primary-600" />
        <div>
            <div class="text-sm text-gray-500">Booking Buku</div>
            <div class="text-2xl font-bold">{{ \App\Models\Booking::count() }}</div>
        </div>
    </x-filament::card>

    <!-- Pengembalian -->
    <x-filament::card class="flex items-center gap-4">
        <x-heroicon-o-arrow-path class="w-10 h-10 text-primary-600" />
        <div>
            <div class="text-sm text-gray-500">Pengembalian</div>
            <div class="text-2xl font-bold">{{ \App\Models\Peminjaman::where('status', 'kembali')->count() }}</div>
        </div>
    </x-filament::card>
</div>

{{-- Diagram Peminjaman Bulanan --}}
<div class="mb-6">
    <x-filament::card>
        <livewire:chart.peminjaman-bulanan />
    </x-filament::card>
</div>

{{-- Top Buku & Top Anggota --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Top Buku -->
    <x-filament::card>
        <div class="text-lg font-bold mb-4">Top Buku</div>
        <table class="w-full text-sm">
            <thead class="text-left text-gray-500">
                <tr>
                    <th>Sampul</th>
                    <th>Judul</th>
                    <th>Dipinjam</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach(
                    \App\Models\Buku::withCount('peminjaman')
                        ->having('peminjaman_count', '>', 0)
                        ->orderByDesc('peminjaman_count')
                        ->limit(5)
                        ->get() as $buku
                )
                    <tr class="border-t">
                        <td>
                            <img src="{{ $buku->gambar ? asset('storage/'.$buku->gambar) : asset('storage/no-cover.png') }}" 
                                class="h-12 w-8 object-cover rounded" />
                        </td>
                        <td class="py-2">{{ $buku->judul }}</td>
                        <td>{{ $buku->peminjaman_count }}</td>
                        <td>{{ $buku->stok }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-filament::card>

    <!-- Top Anggota -->
    <x-filament::card>
        <div class="text-lg font-bold mb-4">Top Anggota</div>
        <table class="w-full text-sm">
            <thead class="text-left text-gray-500">
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Jurusan</th>
                    <th>Jumlah Pinjam</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Anggota::withCount('peminjaman')->orderByDesc('peminjaman_count')->limit(5)->get() as $anggota)
                    <tr class="border-t">
                        <td>
                            <img src="{{ $anggota->gambar ? asset('storage/' . $anggota->gambar) : asset('storage/no-cover.png') }}"
                            class="h-10 w-10 rounded-full object-cover" />
                        </td>
                        <td class="py-2">{{ $anggota->nama }}</td>
                        <td>{{ $anggota->jurusan->name ?? '-' }}</td>
                        <td>{{ $anggota->peminjaman_count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-filament::card>
</div>

{{-- Script Jam Digital --}}
<script>
    function updateWaktu() {
        const sekarang = new Date();
        const hariNama = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const bulanNama = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

        let hari = hariNama[sekarang.getDay()];
        let tanggal = sekarang.getDate();
        let bulan = bulanNama[sekarang.getMonth()];
        let tahun = sekarang.getFullYear();

        let jam = sekarang.getHours().toString().padStart(2, '0');
        let menit = sekarang.getMinutes().toString().padStart(2, '0');
        let detik = sekarang.getSeconds().toString().padStart(2, '0');

        document.getElementById('tanggal-sekarang').textContent = `${hari}, ${tanggal} ${bulan} ${tahun}`;
        document.getElementById('jam-sekarang').textContent = `${jam}:${menit}:${detik}`;
    }
    setInterval(updateWaktu, 1000);
    updateWaktu();
</script>

{{-- CSS Animasi & Digital Clock --}}
<style>
    /* Tulisan berjalan */
    @keyframes marquee {
        0%   { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
    .animate-marquee {
        display: inline-block;
        padding-left: 100%;
        animation: marquee 20s linear infinite;
    }
    .hover\:pause-marquee:hover {
        animation-play-state: paused;
    }

    /* Digital Clock Style */
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap');
    .digital-clock {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        font-weight: bold;
        color: #1e3a8a; /* biru tua */
        background: #f8fafc;
        padding: 6px 12px;
        border-radius: 8px;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.1), 0 2px 6px rgba(0,0,0,0.2);
        letter-spacing: 2px;
        display: inline-block;
    }
</style>

</x-filament-panels::page>
