<div>
    <h3 class="text-lg font-semibold">Detail Denda</h3>
    <ul class="mt-2 space-y-2">
        @foreach($denda as $item)
            <li class="flex justify-between">
                <span>{{ $item->denda->jenis_denda }} ({{ $item->jumlah }} hari)</span>
                <span class="font-bold text-red-600">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</span>
            </li>
        @endforeach
    </ul>
    <div class="mt-4 text-right font-bold">
        Total: Rp {{ number_format($denda->sum('total_harga'), 0, ',', '.') }}
    </div>
</div>
