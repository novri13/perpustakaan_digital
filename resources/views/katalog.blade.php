<!DOCTYPE html>
<html lang="id">
<head>
  <!-- ===================================================================
       KATALOG BUKU – PERPUSTAKAAN DIGITAL
       =================================================================== -->

  <meta charset="UTF-8">
  <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_perpus.png') }}">
  <title>Katalog Buku - Perpustakaan Digital</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  @php
    use Illuminate\Support\Str;
  @endphp

  <!-- ===================================================================
       STYLE INTERNAL
       (Semua properti dari kode asli; urutan & indentasi dirapikan.)
       =================================================================== -->
  <style>
    body {
      background-color: #f1f1f1;
    }

    .search-box {
      background: #ddd;
      padding: 20px;
    }

    .sidebar {
      background: #fff;
      border-radius: 10px;
      padding: 20px;
    }

    .book-card {
      background: #fff;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 20px;
      display: flex;
      gap: 15px;
    }

    .book-cover {
      width: 120px;
      height: 160px;
      background: #ccc;
      flex-shrink: 0;
    }

    .book-info {
      flex-grow: 1;
    }

    .book-info h5 {
      margin: 0;
    }

    .label-penerbit {
      display: inline-block;
      background: #e7e7e7;
      border-radius: 4px;
      padding: 2px 6px;
      font-size: 0.85rem;
      margin-bottom: 8px;
    }

    .badge-ketersediaan {
      font-size: 1.2rem;
      background: #0d6efd;
      color: white;
      padding: 8px;
      text-align: center;
      border-radius: 5px;
      width: 80px;
    }

    .pagination {
      justify-content: center;
    }

    .footer {
      background: #fff;
      padding: 20px;
      border-top: 1px solid #ccc;
      margin-top: 30px;
    }
  </style>
</head>

