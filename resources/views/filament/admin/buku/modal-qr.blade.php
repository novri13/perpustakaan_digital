<div style="font-family: sans-serif;">
    <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 1rem; color: #000;">Kode QR</h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 16px;">
        @foreach ($bukus as $buku)
            <div style="border: 1px solid #ccc; border-radius: 6px; padding: 8px; text-align: center; background-color: #fff;">
                <img src="{{ asset('storage/' . $buku->qr_code) }}" alt="QR {{ $buku->id }}" style="margin: 0 auto; width: 96px; height: 96px; object-fit: contain;">
                <div style="font-size: 14px; margin-top: 8px; font-weight: 500; color: #000;">{{ $buku->id }}</div>
            </div>
        @endforeach
    </div>

    <div style="display: flex; justify-content: center; margin-top: 24px; gap: 16px;">
        <a href="{{ route('admin.qrcode.pdf') }}" target="_blank"
           style="padding: 8px 16px; background-color: #4b5563; color: #fff; border-radius: 4px; text-decoration: none;">
            Cetak
        </a>
        {{-- <button type="button" onclick="window.dispatchEvent(new Event('close-modal'))"
                style="padding: 8px 16px; background-color: #d1d5db; color: #000; border-radius: 4px; border: none; cursor: pointer;">
            Kembali
        </button> --}}
    </div>
</div>
