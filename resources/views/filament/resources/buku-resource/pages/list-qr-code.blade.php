<x-filament::page>
    <h1 class="text-xl font-bold mb-6">Daftar QR Code Buku</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($this->bukus as $buku)
            <div class="p-4 border rounded shadow text-center">
                <p class="font-semibold mb-2">{{ $buku->judul }}</p>
                <img src="{{ asset('storage/' . $buku->qr_code) }}" alt="QR {{ $buku->judul }}" class="mx-auto" width="150">
                <p class="text-sm text-gray-600 mt-2">ID: {{ $buku->id }}</p>
            </div>
        @endforeach
    </div>
</x-filament::page>
