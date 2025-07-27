<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Transaksi Anggota</title>

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { background: #f8f9fa; }

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
      letter-spacing: 1px;
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

    .sidebar .nav-link:hover {
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
    }

    .sidebar .nav-link.active {
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
      font-weight: bold;
    }

    .sidebar .nav-link i {
      width: 20px;
      text-align: center;
    }

    /* Main Content */
    .main-content {
      flex: 1;
      padding: 30px 50px;
    }

    /* Breadcrumb */
    .breadcrumb-custom {
      background: #e9ecef;
      padding: 12px 20px;
      border-radius: 8px;
      margin-bottom: 25px;

      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .jam-tanggal {
      text-align: right;
      font-size: 13px;
      color: #555;
      line-height: 1.2;
    }
    .jam-tanggal i {
      color: #0d6efd;
      margin-right: 5px;
    }

    /* Card Konten */
    .content-card {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .content-card h4 {
      font-weight: 600;
    }

    /* Table */
    table th, table td {
      vertical-align: middle;
    }
  </style>
</head>
<body>

  {{-- NAVBAR --}}
  @include('layouts.partials.navbar')

  <div class="d-flex">
    @include('layouts.partials.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="main-content">
      {{-- Breadcrumb --}}
      <div class="breadcrumb-custom">
        <div>
          <a href="{{ route('anggota.dashboard') }}" class="text-decoration-none text-secondary">
            <i class="fas fa-home"></i> Beranda
          </a> / <span class="text-dark">Riwayat Peminjaman</span>
        </div>
        <!-- ✅ JAM DIGITAL -->
        <div class="jam-tanggal">
          <div><i class="fas fa-clock"></i> <span id="jam-digital"></span></div>
          <div id="tanggal-digital"></div>
        </div>
      </div>

      {{-- Card Konten --}}
      <div class="content-card">
        <h4 class="mb-4"><i class="fas fa-book-open me-2"></i> Riwayat Peminjaman</h4>

        {{-- Tabs --}}
        <ul class="nav nav-tabs" id="riwayatTabs" role="tablist">
          <li class="nav-item">
            <button class="nav-link active" id="peminjaman-tab" data-bs-toggle="tab" data-bs-target="#tab-peminjaman" type="button">
              📚 Sedang Dipinjam
            </button>
          </li>
          <li class="nav-item">
            <button class="nav-link" id="pengembalian-tab" data-bs-toggle="tab" data-bs-target="#tab-pengembalian" type="button">
              ✅ Sudah Dikembalikan
            </button>
          </li>
        </ul>

        {{-- Tab Content --}}
        <div class="tab-content mt-3">
          {{-- Sedang Dipinjam --}}
          <div class="tab-pane fade show active" id="tab-peminjaman">
            <table class="table table-bordered table-striped align-middle">
              <thead class="table-light">
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
                    <td>{{ $pinjam->denda ? 'Rp '.number_format($pinjam->denda->jumlah ?? 0,0,',','.') : '-' }}</td>
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

          {{-- Sudah Dikembalikan --}}
          <div class="tab-pane fade" id="tab-pengembalian">
            <table class="table table-bordered table-striped align-middle">
              <thead class="table-light">
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
                    <td>{{ $pinjam->denda ? 'Rp '.number_format($pinjam->denda->jumlah ?? 0,0,',','.') : '-' }}</td>
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
    </div>
  </div>

  {{-- FOOTER --}}
  @include('layouts.partials.footer')

  <!-- Font Awesome -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
  <!-- Bootstrap Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- ✅ JAM DIGITAL -->
  <script>
    function updateClock() {
      const now = new Date();
      const hariNama = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
      const bulanNama = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

      let hari = hariNama[now.getDay()];
      let tanggal = now.getDate();
      let bulan = bulanNama[now.getMonth()];
      let tahun = now.getFullYear();

      let jam = now.getHours().toString().padStart(2, '0');
      let menit = now.getMinutes().toString().padStart(2, '0');
      let detik = now.getSeconds().toString().padStart(2, '0');

      document.getElementById('jam-digital').textContent = `${jam}:${menit}:${detik}`;
      document.getElementById('tanggal-digital').textContent = `${hari}, ${tanggal} ${bulan} ${tahun}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
  </script>

</body>
</html>
