
    <div style="background-color: #e5e7eb; padding: 1rem; font-family: sans-serif; font-size: 14px;">
        {{-- Header --}}
        <div style="background-color: #d1d5db; padding: 8px; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 18px; margin-bottom: 1rem;">
            Kartu Anggota Perpustakaan <br>
            SMA NEGERI 1 BENGKULU SELATAN
        </div>

        {{-- Konten Utama --}}
        <div class="row" style="display: flex; flex-direction: row; background-color: #ffffff; border: 1px solid black;">

            {{-- Kolom Kiri --}}
            <div style="width: 50%; padding: 1rem; border-right: 1px solid black; display: flex; flex-direction: column; align-items: center;">
                <div style="display: flex; justify-content: center; margin-bottom: 0.5rem;">
                    <img src="{{ asset('images/logo_perpus.png') }}" alt="Logo" style="height: 50px; margin-right: 10px;">
                </div>

                <div style="text-align: center; font-size: 15px; font-weight: bold; text-transform: uppercase; line-height: 1.1;">
                    SMA NEGERI 1 BENGKULU SELATAN
                </div>

                <div style="text-align: center; font-size: 8px;">
                    Jln. Pangeran Duayu No.04, Manna, Bengkulu Selatan 38515 Telp. (0739) 21045 — Fax.(0739) 21428<br>
                    Email: smanegeri1bs@gmail.com — Website: http://sman1bs.sch.id
                </div>

                <div style="text-align: center; margin-top: 0.5rem; font-weight: 600; font-size: 12px;">
                    Kartu Anggota Perpustakaan
                </div>

                <div style="display: flex; justify-content: center; margin-top: 0.5rem;">
                    <img src="{{ asset('storage/' . $anggota->gambar) }}" alt="Foto Anggota" style="width: 96px; height: 112px; object-fit: cover; border: 1px solid #000;">
                </div>

                <div style="text-align: center; font-weight: bold; font-size: 12px; margin-top: 0.5rem; text-transform: uppercase;">
                    {{ $anggota->nama }}
                </div>

                <div style="text-align: center; font-size: 10px; letter-spacing: 1px;">
                    {{ $anggota->id }}
                </div>

                <div style="text-align: center; font-size: 12px; font-weight: bold; margin-top: 0.5rem; text-transform: uppercase;">
                    JURUSAN : {{ $anggota->jurusan->name ?? '-' }}
                </div>
            </div>

            {{-- Kolom Kanan --}}
            <div style="width: 50%; padding: 1rem;">
                <div style="text-align: center; font-weight: bold; font-size: 12px; text-transform: uppercase; margin-bottom: 0.5rem;">
                    TATA TERTIB PERPUSTAKAAN
                </div>

                <ol style="list-style-type: decimal; font-size: 11px; line-height: 1.4; padding-left: 20px; margin: 0;">
                    <li style="margin-bottom: 4px;">Kartu Anggota ini harus dibawa setiap kunjungan, pinjaman, pengembalian ke perpustakaan.</li>
                    <li style="margin-bottom: 4px;">Tanpa kartu anggota, peminjaman, pengembalian tidak dilayani.</li>
                    <li style="margin-bottom: 4px;">Penggunaan kartu ini berlaku khusus selama menjadi anggota terdaftar di SMA Negeri 1 Bengkulu Selatan.</li>
                    <li style="margin-bottom: 4px;">Anggota bertanggung jawab atas kerusakan dan kehilangan buku yang dipinjam.</li>
                    <li style="margin-bottom: 4px;">Waktu peminjaman 7 hari dan dapat diperpanjang 7 hari lagi (harus konfirmasi dengan petugas perpustakaan).</li>
                </ol>

                <div style="text-align: center; font-weight: 600; font-size: 12px; margin-top: 1rem;">
                    SCAN HERE
                </div>

                <div style="display: flex; justify-content: center;">
                    <img src="{{ asset('storage/' . $anggota->qr_code) }}" alt="QR Code" style="width: 96px; height: 96px; border: 1px solid #000;">
                </div>

                <p style="text-align: center; font-size: 10px; margin-top: 0.5rem; line-height: 1.3; padding: 0 0.25rem;">
                    Kartu ini adalah milik SMA Negeri 1 Bengkulu Selatan, dipergunakan oleh orang yang bersangkutan dan berlaku selama menjadi anggota terdaftar di SMA Negeri 1 Bengkulu Selatan. <br><br>
                    Jika menemukan kartu ini, mohon dapat dikembalikan pada petugas yang ada di depan kartu anggota perpustakaan.
                </p>
            </div>
        </div>

        {{-- Tombol --}}
        <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 1rem;">
            <button onclick="window.print()" style="background-color: #1f2937; color: white; padding: 0.25rem 1rem; border-radius: 4px; font-size: 12px; border: none; cursor: pointer;">
                Cetak
            </button>
            <button onclick="history.back()" style="background-color: #9ca3af; color: white; padding: 0.25rem 1rem; border-radius: 4px; font-size: 12px; border: none; cursor: pointer;">
                Kembali
            </button>
        </div>
    </div>

