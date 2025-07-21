<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Denah Perpustakaan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .denah-box {
      height: 80px;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 13px;
      color: #fff;
      border: 1px solid #dee2e6;
    }

    .rak { background-color: #6c757d; }
    .baca-personal { background-color: #0d6efd; }
    .baca-kelompok { background-color: #198754; height: 160px !important; }
    .buku-paket { background-color: #ffc107; }
    .sirkulasi { background-color: #6610f2; }
    .pustakawan { background-color: #fd7e14; }
    .pintu { background-color: #adb5bd; }
    .kepala { background-color: #20c997; }
  </style>
</head>
<body class="bg-light">

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
      <a class="nav-link" href="{{ route('home') }}">Beranda</a>
      <a class="nav-link text-primary" href="{{ route('denah') }}">Denah Pustaka</a>
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

<!-- Header -->
<div class="container py-4">
  <h3 class="text-center mb-4">Denah Perpustakaan</h3>

  <div class="bg-white border rounded p-4 shadow-sm">
    <!-- Baris 1 -->
    <div class="row g-2">
      <div class="col-2 denah-box rak">Lemari 1</div>
      <div class="col-2 denah-box rak">Lemari 2</div>
      <div class="col-2 denah-box rak">Lemari 3</div>
      <div class="col-2 denah-box rak">Lemari 4</div>
      <div class="col-8"></div>
    </div>

    <!-- Baris 2 -->
    <div class="row g-2 mt-2">
      <div class="col-2">
        <div class="row g-2">
          <div class="col-12 denah-box baca-personal">Meja Baca<br>Personal</div>
          <div class="col-12 denah-box baca-personal">Meja Baca<br>Personal</div>
          <div class="col-12 denah-box baca-personal">Meja Baca<br>Personal</div>
        </div>
      </div>
      <div class="col-4 denah-box baca-kelompok">Meja Baca Kelompok</div>
      <div class="col-2 denah-box buku-paket">Lemari Buku Paket</div>
      <div class="col-4">
        <div class="row g-2">
          <div class="col-6 denah-box buku-paket">Lemari Buku Paket</div>
          <div class="col-6 denah-box buku-paket">Lemari Buku Paket</div>
        </div>
      </div>
    </div>

    <!-- Baris 3 -->
    <div class="row g-2 mt-2">
      <div class="col-2 denah-box sirkulasi">Meja Sirkulasi</div>
      <div class="col-4 denah-box pustakawan">Meja Pustakawan</div>
      <div class="col-2 denah-box pintu">Pintu</div>
      <div class="col-2 denah-box kepala">Meja Kepala</div>
    </div>
  </div>

  <!-- Keterangan -->
  <div class="mt-4">
    <h5>Keterangan:</h5>
    <ol>
      <li>Lemari koleksi</li>
      <li>Meja baca personal</li>
      <li>Meja baca kelompok</li>
      <li>Lemari koleksi buku paket</li>
      <li>Meja pencatatan sirkulasi</li>
      <li>Meja petugas perpustakaan</li>
      <li>Pintu</li>
      <li>Meja kepala perpustakaan</li>
    </ol>
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
        E-Mail: smanegeri1bs@gmail.com
        Website: https://sman11bs.sch.id</p>
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
