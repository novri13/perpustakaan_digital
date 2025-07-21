<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Anggota</title>
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- Bootstrap & FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: Arial, sans-serif; background: #f7f7f7; margin: 0; }

    header, footer {
      background: #ddd; padding: 15px 20px;
      display: flex; justify-content: space-between; align-items: center;
    }

    .breadcrumb {
      background: #999; color: white;
      padding: 10px 20px;
    }

    .container { display: flex; }

    aside {
      width: 220px; background: #eee;
      padding: 20px;
    }

    .menu-item {
      background: #ccc;
      margin-bottom: 10px;
      padding: 10px;
      border-radius: 4px;
      font-weight: bold;
    }

    .menu-item.active { background: #999; }

    main {
      flex: 1;
      padding: 30px;
    }

    .card {
      background: white;
      border: 1px solid #aaa;
      padding: 20px;
      border-radius: 6px;
      max-width: 850px;
    }

    .profile-grid {
      display: grid;
      grid-template-columns: 200px auto;
      gap: 15px;
    }

    .profile-grid img {
      width: 100%;
      height: auto;
      border: 1px solid #ccc;
      background: #f2f2f2;
      border-radius: 8px; /* biar lebih estetis */
      object-fit: cover; /* agar proporsional */
    }

    .profile-fields {
      display: grid;
      grid-template-columns: 180px auto;
      row-gap: 10px;
      column-gap: 10px;
    }

    .profile-fields label {
      font-weight: bold;
      text-align: right;
    }

    .profile-fields input {
      padding: 6px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 100%;
    }

    .profile-fields input[readonly] {
      background: #f9f9f9;
      pointer-events: none; /* Tidak bisa diklik/diseleksi */
      user-select: none;   /* Tidak bisa di-highlight */
      cursor: default;
    }

    .actions {
      margin-top: 20px;
      text-align: right;
    }

    .btn {
      padding: 8px 16px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .btn-primary {
      background: #007bff;
      color: white;
    }

    .btn-secondary {
      background: #aaa;
      color: white;
    }

    footer {
      font-size: 14px;
      background: #444;
      color: white;
      justify-content: space-around;
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

    <a href="{{ route('anggota.dashboard') }}" class="nav-link d-flex align-items-center px-3 py-2 rounded text-dark">
      <i class="fas fa-home me-2"></i> Dashboard
    </a>

    <a href="{{ route('anggota.profil') }}" class="nav-link d-flex align-items-center px-3 py-2 rounded bg-secondary text-white">
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
  <div class="card">
    <h3>Profil Anggota</h3>
    <form action="{{ route('anggota.profil.update') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="profile-grid">
        <!-- Foto Profil -->
        <img src="{{ $anggota->gambar ? asset('storage/' . $anggota->gambar) : 'https://via.placeholder.com/120' }}" alt="Foto Profil">

        <div class="profile-fields">
          <label>NIP/NISN:</label>
          <input type="text" value="{{ $anggota->id }}" readonly>

          <label>Nama:</label>
          <input type="text" value="{{ $anggota->nama }}" readonly>

          <label>Jurusan:</label>
          <input type="text" value="{{ $anggota->jurusan->nama ?? '-' }}" readonly>

          <label>Kelas:</label>
          <input type="text" value="{{ $anggota->kelas ?? '-' }}" readonly>

          <label>Jenis Kelamin:</label>
          <input type="text" value="{{ $anggota->jenkel == 'L' ? 'Laki-laki' : 'Perempuan' }}" readonly>

          <label>Alamat:</label>
          <input type="text" value="{{ $anggota->alamat ?? '-' }}" readonly>

          <label>No Tel Siswa:</label>
          <input type="text" value="{{ $anggota->no_telp ?? '-' }}" readonly>

          <label>Email:</label>
          <input type="text" value="{{ $anggota->email ?? '-' }}" readonly>

          <label>Jabatan:</label>
          <input type="text" value="{{ ucfirst($anggota->jabatan) }}" readonly>

          <label>Status:</label>
          <input type="text" value="{{ ucfirst($anggota->status) }}" readonly>

          <label>Password Baru:</label>
          <input type="password" name="password" placeholder="Password baru (opsional)">

          <label>Konfirmasi Password:</label>
          <input type="password" name="password_confirmation" placeholder="Konfirmasi Password">
        </div>
      </div>

      <div class="actions">
        <button type="reset" class="btn btn-secondary">Reset</button>
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
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
      Website: <a href="https://sman1bs.sch.id/" style="color: white;">https://sman1bs.sch.id/</a>
    </div>
  </footer>

<script>
  function updateProfile() {
    const password = document.querySelectorAll("input[type=password]")[0].value;
    const confirm = document.querySelectorAll("input[type=password]")[1].value;

    if (password && password !== confirm) {
      alert("Konfirmasi password tidak cocok.");
      return;
    }

    alert("Profil berhasil diperbarui!");
    // Kirim ke Laravel pakai AJAX atau submit form (jika disiapkan)
  }

  function batalEdit() {
    if (confirm("Yakin batal mengubah profil?")) {
      location.reload();
    }
  }
</script>
<!-- Font Awesome (untuk icon user & bell) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

<!-- Bootstrap Bundle (sudah termasuk Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
 