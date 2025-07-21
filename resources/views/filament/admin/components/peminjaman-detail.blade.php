<div>
    <h3 class="text-lg font-bold mb-2">Informasi Peminjaman</h3>
    <p><strong>Kode Pinjam:</strong> {{ $record->kode_peminjaman }}</p>
    <p><strong>Nama Anggota:</strong> {{ $record->anggota->nama }}</p>
    <p><strong>NIP/NISN:</strong> {{ $record->anggota->id }}</p>
    <p><strong>Judul Buku:</strong> {{ $record->buku->judul }}</p>
    <p><strong>Jumlah Buku:</strong> {{ $record->jumlah_buku }}</p>
    <p><strong>Tanggal Pinjam:</strong> {{ \Carbon\Carbon::parse($record->tanggal_pinjam)->format('d-m-Y') }}</p>
    <p><strong>Jatuh Tempo:</strong> {{ \Carbon\Carbon::parse($record->tanggal_kembali)->format('d-m-Y') }}</p>
</div>

<div class="mt-4">
    <h3 class="text-lg font-bold mb-2">Informasi Pengembalian</h3>
    <p><strong>Status:</strong> {{ ucfirst($record->status) }}</p>
    <p><strong>Denda:</strong>
        @if ($record->denda)
            Rp. {{ number_format($record->denda->harga, 0, ',', '.') }}
            ({{ $record->denda->lama_waktu }} hari keterlambatan)
        @else
            Tidak ada denda
        @endif
    </p>
</div>
