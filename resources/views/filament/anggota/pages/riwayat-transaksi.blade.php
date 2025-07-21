<x-filament::page>
    <div class="space-y-6">
        {{-- Pencarian Buku --}}
        <div>
            <input
                type="text"
                placeholder="Masukkan Kata Kunci Untuk Mencari Buku"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-primary-500"
            />
        </div>

        {{-- Tab Navigasi --}}
        <div class="flex border-b border-gray-300">
            <button wire:click="$set('activeTab', 'peminjaman')"
                @class([
                    'px-4 py-2 text-sm font-medium border-b-2',
                    'border-blue-500 text-blue-600' => $this->activeTab === 'peminjaman',
                    'text-gray-500 border-transparent hover:text-blue-500' => $this->activeTab !== 'peminjaman',
                ])
            >
                Riwayat Peminjaman Buku
            </button>
            <button wire:click="$set('activeTab', 'pengembalian')"
                @class([
                    'px-4 py-2 text-sm font-medium border-b-2',
                    'border-blue-500 text-blue-600' => $this->activeTab === 'pengembalian',
                    'text-gray-500 border-transparent hover:text-blue-500' => $this->activeTab !== 'pengembalian',
                ])
            >
                Riwayat Pengembalian Buku
            </button>
        </div>

        {{-- Tabel Riwayat --}}
        <div class="mt-4">
            @if ($this->activeTab === 'peminjaman')
                {{ $this->table }}
            @elseif ($this->activeTab === 'pengembalian')
                {{ $this->tablePengembalian }}
            @endif
        </div>
    </div>
</x-filament::page>
