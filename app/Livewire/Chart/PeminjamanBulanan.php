<?php

namespace App\Livewire\Chart;

use App\Models\Peminjaman;
use Carbon\Carbon;
use Livewire\Component;

class PeminjamanBulanan extends Component
{
    public $labels = [];
    public $data = [];

    public function mount()
    {
        $this->generateChartData();
    }

    public function generateChartData()
    {
        $this->labels = [];
        $this->data = [];

        for ($i = 0; $i < 6; $i++) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $this->labels[] = Carbon::now()->subMonths($i)->format('M Y');
            $this->data[] = Peminjaman::where('status', 'dipinjam')
                ->whereYear('tanggal_pinjam', Carbon::parse($month)->year)
                ->whereMonth('tanggal_pinjam', Carbon::parse($month)->month)
                ->count();
        }

        $this->labels = array_reverse($this->labels);
        $this->data = array_reverse($this->data);
    }

    public function render()
    {
        return view('livewire.chart.peminjaman-bulanan');
    }
}

