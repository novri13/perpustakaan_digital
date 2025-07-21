<x-filament::page>
    <form wire:submit.prevent="getData" style="display: flex; flex-wrap: wrap; align-items: end; gap: 16px; margin-bottom: 24px;">
        <div style="display: flex; flex-direction: column;">
            <label style="font-weight: 600; margin-bottom: 4px;">Tanggal Awal</label>
            <input type="date" wire:model.defer="tanggalAwal"
                style="padding: 8px 10px; border: 1px solid #ccc; border-radius: 6px; background: inherit; color: inherit;">
        </div>
        <div style="display: flex; flex-direction: column;">
            <label style="font-weight: 600; margin-bottom: 4px;">Tanggal Akhir</label>
            <input type="date" wire:model.defer="tanggalAkhir"
                style="padding: 8px 10px; border: 1px solid #ccc; border-radius: 6px; background: inherit; color: inherit;">
        </div>
        <button type="submit"
            style="padding: 10px 16px; background-color: #007BFF; color: white; border: none; border-radius: 6px; cursor: pointer;"
            onmouseover="this.style.backgroundColor='#0056b3'"
            onmouseout="this.style.backgroundColor='#007BFF'">
            Tampilkan
        </button>

        @if ($showData)
            <a href="{{ route('admin.laporan-peminjaman', ['awal' => $tanggalAwal, 'akhir' => $tanggalAkhir]) }}"
               target="_blank"
               style="padding: 10px 16px; background-color: #28a745; color: white; text-decoration: none; border-radius: 6px;">
                Cetak PDF
            </a>
            <a href="{{ route('admin.laporan-peminjaman.excel', ['awal' => $tanggalAwal, 'akhir' => $tanggalAkhir]) }}"
               style="padding: 10px 16px; background-color: #ffc107; color: black; text-decoration: none; border-radius: 6px;">
                Export Excel
            </a>
        @endif
    </form>

    @if ($showData)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead style="background-color: #f0f0f0;">
                    <tr>
                        <th style="border: 1px solid #ccc; padding: 8px;">Kode Pinjam</th>
                        <th style="border: 1px solid #ccc; padding: 8px;">NIP/NISN</th>
                        <th style="border: 1px solid #ccc; padding: 8px;">Nama Peminjam</th>
                        <th style="border: 1px solid #ccc; padding: 8px;">Tanggal Pinjam</th>
                        <th style="border: 1px solid #ccc; padding: 8px;">Tanggal Kembali</th>
                        <th style="border: 1px solid #ccc; padding: 8px;">Jumlah Buku</th>
                        <th style="border: 1px solid #ccc; padding: 8px;">Denda</th>
                        <th style="border: 1px solid #ccc; padding: 8px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr>
                            <td style="border: 1px solid #ccc; padding: 8px;">P{{ str_pad($item['id'], 6, '0', STR_PAD_LEFT) }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px;">{{ $item['anggota']['id'] ?? '-' }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px;">{{ $item['anggota']['nama'] ?? '-' }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px;">{{ \Carbon\Carbon::parse($item['tanggal_pinjam'])->format('d-m-Y') }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px;">{{ \Carbon\Carbon::parse($item['tanggal_kembali'])->format('d-m-Y') }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px;">{{ $item['jumlah_buku'] }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px;">
                                @if ($item['denda']['harga'] ?? 0)
                                    Rp {{ number_format($item['denda']['harga'], 0, ',', '.') }}
                                @else
                                    Tidak ada Denda
                                @endif
                            </td>
                            <td style="border: 1px solid #ccc; padding: 8px;">{{ ucfirst($item['status']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="border: 1px solid #ccc; padding: 16px; text-align: center;">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</x-filament::page>