<body>

  <!-- ===================================================================
       NAVBAR UTAMA (shared dengan beranda)
       =================================================================== -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-2">
    <div class="container-fluid">
      <!-- Logo + Identitas Sekolah -->
      <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
        <img src="{{ asset('images/logo_perpus.png') }}" alt="Logo" width="50" class="me-2">
        <div class="lh-sm">
          <span class="fw-bold">Perpustakaan Digital</span><br>
          <small>SMA Negeri 1 Bengkulu Selatan</small>
        </div>
      </a>

      <!-- Menu Right -->
      <div class="ms-auto d-flex align-items-center gap-3">
        <a class="nav-link text-primary" href="{{ route('home') }}">Beranda</a>
        <a class="nav-link" href="{{ route('denah') }}">Denah Pustaka</a>
        <a class="nav-link" href="{{ route('pustakawan') }}">Pustakawan</a>

        @guest
          <!-- Kalau belum login -->
          <a class="btn btn-primary" href="{{ route('anggota.login.form') }}">Login</a>
        @else
          @php
            $user = auth()->user();
            $unreadCount = $user->unreadNotifications->count();
            $notifications = $user->notifications()->latest()->take(5)->get();
          @endphp

          <!-- =========================
               Notifikasi Dropdown
               ========================= -->
          <div class="dropdown me-3">
            <a href="#" id="notifDropdown" class="text-dark position-relative" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-bell fa-lg"></i>
              @if($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  {{ $unreadCount }}
                </span>
              @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notifDropdown" style="min-width: 300px; max-height: 350px; overflow-y: auto;">
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

          <!-- =========================
               Profil Dropdown
               ========================= -->
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


  <!-- ===================================================================
       BREADCRUMB + SEARCH BAR GLOBAL KATALOG
       =================================================================== -->
  <div class="search-box text-center">
    <div class="container">
      <div class="mb-2 text-start text-secondary">Beranda / Katalog Buku</div>
      <form method="GET" action="{{ route('katalog') }}" class="w-100">
        <input type="text" name="q" class="form-control" placeholder="Masukkan Kata Kunci Untuk Mencari Buku">
      </form>
    </div>
  </div>


  <!-- ===================================================================
       MAIN CONTENT WRAPPER
       Berisi: Sidebar Filter + Daftar Buku
       =================================================================== -->
  <div class="container py-4">
    <!-- Form GET agar semua filter (tahun, rak, bahasa, q) submit bersama -->
    <form method="GET" action="{{ route('katalog') }}">
      <div class="row">

        <!-- ===============================================================
             SIDEBAR FILTER (kolom kiri)
             =============================================================== -->
        <div class="col-md-3">
          <div class="sidebar">
            <h6>Pencarian Berdasarkan</h6>

            <div class="flex justify-between items-center mb-4">
              <h4 class="text-lg font-bold">Filter Buku</h4>

              {{-- ✅ Tombol Reset Semua Filter --}}
              @if(request()->has('tahun') || request()->has('rak') || request()->has('bahasa') || request()->has('q'))
                <a href="{{ route('katalog') }}" class="inline-block mb-2 px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                  🔄 Reset Semua Filter
                </a>
              @endif
            </div>

            <!-- =======================
                 FILTER: TAHUN TERBIT
                 ======================= -->
            <div class="mb-4">
              <strong class="block mb-1">Tahun Terbit</strong>

              {{-- Tombol Reset Tahun --}}
              @if(request('tahun'))
                <a href="{{ request()->fullUrlWithQuery(['tahun' => null]) }}" class="inline-block mb-2 px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                  ⟳ Reset Tahun
                </a>
              @endif

              {{-- List Tahun --}}
              @php $tahunChunk = array_slice($tahunList, 0, 3); @endphp
              @foreach ($tahunChunk as $tahun)
                <div>
                  <input type="radio" name="tahun" value="{{ $tahun }}" onchange="this.form.submit()" {{ request('tahun') == $tahun ? 'checked' : '' }}>
                  {{ $tahun }}
                </div>
              @endforeach

              <div id="tahun-more" style="display: none;">
                @foreach (array_slice($tahunList, 3) as $tahun)
                  <div>
                    <input type="radio" name="tahun" value="{{ $tahun }}" onchange="this.form.submit()" {{ request('tahun') == $tahun ? 'checked' : '' }}>
                    {{ $tahun }}
                  </div>
                @endforeach
              </div>

              @if(count($tahunList) > 3)
                <a href="#" class="toggle-filter text-blue-500 text-sm" data-target="#tahun-more">Lihat Selengkapnya</a>
              @endif
            </div>
            <hr>

            <!-- =======================
                 FILTER: LOKASI RAK
                 ======================= -->
            <div class="mb-4">
              <strong class="block mb-1">Lokasi Rak</strong>

              {{-- Tombol Reset Rak --}}
              @if(request('rak'))
                <a href="{{ request()->fullUrlWithQuery(['rak' => null]) }}" class="inline-block mb-2 px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                  ⟳ Reset Rak
                </a>
              @endif

              {{-- List Rak --}}
              @php $rakChunk = array_slice($rakList, 0, 3, true); @endphp
              @foreach ($rakChunk as $id => $nama)
                <div>
                  <input type="radio" name="rak" value="{{ $id }}" onchange="this.form.submit()" {{ request('rak') == $id ? 'checked' : '' }}>
                  {{ $nama }}
                </div>
              @endforeach

              <div id="rak-more" style="display: none;">
                @foreach (array_slice($rakList, 3, null, true) as $id => $nama)
                  <div>
                    <input type="radio" name="rak" value="{{ $id }}" onchange="this.form.submit()" {{ request('rak') == $id ? 'checked' : '' }}>
                    {{ $nama }}
                  </div>
                @endforeach
              </div>

              @if(count($rakList) > 3)
                <a href="#" class="toggle-filter text-blue-500 text-sm" data-target="#rak-more">Lihat Selengkapnya</a>
              @endif
            </div>
            <hr>

            <!-- =======================
                 FILTER: BAHASA
                 ======================= -->
            <div class="mb-4">
              <strong class="block mb-1">Bahasa</strong>

              {{-- Tombol Reset Bahasa --}}
              @if(request('bahasa'))
                <a href="{{ request()->fullUrlWithQuery(['bahasa' => null]) }}" class="inline-block mb-2 px-2 py-1 text-xs bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                  ⟳ Reset Bahasa
                </a>
              @endif

              {{-- List Bahasa --}}
              @php $bahasaChunk = array_slice($bahasaList, 0, 3); @endphp
              @foreach ($bahasaChunk as $bahasa)
                <div>
                  <input type="radio" name="bahasa" value="{{ $bahasa }}" onchange="this.form.submit()" {{ request('bahasa') == $bahasa ? 'checked' : '' }}>
                  {{ ucfirst($bahasa) }}
                </div>
              @endforeach

              <div id="bahasa-more" style="display: none;">
                @foreach (array_slice($bahasaList, 3) as $bahasa)
                  <div>
                    <input type="radio" name="bahasa" value="{{ $bahasa }}" onchange="this.form.submit()" {{ request('bahasa') == $bahasa ? 'checked' : '' }}>
                    {{ ucfirst($bahasa) }}
                  </div>
                @endforeach
              </div>

              @if(count($bahasaList) > 3)
                <a href="#" class="toggle-filter text-blue-500 text-sm" data-target="#bahasa-more">Lihat Selengkapnya</a>
              @endif
            </div>
            <hr>

            <!-- Tombol submit filter manual (opsional; kebanyakan filter auto-submit on change) -->
            <button class="btn btn-primary btn-sm mt-3" type="submit">Filter</button>
          </div> <!-- /.sidebar -->
        </div> <!-- /.col-md-3 -->


        <!-- ===============================================================
             KOLOM KANAN – LIST BUKU + ALERT FILTER AKTIF + PAGINATION
             =============================================================== -->
        <div class="col-md-9">

          {{-- ✅ Filter Kategori --}}
          @if($selectedKategori)
            <div class="alert alert-info d-flex justify-content-between align-items-center">
              <div>
                Menampilkan buku pada kategori:
                <strong>{{ $selectedKategori->name }}</strong>
              </div>
              <a href="{{ route('katalog') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
          @endif

          {{-- ✅ Filter Tahun Terbit --}}
          @if($selectedTahunTerbit)
            <div class="alert alert-info d-flex justify-content-between align-items-center">
              <div>
                Menampilkan buku berdasarkan tahun terbit:
                @if(is_array($selectedTahunTerbit))
                  <strong>{{ implode(', ', $selectedTahunTerbit) }}</strong>
                @else
                  <strong>{{ $selectedTahunTerbit }}</strong>
                @endif
              </div>
              <a href="{{ route('katalog') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
          @endif

          {{-- ✅ Filter Lokasi Rak --}}
          @if($selectedRak)
            <div class="alert alert-info d-flex justify-content-between align-items-center">
              <div>
                Menampilkan buku pada rak:
                @if($selectedRak instanceof \Illuminate\Support\Collection)
                  {{-- Jika multi rak dipilih --}}
                  <strong>{{ $selectedRak->pluck('name')->join(', ') }}</strong>
                @else
                  {{-- Jika hanya satu rak --}}
                  <strong>{{ $selectedRak->name }}</strong>
                @endif
              </div>
              <a href="{{ route('katalog') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
          @endif

          {{-- ✅ Filter Bahasa --}}
          @if($selectedBahasa)
            <div class="alert alert-info d-flex justify-content-between align-items-center">
              <div>
                Menampilkan buku berdasarkan bahasa:
                @if(is_array($selectedBahasa))
                  <strong>{{ implode(', ', $selectedBahasa) }}</strong>
                @else
                  <strong>{{ $selectedBahasa }}</strong>
                @endif
              </div>
              <a href="{{ route('katalog') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
          @endif

          <!-- Ringkasan jumlah hasil -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>Ditemukan <strong>{{ $bukus->total() }}</strong> buku dari pencarian</div>
          </div>

          <!-- =============================================================
               LOOP: ITEM BUKU
               ============================================================= -->
          @forelse ($bukus as $buku)
            @php
                // Kalau ada nama file gambar di DB, pakai itu, kalau tidak ada fallback
                $gambarBuku = $buku->gambar 
                    ? asset('storage/' . $buku->gambar) 
                    : asset('storage/no-cover.png');
            @endphp

              <div class="book-card">
                <!-- Sampul -->
                <img src="{{ $gambarBuku }}" alt="Sampul" class="book-cover">

              <!-- Info Buku -->
              <div class="book-info">
                <h5>{{ $buku->judul }}</h5>
                <span class="label-penerbit">{{ $buku->penerbit->name ?? '-' }}</span>
                <p class="small mb-1">{{ Str::limit(strip_tags($buku->deskripsi), 100) }}</p>
                <p class="small mb-1">Edisi & Tahun: {{ $buku->edisi ?? '-' }} / {{ $buku->tahun_terbit ? \Carbon\Carbon::parse($buku->tahun_terbit)->format('Y') : '-' }}</p>
                <p class="small mb-1">ISBN/ISSN: {{ $buku->id ?? '-' }}</p>
                <p class="small mb-1">Kategori: {{ $buku->kategori->name ?? '-' }}</p>
                <p class="small mb-1">Bahasa: {{ $buku->bahasa ?? '-' }}</p>
              </div>

              <!-- Stok + Detail -->
              <div class="text-center">
                <div class="text-center mb-2">
                  <div class="fw-bold">Stok Buku</div>
                  <div class="badge bg-success fs-5">{{ $buku->stok }}</div>
                </div>
                <a href="{{ route('detail-buku', $buku->id) }}" class="btn btn-outline-primary btn-sm mt-2">Tampilkan Detail</a>
              </div>
            </div>
          @empty
            <p>Tidak ada buku ditemukan.</p>
          @endforelse

          <!-- Pagination -->
          <nav>
            {{ $bukus->links() }}
          </nav>
        </div> <!-- /.col-md-9 -->
      </div> <!-- /.row -->
    </form>
  </div> <!-- /.container -->


  <!-- ===================================================================
       FOOTER INFORMASI SEKOLAH & KONTAK
       =================================================================== -->
  <div class="footer">
    <div class="row">
      <!-- Logo & Alamat -->
      <div class="col-md-4 text-center">
        <img src="{{ asset('images/logo_perpus.png') }}" alt="Logo SMAN 1" style="height: 60px; margin-bottom: 10px;">
        <h6>SMA NEGERI 1 BENGKULU SELATAN</h6>
        <p>Alamat: Jln. Pangeran Duayu Manna</p>
      </div>

      <!-- Tentang Kami -->
      <div class="col-md-4">
        <h6>Tentang Kami</h6>
        <p>Perpustakaan digital SMANSA menyajikan beragam koleksi buku, memenuhi kebutuhan anggota untuk sumber belajar dan referensi.</p>
      </div>

      <!-- Kontak -->
      <div class="col-md-4">
        <h6>Kontak</h6>
        <p>Telp. (0739)21296 / Fax.(0739)2268<br>
          E-Mail: smanegeri1bs@gmail.com<br>
          Website: https://sman1bs.sch.id
        </p>
      </div>
    </div>
    <hr>
    <div class="text-center">
      SMA Negeri 1 Bengkulu Selatan - © SiPertal 2025 &nbsp; | &nbsp; Version 1.0
    </div>
  </div>


  <!-- ===================================================================
       SCRIPT EKSTERNAL
       =================================================================== -->
  <!-- Font Awesome (untuk icon user & bell) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
  <!-- Bootstrap Bundle (Popper.js sudah termasuk) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


  <!-- ===================================================================
       SCRIPT: Toggle Show/Hide (versi .toggle-link)
       Catatan: markup saat ini tidak menggunakan .toggle-link secara eksplisit,
       tapi script asli dipertahankan (tidak dihapus) untuk kompatibilitas.
       =================================================================== -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // === Toggle Show/Hide ===
      document.querySelectorAll(".toggle-link").forEach(link => {
        link.addEventListener("click", function() {
          let targetId = this.dataset.target;
          let container = document.getElementById(targetId);
          let hiddenItems = container.querySelectorAll("div[style*='display: none']");

          if (hiddenItems.length > 0) {
            // Show all items
            container.querySelectorAll("div").forEach(el => el.style.display = "block");
            this.textContent = "Sembunyikan";
          } else {
            // Hide items after 3
            container.querySelectorAll("div").forEach((el, idx) => {
              el.style.display = (idx >= 3) ? "none" : "block";
            });
            this.textContent = "Lihat Selengkapnya";
          }
        });
      });
    });
  </script>


  <!-- ===================================================================
       SCRIPT: Toggle Filter (Tahun / Rak / Bahasa)
       Klik link "Lihat Selengkapnya" untuk expand/collapse daftar.
       =================================================================== -->
  <script>
    document.querySelectorAll('.toggle-filter').forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        let target = document.querySelector(this.dataset.target);
        if (target.style.display === 'none') {
          target.style.display = 'block';
          this.textContent = 'Sembunyikan';
        } else {
          target.style.display = 'none';
          this.textContent = 'Lihat Selengkapnya';
        }
      });
    });
  </script>

</body>
</html>
