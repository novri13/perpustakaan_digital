<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Buku | Perpustakaan Digital</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_perpus.png') }}">

  <style>
    body {
      background-color: #f7f8fa;
      font-family: "Segoe UI", Roboto, sans-serif;
    }

    .breadcrumb-bar {
      background: #fff;
      padding: 12px 30px;
      font-size: 0.95rem;
      border-bottom: 1px solid #e0e0e0;
    }

    .search-box {
      background: #fff;
      padding: 25px;
      border-bottom: 1px solid #e0e0e0;
    }
    .search-box .form-control {
      max-width: 500px;
      margin: 0 auto;
      border-radius: 30px;
      padding: 12px 20px;
    }

    .detail-container {
      background: #fff;
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      margin-top: 30px;
    }

    .book-cover {
      width: 100%;
      max-width: 280px;
      height: 380px;
      background: #f2f2f2;
      border: 1px solid #ddd;
      border-radius: 8px;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
    }
    .book-cover img {
      max-height: 100%;
      max-width: 100%;
      object-fit: contain;
    }

    .detail-info h5 {
      font-size: 1.4rem;
      font-weight: 700;
      margin-bottom: 20px;
    }
    .detail-info p {
      margin-bottom: 8px;
      font-size: 0.95rem;
    }

    .btn-rounded {
      border-radius: 30px;
      padding: 8px 20px;
    }

    .footer {
      background: #fff;
      padding: 25px 0;
      border-top: 1px solid #ddd;
      margin-top: 50px;
      text-align: center;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

  {{-- NAVBAR --}}
  @include('layouts.partials.navbar')

  <!-- Breadcrumb -->
  <div class="breadcrumb-bar">
    <div class="container">
      <span>Beranda</span> / <span class="text-secondary">Detail Buku</span>
    </div>
  </div>

  <!-- Search Box -->
  <div class="search-box text-center">
    <form method="GET" action="{{ route('katalog') }}">
      <input type="text" name="q" class="form-control shadow-sm" placeholder="Cari buku...">
    </form>
  </div>

  <!-- Book Detail -->
  <div class="container">
    <div class="detail-container">
      <div class="row justify-content-center align-items-start">
        
        <!-- Cover Buku -->
        <div class="col-md-4 text-center">
          <div class="book-cover shadow-sm">
            <img 
              src="{{ $buku->gambar ? asset('storage/' . $buku->gambar) : asset('storage/no-cover.png') }}" 
              alt="{{ $buku->judul }}">
          </div>
        </div>
        
        <!-- Detail Info -->
        <div class="col-md-7">
          <div class="detail-info">
            <h5>{{ $buku->judul }}</h5>

            <p><strong>Pengarang:</strong> {{ $buku->pengarang }}</p>
            <p><strong>ISBN/ISSN:</strong> {{ $buku->id }}</p>
            <p><strong>Tahun Terbit:</strong> 
              {{ $buku->tahun_terbit ? \Carbon\Carbon::parse($buku->tahun_terbit)->format('Y') : '-' }}
            </p>
            <p><strong>Kategori:</strong> {{ $buku->kategori->name ?? '-' }}</p>
            <p><strong>Rak:</strong> {{ $buku->rak->name ?? '-' }}</p>
            <p><strong>Penerbit:</strong> {{ $buku->penerbit->name ?? '-' }}</p>
            <p><strong>Edisi:</strong> {{ $buku->edisi ?? '-' }}</p>
            <p><strong>Sedang Dipinjam:</strong> {{ $sedangDipinjam }}</p>
            <p><strong>Jumlah Buku:</strong> {{ $buku->stok }} / {{ $buku->stok + $sedangDipinjam }}</p>
            <p><strong>Bahasa:</strong> {{ $buku->bahasa }}</p>
            <p class="mt-3"><strong>Deskripsi:</strong> <br>{{ $buku->deskripsi }}</p>

            <!-- Tombol Download e-book -->
            <div class="mt-4">
              <p class="fw-bold mb-2">Download e-book:</p>
              @if ($buku->lampiran_buku)
                <a href="{{ asset('storage/lampiran/' . $buku->lampiran_buku) }}" 
                   class="btn btn-primary btn-rounded shadow-sm" download>
                  <i class="fas fa-download me-1"></i> Download e-book
                </a>
              @else
                <button class="btn btn-secondary btn-rounded shadow-sm" disabled>
                  <i class="fas fa-ban me-1"></i> Tidak tersedia
                </button>
              @endif
            </div>

            <!-- Tombol Booking Buku -->
            <div class="mt-3">
            @auth
              @if(auth()->user()->hasRole('anggota'))
                @if($buku->stok > 0)
                  @if(!$sudahBooking && $jumlahBooking < 3)
                    <form action="{{ route('anggota.bookings.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                        <button type="submit" class="btn btn-success">Booking Buku</button>
                    </form>
                  @elseif($sudahBooking)
                    <div class="alert alert-warning mt-2">
                      Kamu sudah booking buku ini atau sedang meminjamnya.
                    </div>
                  @elseif($jumlahBooking >= 3)
                    <div class="alert alert-danger mt-2">
                      Kamu sudah booking 3 buku. Hapus satu untuk menambah.
                    </div>
                  @endif
                @else
                  <button class="btn btn-secondary btn-rounded shadow-sm" disabled>
                    <i class="fas fa-times"></i> Stok Habis
                  </button>
                @endif
              @endif
            @else
              <a href="{{ route('anggota.login.form', ['redirect' => request()->fullUrl()]) }}" 
                class="btn btn-success btn-rounded shadow-sm">
                <i class="fas fa-calendar-plus me-1"></i> Booking Buku
              </a>
            @endauth
            </div>

            <!-- Tombol Navigasi -->
            <div class="mt-4">
              <a href="{{ route('katalog') }}" class="btn btn-light btn-rounded shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
              </a>
              <a href="{{ route('home') }}" class="btn btn-light btn-rounded shadow-sm">
                <i class="fas fa-home me-1"></i> Beranda
              </a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- FOOTER --}}
  @include('layouts.partials.footer')

  <!-- Font Awesome -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
  <!-- Bootstrap Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
