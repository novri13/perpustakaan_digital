<x-filament::page>
    <div class="space-y-6">
        {{-- Pencarian Buku --}}
        <div>
            <x-filament::section>
                <x-slot name="header">
                    <h2 class="text-lg font-bold">Cari Buku</h2>
                </x-slot>

                <div class="mt-2">
                    <input type="text" placeholder="Masukkan Kata Kunci Untuk Mencari Buku"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-primary-500" />
                </div>
            </x-filament::section>
        </div>

        {{-- Sambutan dan Catatan --}}
        <x-filament::section>
            <x-slot name="header">
                <h2 class="text-xl font-bold">Selamat Datang, {{ auth()->user()->nama }}</h2>
            </x-slot>

            <ul class="list-disc ml-5 mt-4 text-sm leading-relaxed">
                <li>Anda dapat melakukan peminjaman buku dengan tempo yang sudah ditentukan.</li>
                <li>Anda dapat melakukan perpanjangan tempo peminjaman buku sebanyak 1 (satu) kali, dengan tempo yang sudah ditentukan.</li>
                <li>Saat melakukan pengembalian buku, harap mengembalikan buku sesuai waktu peminjaman lamanya 7 hari dan dapat diperpanjang 7 hari lagi (harus konfirmasi dengan petugas perpustakaan).</li>
                <li>Harap jaga buku dengan baik saat dibawa pulang ke rumah.</li>
            </ul>
        </x-filament::section>
    </div>
</x-filament::page>
