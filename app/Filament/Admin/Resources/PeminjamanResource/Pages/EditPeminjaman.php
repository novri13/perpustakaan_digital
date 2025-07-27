<?php

namespace App\Filament\Admin\Resources\PeminjamanResource\Pages;

use App\Filament\Admin\Resources\PeminjamanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Buku;
use App\Models\TransaksiDenda;
use App\Models\Denda;
use Illuminate\Support\Facades\DB;

class EditPeminjaman extends EditRecord
{
    protected static string $resource = PeminjamanResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
    
    protected function afterSave(): void
    {
        $peminjaman = $this->record;

        // Jika status dikembalikan → cek denda
        if ($peminjaman->status === 'kembali') {
            DB::transaction(function () use ($peminjaman) {
                $hariTerlambat = now()->diffInDays($peminjaman->tanggal_kembali, false);

                if ($hariTerlambat > 0) {
                    // Ambil tarif denda keterlambatan
                    $tarif = Denda::where('jenis_denda', 'Keterlambatan/Hari')->first();

                    if ($tarif) {
                        $total = $hariTerlambat * $tarif->harga;

                        TransaksiDenda::create([
                            'user_id'        => $peminjaman->anggota->user_id, // sesuaikan jika pakai user_id
                            'peminjaman_id'  => $peminjaman->id,
                            'denda_id'       => $tarif->id,
                            'jumlah'         => $hariTerlambat,
                            'total_harga'    => $total,
                            'status_bayar'   => 'belum',
                        ]);

                        // Update status denda
                        $peminjaman->update(['status_denda' => 'belum_lunas']);
                    }
                } else {
                    // Tidak ada keterlambatan
                    $peminjaman->update(['status_denda' => 'tidak_ada']);
                }
            });
        }
    }
}
