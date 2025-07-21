<x-filament::page>
    <form wire:submit.prevent="getData" style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; margin-bottom: 24px;">
        <div style="display: flex; flex-direction: column;">
            <label style="font-weight: 600; margin-bottom: 4px;">Tanggal Awal</label>
            <input type="date" wire:model="tanggalAwal"
                   style="padding: 6px 10px; font-size: 14px; border: 1px solid #ccc; border-radius: 5px;">
        </div>
        <div style="display: flex; flex-direction: column;">
            <label style="font-weight: 600; margin-bottom: 4px;">Tanggal Akhir</label>
            <input type="date" wire:model="tanggalAkhir"
                   style="padding: 6px 10px; font-size: 14px; border: 1px solid #ccc; border-radius: 5px;">
        </div>
        <button type="submit"
                style="padding: 8px 16px; background-color: #2563eb; color: white; border: none; border-radius: 5px;">
            Tampilkan
        </button>

        @if ($showData)
            <a href="{{ route('admin.laporan-pengembalian.pdf', ['awal' => $tanggalAwal, 'akhir' => $tanggalAkhir]) }}"
               target="_blank"
               style="padding: 8px 16px; background-color: green; color: white; border-radius: 5px; text-decoration: none;">
                Cetak PDF
            </a>
            <a href="{{ route('admin.laporan-pengembalian.excel', ['awal' => $tanggalAwal, 'akhir' => $tanggalAkhir]) }}"
               style="padding: 8px 16px; background-color: orange; color: black; border-radius: 5px; text-decoration: none;">
                Export Excel
            </a>
        @endif
    </form>

    @if ($showData)
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead style="background-color: #f5f5f5;">
                    <tr>
                        <th style="border: 1px solid #ccc; padding: 8px;">No</th>
                        <th style="border: 1px solid #ccc; padding: 8px;">Kode Pinjam</th>
                        <th style="border: 1px solid #ccc; padding: 8px;">NIP/NISN</th>
                        <th style="border: 1px solid #ccc; padding: 8px;">Nama Peminjam</th>
                        <th style="border: 1px solid #ccc; padding: 8px;">Tanggal Pinjam</th>
                        <th style="border: 1px solid #ccc; padding: 8px;">Tanggal Kembali</th>
                        <th style="border: 1px solid #ccc; padding: 8px;">Denda</th>
                        <th style="border: 1px solid #ccc; padding: 8px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $item)
                        <tr>
                            <td style="border: 1px solid #ccc; padding: 8px; text-align: center;">{{ $index + 1 }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px;">P{{ str_pad($item['id'], 6, '0', STR_PAD_LEFT) }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px;">{{ $item['anggota']['id'] ?? '-' }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px;">{{ $item['anggota']['nama'] ?? '-' }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px;">{{ \Carbon\Carbon::parse($item['tanggal_pinjam'])->format('d-m-Y') }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px;">{{ \Carbon\Carbon::parse($item['tanggal_kembali'])->format('d-m-Y') }}</td>
                            <td style="border: 1px solid #ccc; padding: 8px;">
                                @if ($item['denda']['harga'] ?? 0)
                                    Rp {{ number_format($item['denda']['harga'], 0, ',', '.') }}
                                @else
                                    Rp 0
                                @endif
                            </td>
                            <td style="border: 1px solid #ccc; padding: 8px; text-align: center;">Selesai</td>
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
