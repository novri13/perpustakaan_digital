<div class="bg-white shadow-lg rounded-lg p-6 max-w-4xl mx-auto mt-16 text-gray-800 border border-black">
    <h2 class="text-xl font-bold mb-4">📘 Detail Buku</h2>

    <!-- Informasi Buku -->
    <h3 class="font-semibold text-md mt-4 border-b pb-1">Informasi Buku</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mt-2">
        <div>
            <p><strong>ID Buku:</strong> {{ $record->id }}</p>
            <p><strong>Judul Buku:</strong> {{ $record->judul }}</p>
            <p><strong>Pengarang:</strong> {{ $record->pengarang }}</p>
            <p><strong>Jumlah Buku</strong> : {{ $buku->stok }} / {{ $buku->stok + $sedangDipinjam }}</p>
            <p><strong>Stok:</strong> 
                <span class="inline-block px-2 py-1 text-xs rounded 
                    {{ $record->stok > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $record->stok > 0 ? $record->stok . ' tersedia' : 'Stok habis' }}
                </span>
            </p>
            <p><strong>Edisi:</strong> {{ $record->edisi }}</p>
            <p><strong>Bahasa:</strong> {{ $record->bahasa }}</p>
        </div>

        <div>
           <p><strong>Tahun Terbit:</strong> 
                {{ $record->tahun_terbit ? \Carbon\Carbon::parse($record->tahun_terbit)->format('Y') : '-' }}
            </p>
            <p><strong>Tahun Masuk:</strong> 
                {{ $record->tahun_masuk ? \Carbon\Carbon::parse($record->tahun_masuk)->format('d-m-Y') : '-' }}
            </p>
            <p><strong>Tahun Berubah:</strong> 
                {{ $record->tahun_berubah ? \Carbon\Carbon::parse($record->tahun_berubah)->format('d-m-Y') : '-' }}
            </p>
            <p><strong>Kategori:</strong> {{ $record->kategori?->name ?? '-' }}</p>
            <p><strong>Rak:</strong> {{ $record->rak?->name ?? '-' }}</p>
            <p><strong>Penerbit:</strong> {{ $record->penerbit?->name ?? '-' }}</p>
        </div>
    </div>

    <!-- Deskripsi -->
    <div class="mt-6">
        <h3 class="font-semibold text-md border-b pb-1">Deskripsi</h3>
        <p class="text-sm text-gray-700 mt-2">{{ $record->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
    </div>

    <!-- Gambar dan QR Code -->
    @if ($record->gambar || $record->qr_code)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        @if ($record->gambar)
            <div>
                <p class="font-semibold mb-2">Gambar Buku:</p>
                <img src="{{ asset('storage/'.$record->gambar) }}" alt="{{ $record->judul }}" class="w-40 h-56 object-cover rounded border">
            </div>
        @endif

        @if ($record->qr_code)
            <div>
                <p class="font-semibold mb-2">QR Code Buku:</p>
                <img src="{{ asset('storage/'.$record->qr_code) }}" alt="QR {{ $record->judul }}" class="w-32 h-32 border rounded">
            </div>
        @endif
    </div>
    @endif

    <!-- Tombol Kembali -->
    <div class="mt-8 text-right">
        <button 
            onclick="history.back()" 
            class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-black border border-black rounded shadow">
            ⬅ Kembali
        </button>
    </div>

</div>
