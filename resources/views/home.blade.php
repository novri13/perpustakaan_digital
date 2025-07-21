<!DOCTYPE html>
<html lang="id">
<head>
  <!-- ============================================
       STYLE GLOBAL & KOMPONEN
       (Hanya penataan ulang / indentasi. Tidak ada perubahan properti.)
       ============================================ -->
  <style>
    /* Judul buku (versi 1 - dari kode asli) */
    .book-title {
      font-size: 0.85rem;        /* lebih kecil dari default */
      font-weight: 600;          /* sedikit tebal */
      line-height: 1.2;          /* rapat tapi rapi */
      text-align: center;        /* biar tetap rata tengah */
      white-space: normal;       /* bisa turun baris */
      word-wrap: break-word;     /* pecah kata panjang */
      min-height: 32px;          /* jaga tinggi biar rata */
      display: -webkit-box;      /* buat 2 baris max */
      -webkit-line-clamp: 2;     /* max 2 baris */
      -webkit-box-orient: vertical;
      overflow: hidden;          /* sembunyikan sisa */
    }

    /* Judul buku (versi 2 - duplikat dari kode asli, tetap dipertahankan) */
    .book-title {
      font-size: 0.85rem;
      font-weight: 600;
      line-height: 1.2;
      text-align: center;
      white-space: normal;
      word-wrap: break-word;
      min-height: 32px; /* biar tinggi seragam */
      display: -webkit-box;       /* aktifkan multi-line clamp */
      -webkit-line-clamp: 2;      /* maksimal 2 baris */
      -webkit-box-orient: vertical;
      overflow: hidden;           /* sembunyikan teks lebih */
    }

    /* Ukuran tetap kecil untuk gambar kategori */
    .kategori-img-wrapper {
      width: 60px;
      height: 60px;
      overflow: hidden;
      margin: 0 auto 8px auto;
      border-radius: 10px;
    }

    .kategori-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    /* Teks kategori */
    .kategori-title {
      font-size: 12px;
      font-weight: 500;
      line-height: 1.2;
      max-width: 70px;
      margin: 0 auto;
      word-wrap: break-word;
      text-align: center;
    }

    /* Hover animasi zoom pada gambar */
    .kategori-item:hover .kategori-img,
    .kategori-item-modal:hover .kategori-img {
      transform: scale(1.1);
    }

    /* Hover shadow & scale sedikit */
    .kategori-item,
    .kategori-item-modal {
      transition: all 0.3s ease;
    }

    .kategori-item:hover,
    .kategori-item-modal:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }

    /* Modal muncul dengan animasi zoom-in */
    .kategori-modal-anim .modal-dialog {
      transform: scale(0.9);
      opacity: 0;
      transition: all 0.3s ease-in-out;
    }

    .kategori-modal-anim.show .modal-dialog {
      transform: scale(1);
      opacity: 1;
    }

    /* Ikon “Lainnya” efek hover */
    .kategori-lainnya:hover {
      background: #f8f9fa;
      transform: scale(1.05);
    }

    /* Kartu anggota peminjam */
    .penikmat-card:hover {
      transform: translateY(-5px) scale(1.05);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
  </style>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perpustakaan Digital</title>
  <link rel="icon" href="{{ asset('images/logo_perpus.png') }}">

  <!-- Bootstrap & FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-light">
  @php use Illuminate\Support\Str; @endphp

  <!-- ============================================ -->
  <!-- NAVBAR HEADER                                 -->
  <!-- ============================================ -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-2">
    <div class="container-fluid">
      <!-- Brand / Logo Sekolah -->
      <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
        <img src="{{ asset('images/logo_perpus.png') }}" alt="Logo" width="50" class="me-2">
        <div class="lh-sm">
          <span class="fw-bold">Perpustakaan Digital</span><br>
          <small>SMA Negeri 1 Bengkulu Selatan</small>
        </div>
      </a>

      <!-- Link kanan: menu statis + auth -->
      <div class="ms-auto d-flex align-items-center gap-3">
        <a class="nav-link text-primary" href="{{ route('home') }}">Beranda</a>
        <a class="nav-link" href="{{ route('denah') }}">Denah Pustaka</a>
        <a class="nav-link" href="{{ route('pustakawan') }}">Pustakawan</a>

        @guest
          <!-- Login saat belum auth -->
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

          <!-- Dropdown Profil User -->
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

  <!-- ============================================ -->
  <!-- HERO / WELCOME SECTION                       -->
  <!-- ============================================ -->
  <div class="bg-secondary text-white text-center py-4">
    <h4>Selamat Datang</h4>
    <p>Di Perpustakaan Digital SMA Negeri 1 Bengkulu Selatan<br>NPSN : 10700973</p>
  </div>

  {{-- <!-- Search (versi lama dikomentari) -->
  <div class="container py-3">
    <input type="text" class="form-control" placeholder="Masukkan Kata Kunci Untuk Mencari Buku">
  </div> --}}

  <!-- ============================================ -->
  <!-- BREADCRUMB & SEARCH FORM                      -->
  <!-- ============================================ -->
  <div class="container py-3">
    <form method="GET" action="{{ route('katalog') }}" class="w-100">
      <input type="text" name="q" class="form-control" placeholder="Masukkan Kata Kunci Untuk Mencari Buku">
    </form>
  </div>

  <!-- ============================================ -->
  <!-- KATEGORI UTAMA (4 pertama + tombol lainnya)   -->
  <!-- ============================================ -->
  <div class="bg-secondary text-white py-3">
    <div class="container">
      <h5 class="text-center mb-3">Pilih kategori yang kamu suka</h5>
      <div class="d-flex justify-content-center flex-wrap">

        {{-- Tampilkan hanya 4 kategori pertama --}}
        @foreach($kategoriUtama as $k)
          @php
            $kategoriImage = $k->gambar 
                ? asset('storage/'.$k->gambar) 
                : asset('storage/no-cover.png');
          @endphp

          <a href="{{ route('katalog', ['kategori' => $k->id]) }}" 
             class="kategori-item text-center bg-white p-3 m-2 rounded shadow-sm" 
             style="text-decoration:none; color:inherit;">
            <div class="kategori-img-wrapper">
              <img src="{{ $kategoriImage }}" alt="{{ $k->name }}" class="kategori-img">
            </div>
            <div class="kategori-title text-dark">{{ $k->name }}</div>
          </a>
        @endforeach

        {{-- Tombol Lainnya --}}
        @if($allKategori->count() > 4)
          <div class="kategori-item text-center bg-light p-3 m-2 rounded shadow-sm kategori-lainnya"
               style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#kategoriModal">
            <div class="kategori-img-wrapper d-flex justify-content-center align-items-center">
              <img src="https://img.icons8.com/ios-filled/50/000000/menu-2.png" 
                   alt="Titik 9" width="28" height="28" />
            </div>
            <div class="kategori-title text-dark">Lainnya</div>
          </div>
        @endif

      </div>
    </div>
  </div>

  <!-- ============================================ -->
  <!-- MODAL: SEMUA KATEGORI                         -->
  <!-- ============================================ -->
  <div class="modal fade kategori-modal-anim" id="kategoriModal" tabindex="-1" aria-labelledby="kategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content shadow-lg">
        <div class="modal-header bg-secondary text-white">
          <h5 class="modal-title" id="kategoriModalLabel">✨ Semua Kategori</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            @foreach($allKategori as $k)
              @php
                $kategoriImage = $k->gambar 
                    ? asset('storage/'.$k->gambar) 
                    : asset('storage/no-cover.png');
              @endphp

              <div class="col-md-3 col-6 text-center mb-4">
                <a href="{{ route('katalog', ['kategori' => $k->id]) }}" 
                   class="kategori-item-modal bg-light p-3 rounded shadow-sm d-block text-decoration-none text-dark">
                  <div class="kategori-img-wrapper">
                    <img src="{{ $kategoriImage }}" alt="{{ $k->name }}" class="kategori-img">
                  </div>
                  <div class="kategori-title">{{ $k->name }}</div>
                </a>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ============================================ -->
  <!-- BUKU POPULER                                  -->
  <!-- ============================================ -->
  <div class="container py-4">
    <h5>Buku yang populer</h5>
    <p>Buku yang sering dipinjam oleh anggota perpustakaan.</p>
    <div class="d-flex justify-content-center flex-wrap gap-3">
      @foreach($populer as $b)
        @php
          $gambarPath = $b->gambar && file_exists(storage_path('app/public/'.$b->gambar))
              ? asset('storage/'.$b->gambar)
              : asset('storage/no-cover.png');
        @endphp
        <div style="width: 160px;">
          <div class="card text-center">
            <img src="{{ $gambarPath }}"
                 alt="{{ $b->judul ?? 'Tanpa Judul' }}"
                 class="card-img-top"
                 style="height: 160px; object-fit: cover;">
            <div class="card-body p-2">
              <a href="{{ route('detail-buku', $b->id) }}" class="small d-block">
                {{ Str::limit($b->judul, 20) }}
              </a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <div class="text-end mt-3">
      <a href="{{ route('katalog') }}" class="btn btn-sm btn-outline-primary">Selengkapnya</a>
    </div>
  </div>

  <!-- ============================================ -->
  <!-- KOLEKSI BUKU TERBARU & ANGOTA RAJIN          -->
  <!-- ============================================ -->
  <div class="container py-4">
    <div class="row g-4">

      <!-- ================= BUKU TERBARU ================= -->
      <div class="col-md-6">
        <div class="card bg-secondary text-white">
          <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center">
              Koleksi Buku Terbaru
              <div>
                <button class="btn btn-sm btn-light me-1" onclick="moveOneBook('bukuBaruCarousel', -1)">❮</button>
                <button class="btn btn-sm btn-light" onclick="moveOneBook('bukuBaruCarousel', 1)">❯</button>
              </div>
            </h5>
            <p class="small text-white-50">Silahkan pilih buku terbaru yang kami miliki</p>

            <!-- ✅ Carousel Wrapper -->
            <div class="overflow-hidden position-relative" style="width: 100%;">
              <div id="bukuBaruCarousel" 
                   class="d-flex transition"
                   style="transition: transform 0.4s ease;">

                @foreach($terbaru as $b)
                  @php
                    $gambarPath = $b->gambar && file_exists(storage_path('app/public/'.$b->gambar))
                        ? asset('storage/'.$b->gambar)
                        : asset('storage/no-cover.png');
                  @endphp

                  <div class="text-center bg-white rounded p-2 flex-shrink-0"
                       style="width: 120px; margin-right: 10px; cursor:pointer;"
                       onclick="showDetailBuku('{{ route('buku.detail.json', $b->id) }}')">

                    <img src="{{ $gambarPath }}"
                         alt="{{ $b->judul ?? 'Tanpa Judul' }}"
                         class="img-fluid mb-2"
                         style="width: 100px; height: 140px; object-fit: cover;">

                    <div class="card-body p-1">
                      <div class="text-dark fw-bold" style="font-size: 13px; line-height: 1.2;">
                        {{ Str::limit($b->judul, 20) }}
                      </div>
                      <div class="text-dark" style="font-size: 13px; font-weight: 600; line-height: 1.2;">
                        {{ $b->total_peminjam_unique }} anggota
                      </div>
                    </div>
                  </div>
                @endforeach

              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ================= MODAL DETAIL BUKU (AJAX JSON) ================= -->
      <div class="modal fade" id="bukuDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
              <h5 class="modal-title">✨ Detail Buku</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="d-flex gap-3">
                <img id="modalBukuGambar" src="" style="width:120px;height:160px;object-fit:cover;" class="rounded">
                <div>
                  <h5 id="modalBukuJudul"></h5>
                  <p><strong>Pengarang:</strong> <span id="modalBukuPengarang"></span></p>
                  <p><strong>Penerbit:</strong> <span id="modalBukuPenerbit"></span></p>
                  <p><strong>Kategori:</strong> <span id="modalBukuKategori"></span></p>
                  <p><strong>Rak:</strong> <span id="modalBukuRak"></span></p>
                  <!-- ✅ Tambahkan info stok -->
                  <p><strong>Stok:</strong> <span id="modalBukuStok"></span></p>
                  <!-- ✅ Tambahkan total peminjam -->
                  <p><strong>Sudah dipinjam oleh:</strong> <span id="modalTotalPeminjam"></span> anggota</p>
                </div>
              </div>
              <hr>
              <p id="modalBukuDeskripsi"></p>
              <h6>Riwayat Peminjaman:</h6>
              <ul id="modalBukuPeminjam" class="list-group small"></ul>
            </div>
          </div>
        </div>
      </div>

      
      <!-- ================= ANGOTA RAJIN MEMINJAM ================= -->
      <div class="col-md-6">
        <div class="card bg-secondary text-white">
          <div class="card-body">
            <h5 class="card-title d-flex justify-content-between align-items-center">
              Anggota Rajin Meminjam
              <div>
                <form method="GET" action="{{ url('/') }}" class="d-inline">
                  <select name="periode" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                    <option value="3" {{ $periodeBulan == 3 ? 'selected' : '' }}>3 Bulan</option>
                    <option value="6" {{ $periodeBulan == 6 ? 'selected' : '' }}>6 Bulan</option>
                    <option value="12" {{ $periodeBulan == 12 ? 'selected' : '' }}>1 Tahun</option>
                  </select>
                </form>
                {{-- <button class="btn btn-sm btn-light me-1" onclick="scrollLeft('penikmatCarousel')">❮</button>
                <button class="btn btn-sm btn-light" onclick="scrollRight('penikmatCarousel')">❯</button> --}}
              </div>
            </h5>

            <p class="small text-white-50">
              Anggota terbaik dalam {{ $periodeBulan }} bulan terakhir
            </p>

            <div id="penikmatCarousel" 
                 class="d-flex overflow-auto px-2" 
                 style="scroll-behavior: smooth;">

              @forelse($penikmat as $a)
                <div class="penikmat-card me-3 text-center bg-white rounded shadow-sm p-2"
                     style="min-width: 100px; max-width: 110px; cursor:pointer; transition: transform 0.2s ease, box-shadow 0.2s ease;"
                     data-id="{{ $a->id }}"
                     data-nama="{{ $a->nama }}">
                  @if ($a->gambar)
                    <img src="{{ asset('storage/' . $a->gambar) }}"
                         class="rounded-circle mb-1"
                         alt="{{ $a->nama }}"
                         style="width: 60px; height: 60px; object-fit: cover;">
                  @else
                    <div style="font-size: 35px;">🧑</div>
                  @endif
                  <div class="text-dark" style="font-size: 13px; font-weight: 600; line-height: 1.2;">
                    {{ $a->nama }}
                  </div>
                  <div class="text-dark" style="font-size: 12px;">{{ $a->peminjaman_count }} Buku</div>
                </div>
              @empty
                <div class="text-white">Belum ada data anggota</div>
              @endforelse
            </div>
            
            <!-- =========== MODAL RIWAYAT PEMINJAMAN ANGGOTA =========== -->
            <div class="modal fade" id="modalRiwayat" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                  <div class="modal-header bg-secondary text-white">
                    <h5 class="modal-title">✨ Riwayat Peminjaman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div id="riwayatLoading" class="text-center py-3">Memuat data...</div>
                    <div id="riwayatContent" class="d-none">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>ISBN / ISSN</th>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Status</th>
                          </tr>
                        </thead>
                        <tbody id="riwayatTable"></tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- /#modalRiwayat -->

          </div><!-- /.card-body -->
        </div><!-- /.card -->
      </div><!-- /.col-md-6 (Anggota Rajin) -->

    </div><!-- /.row -->
  </div><!-- /.container py-4 -->

  <!-- ============================================ -->
  <!-- FOOTER INFORMASI SEKOLAH                     -->
  <!-- ============================================ -->
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


  <!-- ============================================ -->
  <!-- SCRIPT: SCROLL MANUAL (BUKU POPULER / DLL)   -->
  <!-- ============================================ -->
  <script>
    function scrollLeft(id) {
      const container = document.getElementById(id);
      container.scrollBy({ left: -200, behavior: 'smooth' });
    }

    function scrollRight(id) {
      const container = document.getElementById(id);
      container.scrollBy({ left: 200, behavior: 'smooth' });
    }
  </script>

  <!-- Font Awesome (untuk icon user & bell) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

  <!-- Bootstrap Bundle (sudah termasuk Popper.js) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- ============================================ -->
  <!-- SCRIPT: SCROLL (DUA VERSI - DARI KODE ASLI)   -->
  <!-- Versi kedua scrollLeft/scrollRight dipertahankan apa adanya -->
  <!-- ============================================ -->
  <script>
    function scrollLeft(id) {
      document.getElementById(id).scrollBy({ left: -150, behavior: 'smooth' });
    }

    function scrollRight(id) {
      document.getElementById(id).scrollBy({ left: 150, behavior: 'smooth' });
    }

    document.addEventListener('DOMContentLoaded', function () {
      const carousel = document.getElementById('penikmatCarousel');
      let scrollStep = 1; // kecepatan scroll (px)
      let maxScrollLeft = carousel.scrollWidth - carousel.clientWidth;
      let intervalId;

      function autoScroll() {
        carousel.scrollLeft += scrollStep;

        // Kalau sampai ujung kanan/kiri, ganti arah
        if (carousel.scrollLeft >= maxScrollLeft || carousel.scrollLeft <= 0) {
          scrollStep = -scrollStep;
        }
      }

      function startAutoScroll() {
        intervalId = setInterval(autoScroll, 30); // 30ms sekali
      }

      function stopAutoScroll() {
        clearInterval(intervalId);
      }

      // Jalankan auto-scroll pertama kali
      startAutoScroll();

      // Pause kalau mouse hover
      carousel.addEventListener('mouseenter', stopAutoScroll);
      // Lanjutkan lagi kalau mouse keluar
      carousel.addEventListener('mouseleave', startAutoScroll);
    });
  </script>

  <!-- ============================================ -->
  <!-- SCRIPT: FETCH RIWAYAT PEMINJAMAN ANGGOTA     -->
  <!-- ============================================ -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const periode = "{{ $periodeBulan }}"; // ambil periode dari dropdown
      const modal = new bootstrap.Modal(document.getElementById('modalRiwayat'));
      const riwayatLoading = document.getElementById('riwayatLoading');
      const riwayatContent = document.getElementById('riwayatContent');
      const riwayatTable = document.getElementById('riwayatTable');

      document.querySelectorAll('.penikmat-card').forEach(card => {
        card.addEventListener('click', function() {
          const anggotaId = this.dataset.id;
          const nama = this.dataset.nama;

          // Judul modal
          document.querySelector('#modalRiwayat .modal-title').textContent = `Riwayat Peminjaman - ${nama}`;

          // Reset modal
          riwayatLoading.classList.remove('d-none');
          riwayatContent.classList.add('d-none');
          riwayatTable.innerHTML = '';

          // Tampilkan modal
          modal.show();

          // Ambil data via AJAX
          fetch(`/anggota/${anggotaId}/riwayat?periode=${periode}`)
            .then(res => res.json())
            .then(data => {
              riwayatLoading.classList.add('d-none');
              riwayatContent.classList.remove('d-none');
              
              if (data.riwayat.length === 0) {
                riwayatTable.innerHTML = `<tr><td colspan="4" class="text-center">Tidak ada peminjaman pada periode ini</td></tr>`;
              } else {
                data.riwayat.forEach(item => {
                  riwayatTable.innerHTML += `
                    <tr>
                      <td>${item.isbn ?? '-'}</td>
                      <td>${item.judul}</td>
                      <td>${item.pengarang}</td>
                      <td>${item.tanggal}</td>
                      <td>${item.status}</td>
                    </tr>
                  `;
                });
              }
            })
            .catch(err => {
              riwayatLoading.textContent = "Gagal memuat data!";
            });
        });
      });
    });
  </script>

  <!-- ============================================ -->
  <!-- SCRIPT: SHOW DETAIL BUKU (FETCH JSON)        -->
  <!-- ============================================ -->
  <script>
    function showDetailBuku(url) {
      fetch(url)
        .then(res => {
          if (!res.ok) throw new Error("Gagal ambil data buku");
          return res.json();
        })
        .then(data => {
          // Isi modal
          document.getElementById('modalBukuGambar').src = data.gambar;
          document.getElementById('modalBukuJudul').textContent = data.judul;
          document.getElementById('modalBukuPengarang').textContent = data.pengarang;
          document.getElementById('modalBukuPenerbit').textContent = data.penerbit;
          document.getElementById('modalBukuKategori').textContent = data.kategori;
          document.getElementById('modalBukuRak').textContent = data.rak;
          document.getElementById('modalBukuDeskripsi').textContent = data.deskripsi;

          // ✅ Stok format
          document.getElementById('modalBukuStok').textContent = data.stok_format;
          // ✅ Total anggota yg sudah pernah pinjam
          document.getElementById('modalTotalPeminjam').textContent = data.total_peminjam_unique;

          // Riwayat peminjam
          let list = '';
          if (data.peminjam.length > 0) {
            data.peminjam.forEach(p => {
              list += `<li class="list-group-item d-flex justify-content-between">
                         <span>${p.nama}</span>
                         <small>${p.tanggal} (${p.status})</small>
                       </li>`;
            });
          } else {
            list = '<li class="list-group-item text-muted">Belum ada peminjam</li>';
          }
          document.getElementById('modalBukuPeminjam').innerHTML = list;

          // Tampilkan modal
          let modal = new bootstrap.Modal(document.getElementById('bukuDetailModal'));
          modal.show();
        })
        .catch(err => console.error("ERROR DETAIL BUKU:", err));
    }
  </script>

  <!-- ============================================ -->
  <!-- SCRIPT: CAROUSEL GESER 1 ITEM                -->
  <!-- ============================================ -->
  <script>
    let carouselPos = 0; // posisi awal

    function moveOneBook(id, step) {
      const container = document.getElementById(id);
      const items = container.children;
      const total = items.length;

      const itemWidth = items[0].offsetWidth + 10; // width + margin
      const maxPos = total - 1; // max index

      // Update posisi
      carouselPos += step;
      if (carouselPos < 0) carouselPos = 0;
      if (carouselPos > maxPos) carouselPos = maxPos;

      // Geser container 1 buku
      const offset = -(carouselPos * itemWidth);
      container.style.transform = `translateX(${offset}px)`;
    }
  </script>

</body>
</html>
