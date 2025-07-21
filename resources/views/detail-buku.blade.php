<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Buku | Perpustakaan Digital</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_perpus.png') }}">
  <style>
    body { background-color: #e9e9e9; }
    .breadcrumb-bar { background-color: #d9d9d9; padding: 10px 30px; font-weight: bold; }
    .search-box { background: #ddd; padding: 20px; }
    .detail-container { background-color: #bfbfbf; padding: 30px; }
    .book-cover { width: 250px; height: 350px; background-color: #eee; border: 2px solid #aaa; display: flex; align-items: center; justify-content: center; }
    .book-cover img { max-height: 100%; max-width: 100%; object-fit: contain; }
    .detail-info { background-color: #f2f2f2; padding: 25px; border-radius: 8px; }
    .footer { background: #fff; padding: 20px; border-top: 1px solid #ccc; margin-top: 30px; }
  </style>
</head>
<body>

 <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-2">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
      <img src="{{ asset('images/logo_perpus.png') }}" alt="Logo" width="50" class="me-2">
      <div class="lh-sm">
        <span class="fw-bold">Perpustakaan Digital</span><br>
        <small>SMA Negeri 1 Bengkulu Selatan</small>
      </div>
    </a>

    <div class="ms-auto d-flex align-items-center gap-3">
      <a class="nav-link text-primary" href="{{ route('home') }}">Beranda</a>
      <a class="nav-link" href="{{ route('denah') }}">Denah Pustaka</a>
      <a class="nav-link" href="{{ route('pustakawan') }}">Pustakawan</a>

      @guest
        <a class="btn btn-primary" href="{{ route('anggota.login.form') }}">Login</a>
      @else
        @php
          $user = auth()->user();
          $unreadCount = $user->unreadNotifications->count();
          $notifications = $user->notifications()->latest()->take(5)->get();
        @endphp

        <!-- Notifikasi Dropdown -->
        <div class="dropdown me-3">
          <a href="#" id="notifDropdown" class="text-dark position-relative" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell fa-lg"></i>
            @if($unreadCount > 0)
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount }}
              </span>
            @endif
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notifDropdown"
              style="min-width: 300px; max-height: 350px; overflow-y: auto;">
            <li class="dropdown-header fw-bold px-3 py-2">Notifikasi Terbaru</li>

            @forelse($notifications as $notif)
              <li class="px-3 py-2 border-bottom small">
                <div class="fw-bold">{{ $notif->data['judul'] ?? '-' }}</div>
                <div class="text-muted">{{ $notif->data['pesan'] ?? '' }}</div>
                <small class="text-secondary">{{ $notif->created_at->diffForHumans() }}</small>
              </li>
            @empty
              <li class="px-3 py-2 text-muted">Tidak ada notifikasi</li>
            @endforelse

            <li><hr class="dropdown-divider"></li>
            <li><span class="dropdown-item text-center small text-muted">Menampilkan 5 notifikasi terakhir</span></li>
          </ul>
        </div>

        <!-- Dropdown Profil -->
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            @if($user->gambar)
              <img src="{{ asset('storage/' . $user->gambar) }}" alt="Foto Profil" class="rounded-circle me-2" width="32" height="32">
            @else
              <i class="fas fa-user-circle fa-lg me-2"></i>
            @endif
            <span class="d-none d-md-inline">{{ $user->name }}</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
            <li><a class="dropdown-item" href="{{ route('anggota.dashboard') }}">Dashboard</a></li>
            <li><a class="dropdown-item" href="{{ route('anggota.profil') }}">Profil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">Logout</button>
              </form>
            </li>
          </ul>
        </div>
      @endguest
    </div>
  </div>
</nav>


<!-- Breadcrumb & Search -->
<div class="breadcrumb-bar">Beranda / Detail Buku</div>
<div class="search-box text-center">
  <div class="container">
    <form method="GET" action="{{ route('katalog') }}">
      <input type="text" name="q" class="form-control" placeholder="Masukkan Kata Kunci Untuk Mencari Buku">
    </form>
  </div>
</div>

<!-- Book Detail -->
<div class="container detail-container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="book-cover mx-auto">
        <img src="{{ asset('storage/' . $buku->gambar) }}" alt="{{ $buku->judul }}">
      </div>
    </div>
    <div class="col-md-6">
      <div class="detail-info">
        <h5 class="fw-bold">{{ $buku->judul }}</h5>
        <p><strong>Pengarang</strong> : {{ $buku->pengarang }}</p>
        <p><strong>ISBN/ISSN</strong> : {{ $buku->id }}</p>
        <p><strong>Tahun Terbit</strong> : 
          {{ $buku->tahun_terbit ? \Carbon\Carbon::parse($buku->tahun_terbit)->format('Y') : '-' }}
        </p>
        <p><strong>Kategori</strong> : {{ $buku->kategori->name ?? '-' }}</p>
        <p><strong>Rak</strong> : {{ $buku->rak->name ?? '-' }}</p>
        <p><strong>Penerbit</strong> : {{ $buku->penerbit->name ?? '-' }}</p>
        <p><strong>Edisi</strong> : {{ $buku->edisi ?? '-' }}</p>
        <p><strong>Sedang Dipinjam</strong> : {{ $sedangDipinjam }}</p>
        <p><strong>Jumlah Buku</strong> : {{ $buku->stok }} / {{ $buku->stok + $sedangDipinjam }}</p>
        <p><strong>Bahasa</strong> : {{ $buku->bahasa }}</p>
        <p><strong>Deskripsi</strong> : {{ $buku->deskripsi }}</p>

        <p class="mt-4 fw-bold">Download e-book dibawah</p>
        @if ($buku->lampiran_buku)
          <a href="{{ asset('storage/lampiran/' . $buku->lampiran_buku) }}" class="btn btn-dark" download>Download e-book</a>
        @else
          <button class="btn btn-secondary" disabled>Buku ini tidak mempunyai e-book</button>
        @endif

        <br><br>
        <a href="{{ route('katalog') }}" class="btn btn-light">Kembali</a>
        <a href="{{ route('home') }}" class="btn btn-light">Beranda</a>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="bg-white text-dark py-4 border-top">
  <div class="container">
    <div class="row text-center text-md-start">
      <div class="col-md-4 mb-3 text-center">
        <img src="{{ asset('images/logo_perpus.png') }}" alt="Logo" width="60" class="mb-2 mx-auto d-block">
        <h6 class="mt-2">SMA NEGERI 1 BENGKULU SELATAN</h6>
        <p>Alamat: Jln. Pangeran Duayu Manna</p>
      </div>
      <div class="col-md-4 mb-3">
        <h6>Tentang Kami</h6>
        <p>Perpustakaan digital SMANSA menyajikan beragam koleksi buku, memenuhi kebutuhan anggota untuk belajar dan referensi.</p>
      </div>
      <div class="col-md-4 mb-3">
        <h6>Kontak</h6>
        <p>Telp. (0739)21296 / Fax.(0739)2268<br>
        E-Mail: smanegeri1bs@gmail.com<br>
        Website: https://sman1bs.sch.id</p>
      </div>
    </div>
    <hr>
    <div class="text-center small">
      SMA Negeri 1 Bengkulu Selatan - © SiPertal 2025 | Version 1.0
    </div>
  </div>
</footer>


  <!-- Font Awesome (untuk icon user & bell) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

<!-- Bootstrap Bundle (sudah termasuk Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
