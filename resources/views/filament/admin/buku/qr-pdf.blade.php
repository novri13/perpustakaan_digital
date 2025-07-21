<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR Code Buku</title>
    <style>
        body { font-family: sans-serif; }
        .grid { display: flex; flex-wrap: wrap; gap: 16px; }
        .item { width: 100px; text-align: center; }
        .item img { width: 100px; height: 100px; object-fit: contain; }
    </style>
</head>
<body>
    <h2 style="text-align: center; margin-bottom: 20px;">Daftar QR Code Buku</h2>

    <div class="grid">
        @foreach ($bukus as $buku)
            <div class="item">
                @if ($buku->qr_code)
                    <img src="{{ public_path('storage/' . $buku->qr_code) }}" alt="QR {{ $buku->id }}">
                    <div style="margin-top: 8px;">{{ $buku->id }}</div>
                @endif
            </div>
        @endforeach
    </div>
</body>
</html>
