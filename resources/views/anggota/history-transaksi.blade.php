<!-- FILE: resources/views/anggota/history-transaksi.blade.php -->

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Transaksi Anggota</title>
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- Bootstrap & FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { margin: 0; font-family: Arial; background: #f5f5f5; }
    header { background: #ddd; padding: 15px 20px; display: flex; justify-content: space-between; }
    .menu span { margin-left: 20px; cursor: pointer; }
    .breadcrumb { background: #999; color: #fff; padding: 10px 20px; }
    .container { display: flex; }
    aside { width: 220px; background: #eee; padding: 20px; }
    .menu-item { padding: 10px; margin-bottom: 10px; background: #ccc; border-radius: 5px; }
    .menu-item.active { background: #999; }
    main { flex: 1; padding: 20px; }
    .tabs { display: flex; margin-bottom: 10px; border-bottom: 2px solid #ccc; }
    .tab { padding: 10px 15px; cursor: pointer; border: 1px solid #ccc; border-bottom: none; }
    .tab.active { background: #e0f0ff; font-weight: bold; }
    .card { background: #fff; padding: 20px; border: 1px solid #aaa; border-radius: 5px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #bbb; padding: 10px; text-align: center; }
    .pagination { display: flex; justify-content: center; margin-top: 10px; gap: 5px; }
    .eye-icon { cursor: pointer; font-size: 18px; }
    #detailModal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000; }
    #detailModal .modal-box { background: #fff; padding: 20px; width: 400px; border-radius: 8px; position: relative; }
    #detailModal .close-btn { position: absolute; top: 10px; right: 10px; cursor: pointer; }
    footer { background: #444; color: white; padding: 20px; display: flex; justify-content: space-between; margin-top: 30px; font-size: 14px; }
    footer div { max-width: 33%; }
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

<div class="breadcrumb">Beranda / Riwayat Transaksi</div>

<div class="container">
  <aside class="bg-light p-3 border-end" style="min-height: 100vh; width: 220px;">
  <nav class="nav flex-column gap-2">

    <a href="{{ route('anggota.dashboard') }}" class="nav-link d-flex align-items-center px-3 py-2 rounded text-dark">
      <i class="fas fa-home me-2"></i> Dashboard
    </a>

    <a href="{{ route('anggota.profil') }}" class="nav-link d-flex align-items-center px-3 py-2 rounded text-dark">
      <i class="fas fa-user me-2"></i> Profile
    </a>

    <a href="{{ route('anggota.history-transaksi') }}" class="nav-link d-flex align-items-center px-3 py-2 rounded bg-secondary text-white">
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

    {{-- TAB NAV --}}
    <ul class="nav nav-tabs" id="riwayatTabs" role="tablist">
      <li class="nav-item">
        <button class="nav-link active" id="peminjaman-tab"
          data-bs-toggle="tab"
          data-bs-target="#tab-peminjaman"
          type="button" role="tab">
          📚 Sedang Dipinjam
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link" id="pengembalian-tab"
          data-bs-toggle="tab"
          data-bs-target="#tab-pengembalian"
          type="button" role="tab">
          ✅ Sudah Dikembalikan
        </button>
      </li>
    </ul>

    {{-- TAB CONTENT WAJIB ADA! --}}
    <div class="tab-content p-3">

      {{-- TAB PEMINJAMAN --}}
      <div class="tab-pane fade show active" id="tab-peminjaman" role="tabpanel">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Judul Buku</th>
              <th>Tgl Pinjam</th>
              <th>Jatuh Tempo</th>
              <th>Jumlah Buku</th>
              <th>Denda</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($peminjamanAktif as $pinjam)
              <tr>
                <td>{{ $pinjam->kode_peminjaman }}</td>
                <td>{{ $pinjam->buku->judul ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_kembali)->format('d/m/Y') }}</td>
                <td>{{ $pinjam->jumlah_buku ?? 1 }}</td> 
                <td>{{ $pinjam->denda ? 'Rp '.number_format($pinjam->denda->jumlah ?? 0,0,',','.') : 'Tidak Ada' }}</td>
                <td><span class="badge bg-warning text-dark">{{ ucfirst($pinjam->status) }}</span></td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center">Tidak ada buku yang sedang dipinjam.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- TAB PENGEMBALIAN --}}
      <div class="tab-pane fade" id="tab-pengembalian" role="tabpanel">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Judul Buku</th>
              <th>Tgl Pinjam</th>
              <th>Tgl Kembali</th>
              <th>Denda</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($peminjamanSelesai as $pinjam)
              <tr>
                <td>{{ $pinjam->kode_peminjaman }}</td>
                <td>{{ $pinjam->buku->judul ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_kembali)->format('d/m/Y') }}</td>
                <td>{{ $pinjam->denda ? 'Rp '.number_format($pinjam->denda->jumlah ?? 0,0,',','.') : 'Tidak Ada' }}</td>
                <td><span class="badge bg-success">{{ ucfirst($pinjam->status) }}</span></td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">Belum ada riwayat pengembalian buku.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>
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

<div id="detailModal">
  <div class="modal-box">
    <span class="close-btn" onclick="closeModal()">❌</span>
    <h3>Detail Transaksi</h3>
    <div id="modalContent">Memuat data...</div>
  </div>
</div>

<script>
  function switchTab(tabId) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('#tab-peminjaman, #tab-pengembalian').forEach(t => t.style.display = 'none');
    if (tabId === 'tab-peminjaman') {
      document.querySelector('.tab:first-child').classList.add('active');
    } else {
      document.querySelector('.tab:last-child').classList.add('active');
    }
    document.getElementById(tabId).style.display = 'block';
  }

  function showModal(kode) {
    const modal = document.getElementById('detailModal');
    const content = document.getElementById('modalContent');
    modal.style.display = 'flex';
    content.innerHTML = `<p><strong>Kode:</strong> ${kode}</p><p><strong>Status:</strong> Data akan ditampilkan di sini</p>`;
  }

  function closeModal() {
    document.getElementById('detailModal').style.display = 'none';
  }

  function showModal(kodePinjam) {
    alert("Detail untuk kode pinjam: " + kodePinjam);
}
</script>

<!-- Font Awesome (untuk icon user & bell) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

<!-- Bootstrap Bundle (sudah termasuk Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>