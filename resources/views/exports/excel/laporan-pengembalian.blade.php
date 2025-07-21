<table>
    <thead>
        <tr>
            <th colspan="8" style="text-align: center; font-weight: bold;">Laporan Pengembalian Buku</th>
        </tr>
        <tr>
            <th colspan="8">Tanggal: {{ \Carbon\Carbon::parse($tanggalAwal)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d-m-Y') }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th>No</th>
            <th>Kode Pinjam</th>
            <th>NIP/NISN</th>
            <th>Nama</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Denda</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>P{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $item->anggota->id ?? '-' }}</td>
                <td>{{ $item->anggota->nama ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d-m-Y') }}</td>
                <td>
                    {{ $item->denda?->harga ? 'Rp ' . number_format($item->denda->harga, 0, ',', '.') : 'Rp 0' }}
                </td>
                <td>Selesai</td>
            </tr>
        @endforeach
    </tbody>
</table>
