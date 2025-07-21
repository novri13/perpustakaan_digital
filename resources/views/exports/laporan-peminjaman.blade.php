<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
        h2 { text-align: center; margin-top: 0; }
        .info { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Laporan Peminjaman Buku</h2>

    <div class="info">
        <strong>Periode:</strong> {{ \Carbon\Carbon::parse($tanggalAwal)->format('d-m-Y') }}
        s/d {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d-m-Y') }} <br>
        <strong>Dicetak pada:</strong> {{ $tanggalCetak }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Pinjam</th>
                <th>NIP/NISN</th>
                <th>Nama</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Jumlah Buku</th>
                <th>Denda</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>P{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $item->anggota->id ?? '-' }}</td>
                    <td>{{ $item->anggota->nama ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d-m-Y') }}</td>
                    <td>{{ $item->jumlah_buku }}</td>
                    <td>
                        {{ $item->denda?->harga ? 'Rp ' . number_format($item->denda->harga, 0, ',', '.') : 'Rp 0' }}
                    </td>
                    <td>
                        {{ $item->status === 'kembali' ? 'Selesai' : ucfirst($item->status) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
