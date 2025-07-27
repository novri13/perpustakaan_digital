<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Anggota - Perpustakaan Digital</title>

  <!-- Bootstrap CSS & Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    html, body {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
      background-color: #f8f9fa;
    }

    .main-container {
      flex: 1;
      display: flex;
    }

    .sidebar {
      width: 240px;
      background: linear-gradient(180deg, #0d6efd, #084298);
      color: #fff;
      padding: 20px;
      min-height: 100vh;
    }

    .sidebar h4 {
      font-size: 18px;
      margin-bottom: 30px;
      text-align: center;
    }

    .sidebar .nav-link {
      color: #cfd8dc;
      padding: 12px 15px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: all 0.3s ease;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
      font-weight: bold;
    }

    .sidebar .nav-link i {
      width: 20px;
      text-align: center;
    }

    main {
      flex: 1;
      padding: 30px;
    }

    .card-custom {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .breadcrumb-custom {
      background: #e9ecef;
      padding: 12px 20px;
      border-radius: 8px;
      font-size: 14px;
      margin-bottom: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    footer.footer-custom {
      background: #fff;
      border-top: 1px solid #ddd;
      padding: 15px 30px;
      font-size: 14px;
      color: #666;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    @media (max-width: 768px) {
      .main-container {
        flex-direction: column;
      }
      .sidebar {
        width: 100%;
        min-height: auto;
      }
    }
  </style>
</head>
<body>

  {{-- NAVBAR --}}
  @include('layouts.partials.navbar')

  <div class="main-container">

    {{-- SIDEBAR --}}
    @include('layouts.partials.sidebar')

    {{-- MAIN CONTENT --}}
    <main>
      <!-- Breadcrumb -->
      <div class="breadcrumb-custom">
        <div><i class="fas fa-home me-1"></i> Dashboard / Anggota</div>
        <div class="d-flex flex-column align-items-end">
          <div class="d-flex align-items-center">
            <i class="fas fa-clock me-1 text-secondary"></i>
            <span id="live-time" class="fw-semibold"></span>
          </div>
          <small class="text-muted">
            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
          </small>
        </div>
      </div>

      <!-- Kartu Sambutan -->
      <div class="card-custom mb-4 border-start border-4 border-primary ps-4 pt-3">
        <h2 class="mb-3 fw-bold">Dashboard Anggota</h2>
        <p class="fs-5">Selamat Datang, <strong>{{ auth()->user()->name }}</strong></p>

        <!-- Kartu Statistik -->
      <div class="row">
        <div class="col-md-4 mb-3">
          <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
              <i class="fa-solid fa-calendar-days fa-2x mb-2 text-warning"></i>
              <h6 class="text-muted">Booking Buku</h6>
              <h3 class="fw-bold text-dark">{{ $totalBooking }}</h3>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-3">
          <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
              <i class="fa-solid fa-book-open-reader fa-2x mb-2 text-primary"></i>
              <h6 class="text-muted">Dipinjam</h6>
              <h3 class="fw-bold text-dark">{{ $totalDipinjam }}</h3>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-3">
          <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
              <i class="fa-solid fa-rotate-left fa-2x mb-2 text-success"></i>
              <h6 class="text-muted">Dikembalikan</h6>
              <h3 class="fw-bold text-dark">{{ $totalDikembalikan }}</h3>
            </div>
          </div>
        </div>
      </div>
      
        <div class="mt-3">
          <p class="fw-semibold text-dark">Catatan Penting:</p>
          <ul class="list-group list-group-flush">
            <li class="list-group-item">📚 Anda dapat melakukan peminjaman buku dengan tempo yang sudah ditentukan.</li>
            <li class="list-group-item">⏳ Perpanjangan peminjaman hanya bisa 1x (maksimal total 14 hari).</li>
            <li class="list-group-item">⚠️ Pengembalian harus tepat waktu untuk menghindari denda.</li>
            <li class="list-group-item">📖 Harap jaga buku dengan baik selama masa peminjaman.</li>
          </ul>
        </div>
      </div>

    </main>
  </div>

  {{-- FOOTER --}}
  @include('layouts.partials.footer')

  <script>
    function updateTime() {
      let now = new Date();
      let jam = now.getHours().toString().padStart(2, '0');
      let menit = now.getMinutes().toString().padStart(2, '0');
      let detik = now.getSeconds().toString().padStart(2, '0');
      document.getElementById("live-time").innerText = `${jam}:${menit}:${detik}`;
    }
    setInterval(updateTime, 1000);
    updateTime();
  </script>

</body>
</html>
