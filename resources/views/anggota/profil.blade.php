<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profil Anggota - Perpustakaan Digital</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
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

    /* Sidebar */
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

    /* Konten */
    main {
      flex: 1;
      padding: 30px;
    }
    .breadcrumb-custom {
      background: #e9ecef;
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 14px;
      margin-bottom: 20px;
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

    .card-custom {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .profile-photo {
      max-width: 200px;
      border-radius: 10px;
      margin-bottom: 15px;
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
    footer a {
      text-decoration: none;
      color: #555;
    }
    footer a:hover {
      color: #0d6efd;
    }
  </style>
</head>
<body>

  {{-- NAVBAR --}}
  @include('layouts.partials.navbar')

  <div class="main-container">

    {{-- SIDEBAR --}}
    @include('layouts.partials.sidebar')

    <!-- KONTEN PROFIL -->
    <main>

      <!-- Breadcrumb + Jam -->
      <div class="breadcrumb-custom">
        <div>
          <i class="fas fa-home me-1"></i> Beranda / Anggota / Profil
        </div>
        <div class="jam-tanggal">
          <div><i class="fas fa-clock"></i> <span id="jam-digital"></span></div>
          <div id="tanggal-digital"></div>
        </div>
      </div>

      <!-- Card Profil -->
      <div class="card-custom">
        <h2 class="mb-4">Profil Anggota</h2>

        <form action="{{ route('anggota.profil.update') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="row">
            <!-- Foto Profil + Nama + NISN -->
            <div class="col-md-3 text-center">
              <img src="{{ $anggota->gambar ? asset('storage/' . $anggota->gambar) : asset('storage/no-cover.png') }}" 
                   class="profile-photo" alt="Foto Profil">

              <!-- Nama & Info Singkat -->
              <h5 class="mt-3 mb-1 fw-bold">{{ $anggota->nama }}</h5>
              <p class="mb-1 text-secondary">{{ $anggota->id }}</p>
              <span class="badge bg-primary">{{ ucfirst($anggota->jabatan) }}</span>
            </div>

            <!-- Detail Profil -->
            <div class="col-md-9">
              <table class="table table-sm table-striped align-middle">
                <tbody>
                  <tr>
                    <th width="200">Jurusan</th>
                    <td>{{ $anggota->jurusan->nama ?? '-' }}</td>
                  </tr>
                  <tr>
                    <th>Kelas</th>
                    <td>{{ $anggota->kelas ?? '-' }}</td>
                  </tr>
                  <tr>
                    <th>Jenis Kelamin</th>
                    <td>{{ $anggota->jenkel == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                  </tr>
                  <tr>
                    <th>Alamat</th>
                    <td>{{ $anggota->alamat ?? '-' }}</td>
                  </tr>
                  <tr>
                    <th>No. Telepon</th>
                    <td>{{ $anggota->no_telp ?? '-' }}</td>
                  </tr>
                  <tr>
                    <th>Email</th>
                    <td>{{ $anggota->email ?? '-' }}</td>
                  </tr>
                  <tr>
                    <th>Status</th>
                     <td>
                      @if($anggota->status === 'aktif')
                        <span class="badge bg-success px-3 py-2 rounded">Aktif</span>
                      @else
                        <span class="badge bg-danger px-3 py-2 rounded">{{ ucfirst($anggota->status) }}</span>
                      @endif
                    </td>
                  </tr>
                </tbody>
              </table>

              <!-- Update Password -->
              <div class="row mt-4">
                <div class="col-md-6">
                  <label>Password Baru</label>
                  <input type="password" name="password" class="form-control" placeholder="Password baru (opsional)">
                </div>
                <div class="col-md-6">
                  <label>Konfirmasi Password</label>
                  <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password">
                </div>
              </div>
            </div>
          </div>

          <!-- Tombol Aksi -->
          <div class="text-end mt-4">
            <button type="reset" class="btn btn-secondary">Reset</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </main>
  </div>

  {{-- FOOTER --}}
  @include('layouts.partials.footer')

  <!-- Bootstrap Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Font Awesome -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

  <!-- Jam Digital -->
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
