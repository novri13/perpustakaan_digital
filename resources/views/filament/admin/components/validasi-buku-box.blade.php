@props([
    'isbn' => null,
    'judul' => null,
])

<div class="rounded-md border-l-4 bg-gray-50 p-3 text-sm"
     :class="{ 'border-green-500': @js($isbn) && @js($judul), 'border-gray-300': !(@js($isbn) && @js($judul)) }">
    @if ($isbn)
        <div><strong>ISBN / ISSN:</strong> {{ $isbn }}</div>
        <div><strong>Judul Buku:</strong> {{ $judul }}</div>
    @else
        <div class="text-gray-400 italic">Belum memilih / scan buku.</div>
    @endif
</div>
