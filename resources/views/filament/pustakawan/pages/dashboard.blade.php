<x-filament-panels::page>
    {{-- Statistik Bar Horizontal --}}
    <div class="flex flex-wrap gap-4 mb-6">
        <!-- Total Buku -->
        <x-filament::card class="flex-1 min-w-[200px] flex items-center gap-4">
            <x-heroicon-o-book-open class="w-10 h-10 text-primary-600" />
            <div>
                <div class="text-sm text-gray-500">Total Buku</div>
                <div class="text-2xl font-bold">{{ \App\Models\Buku::count() }}</div>
            </div>
        </x-filament::card>

        <!-- Total Anggota -->
        <x-filament::card class="flex-1 min-w-[200px] flex items-center gap-4">
            <x-heroicon-o-user-group class="w-10 h-10 text-primary-600" />
            <div>
                <div class="text-sm text-gray-500">Total Anggota</div>
                <div class="text-2xl font-bold">{{ \App\Models\Anggota::count() }}</div>
            </div>
        </x-filament::card>

        <!-- Transaksi Peminjaman -->
        <x-filament::card class="flex-1 min-w-[200px] flex items-center gap-4">
            <x-heroicon-o-arrow-down-tray class="w-10 h-10 text-primary-600" />
            <div>
                <div class="text-sm text-gray-500">Transaksi Peminjaman</div>
                <div class="text-2xl font-bold">{{ \App\Models\Peminjaman::where('status', 'dipinjam')->count() }}</div>
            </div>
        </x-filament::card>

        <!-- Transaksi Pengembalian -->
        <x-filament::card class="flex-1 min-w-[200px] flex items-center gap-4">
            <x-heroicon-o-arrow-up-tray class="w-10 h-10 text-primary-600" />
            <div>
                <div class="text-sm text-gray-500">Transaksi Pengembalian</div>
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

    {{-- Top Buku dan Top Anggota --}}
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
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\Buku::withCount('peminjaman')->orderByDesc('peminjaman_count')->limit(5)->get() as $buku)
                        <tr class="border-t">
                            <td>
                                <img src="{{ asset('storage/' . $buku->gambar) }}" class="h-12 w-8 object-cover" />
                            </td>
                            <td class="py-2">{{ $buku->judul }}</td>
                            <td>{{ $buku->peminjaman_count }}</td>
                            <td>{{ $buku->stok + $buku->peminjaman_count }}</td>
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
                                <img src="{{ asset('storage/' . $anggota->gambar) }}" class="h-10 w-10 rounded-full object-cover" />
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
</x-filament-panels::page>
