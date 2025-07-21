<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Anggota - Perpustakaan Digital</title>
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- Bootstrap & FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      margin: 0;
      background-color: #f5f5f5;
    }

    header {
      background-color: #ddd;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #aaa;
    }

    header .logo {
      font-weight: bold;
    }

    header .menu {
      display: flex;
      gap: 20px;
    }

    header .menu span {
      cursor: pointer;
    }

    .breadcrumb {
      background-color: #aaa;
      color: white;
      padding: 10px 20px;
      font-size: 14px;
    }

    .container {
      display: flex;
    }

    aside {
      width: 220px;
      background-color: #eee;
      padding: 20px;
      height: calc(100vh - 130px);
    }

    aside .menu-item {
      display: flex;
      align-items: center;
      padding: 10px;
      background-color: #ccc;
      margin-bottom: 10px;
      cursor: pointer;
      border-radius: 5px;
    }

    aside .menu-item.active {
      background-color: #999;
    }

    main {
      flex: 1;
      padding: 20px;
    }

    .search-bar {
      margin-bottom: 20px;
    }

    .search-bar input {
      width: 100%;
      padding: 10px;
      font-size: 16px;
    }

    .card {
      background-color: white;
      padding: 20px;
      border: 1px solid #aaa;
      border-radius: 5px;
    }

    .card h2 {
      margin-top: 0;
    }

    .card ul {
      padding-left: 20px;
    }

    footer {
      background-color: #444;
      color: white;
      padding: 20px;
      display: flex;
      justify-content: space-between;
      font-size: 14px;
    }

    footer div {
      max-width: 33%;
    }
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
      <a class="nav-link" href="{{ route('home') }}">Beranda</a>
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

  <div class="breadcrumb">Beranda / Anggota</div>

  <div class="container">
   <aside class="bg-light p-3 border-end" style="min-height: 100vh; width: 220px;">
  <nav class="nav flex-column gap-2">

    <a href="{{ route('anggota.dashboard') }}" class="nav-link d-flex align-items-center px-3 py-2 rounded bg-secondary text-white">
      <i class="fas fa-home me-2"></i> Dashboard
    </a>

    <a href="{{ route('anggota.profil') }}" class="nav-link d-flex align-items-center px-3 py-2 rounded text-dark">
      <i class="fas fa-user me-2"></i> Profile
    </a>

    <a href="{{ route('anggota.history-transaksi') }}" class="nav-link d-flex align-items-center px-3 py-2 rounded text-dark">
      <i class="fas fa-book-open me-2"></i> Riwayat Peminjaman
    </a>

    <form action="{{ route('logout') }}" method="POST" class="w-100 m-0">
      @csrf
      <button type="submit" class="nav-link d-flex align-items-center px-3 py-2 rounded text-dark w-100 bg-transparent border-0 text-start">
        <i class="fas fa-sign-out-alt me-2"></i> Logout
      </button>
    </form>

  </nav>
</aside>


    <main>
      {{-- <div class="search-bar">
        <input type="text" placeholder="🔍 Masukkan Kata Kunci Untuk Mencari Buku"/>
      </div> --}}

      <div class="card">
        <h2>Dashboard Anggota</h2>
        <p><strong>Selamat Datang, {{ auth()->user()->name }}</strong></p>
        <p><strong>Catatan :</strong></p>
        <ul>
          <li>Anda dapat melakukan peminjaman buku dengan tempo yang sudah ditentukan.</li>
          <li>Anda dapat melakukan perpanjangan tempo peminjaman buku sebanyak 1 (satu) kali, dengan tempo yang sudah ditentukan.</li>
          <li>Saat melakukan pengembalian buku, harap mengembalikan buku sesuai waktu peminjaman lamanya 7 hari dan dapat diperpanjang 7 hari lagi (harus konfirmasi dengan petugas perpustakaan).</li>
          <li>Harap jaga buku dengan baik saat dibawa pulang ke rumah.</li>
        </ul>
      </div>
    </main>
  </div>

  <footer>
    <div>
      <img src="{{ asset('images/logo_perpus.png') }}" alt="logo" width="60" class="d-block mx-auto mb-2">
      <strong>SMA NEGERI 1 BENGKULU SELATAN</strong><br>
      Alamat: Jln. Pangeran Duayu Manna
    </div>
    <div>
      <strong>Tentang Kami</strong><br>
      Perpustakaan digital SMANSA menyajikan beragam koleksi buku, memenuhi kebutuhan anggota untuk sumber belajar dan referensi.
    </div>
    <div>
      <strong>Kontak</strong><br>
      Telp: (0739)21296 / Fax: (0739)2268<br>
      E-Mail: smanegeri1bs@gmail.com<br>
      Website: <a href="#" style="color: white;">https://sman1bs.sch.id/</a>
    </div>
  </footer>

   <!-- JS Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
