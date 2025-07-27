<div class="space-y-4">

    {{-- Informasi Dasar --}}
    <div>
        <h3 class="text-lg font-bold mb-2">📚 Informasi Peminjaman</h3>
        <p><strong>Kode Pinjam:</strong> {{ 'P' . str_pad($record->id, 6, '0', STR_PAD_LEFT) }}</p>
        <p><strong>Nama Anggota:</strong> {{ $record->anggota->nama }}</p>
        <p><strong>NIP/NISN:</strong> {{ $record->anggota->id }}</p>
        <p><strong>Judul Buku:</strong> {{ $record->buku->judul }}</p>
        <p><strong>Jumlah Buku:</strong> {{ $record->jumlah_buku }}</p>
    </div>

    {{-- Informasi Tanggal --}}
    <div>
        <h3 class="text-lg font-bold mb-2">🗓️ Informasi Waktu</h3>
        <p><strong>Tanggal Pinjam:</strong> {{ \Carbon\Carbon::parse($record->tanggal_pinjam)->format('d-m-Y') }}</p>
        <p><strong>Jatuh Tempo:</strong> {{ \Carbon\Carbon::parse($record->tanggal_kembali)->format('d-m-Y') }}</p>
        <p><strong>Tanggal Dikembalikan:</strong> 
            {{ $record->tanggal_dikembalikan ? \Carbon\Carbon::parse($record->tanggal_dikembalikan)->format('d-m-Y') : '-' }}
        </p>
    </div>

    {{-- Informasi Status --}}
    <div>
        <h3 class="text-lg font-bold mb-2">✅ Status</h3>
        <p><strong>Status Peminjaman:</strong> 
            <span class="px-2 py-1 rounded text-white 
                {{ $record->status === 'dipinjam' ? 'bg-yellow-500' : 
                   ($record->status === 'diperpanjang' ? 'bg-blue-500' :
                   ($record->status === 'pending' ? 'bg-red-500' :
                   ($record->status === 'kembali' ? 'bg-green-500' : 'bg-gray-500'))) }}">
                {{ ucfirst($record->status) }}
            </span>
        </p>
        <p><strong>Status Denda:</strong> 
            <span class="px-2 py-1 rounded text-white {{ $record->status_denda === 'lunas' ? 'bg-green-500' : 'bg-red-500' }}">
                {{ $record->status_denda === 'lunas' ? 'Lunas' : 'Belum Lunas' }}
            </span>
        </p>
    </div>

    {{-- Informasi Denda --}}
    <div>
        <h3 class="text-lg font-bold mb-2">💰 Informasi Denda</h3>
        @if ($record->denda)
            <p><strong>Nominal Denda:</strong> Rp. {{ number_format($record->denda->harga, 0, ',', '.') }}</p>
            <p><strong>Keterlambatan:</strong> {{ $record->denda->lama_waktu }} hari</p>
        @else
            <p>Tidak ada denda.</p>
        @endif
    </div>

</div>
