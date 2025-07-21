@props([
    'nisn' => null,
    'nama' => null,
])

<div class="rounded-md border-l-4 bg-gray-50 p-3 text-sm"
     :class="{ 'border-green-500': @js($nisn) && @js($nama), 'border-gray-300': !(@js($nisn) && @js($nama)) }">
    @if ($nisn)
        <div><strong>NISN / NIP:</strong> {{ $nisn }}</div>
        <div><strong>Nama Anggota:</strong> {{ $nama }}</div>
    @else
        <div class="text-gray-400 italic">Belum memilih / scan anggota.</div>
    @endif
</div>
