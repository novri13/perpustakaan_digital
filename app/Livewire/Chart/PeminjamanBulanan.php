<?php

namespace App\Livewire\Chart;

use App\Models\Peminjaman;
use Carbon\Carbon;
use Livewire\Component;

class PeminjamanBulanan extends Component
{
    public $labels = [];
    public $peminjamanData = [];
    public $pengembalianData = [];
    public $tahun;

    public function mount($tahun = null)
    {
        // Default tahun = tahun berjalan
        $this->tahun = $tahun ?? now()->year;
        $this->generateChartData();
    }

    public function updatedTahun()
    {
        $this->generateChartData();
    }

    public function generateChartData()
    {
        $this->labels = [];
        $this->peminjamanData = [];
        $this->pengembalianData = [];

        // 12 bulan
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $this->labels[] = Carbon::create()->month($bulan)->format('M');

            // Hitung peminjaman per bulan (created_at)
            $pinjam = Peminjaman::whereYear('created_at', $this->tahun)
                ->whereMonth('created_at', $bulan)
                ->count();

            // Hitung pengembalian per bulan (updated_at + status kembali)
            $kembali = Peminjaman::where('status', 'kembali')
                ->whereYear('updated_at', $this->tahun)
                ->whereMonth('updated_at', $bulan)
                ->count();

            $this->peminjamanData[] = $pinjam;
            $this->pengembalianData[] = $kembali;
        }
    }

    public function render()
    {
        return view('livewire.chart.peminjaman-bulanan');
    }
}

