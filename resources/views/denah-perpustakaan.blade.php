<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Denah Perpustakaan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
     /* Efek hover underline elegan */
  .hover-underline {
    position: relative;
    text-decoration: none;
    transition: color 0.2s ease;
  }
  .hover-underline::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -3px;
    width: 0;
    height: 2px;
    background: #0d6efd;
    transition: width 0.3s ease;
  }
  .hover-underline:hover::after {
    width: 100%;
  }

    footer a:hover {
    color: #0d6efd !important; /* efek hover biru elegan */
  }
    
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

    {{-- NAVBAR --}}
    @include('layouts.partials.navbar')


<!-- Header -->
<div class="container py-4">
  <h3 class="text-center mb-4">Denah Perpustakaan</h3>

  <div class="bg-white border rounded p-4 shadow-sm">
      <!-- Baris 1 -->
      <div class="row g-2">
        <div class="col-3 denah-box rak">Gudang</div>
        <div class="col-1"></div>
        <div class="col-2 denah-box rak">RAK 1</div>
        <div class="col-2 denah-box rak">RAK 2</div>
        <div class="col-10"></div>
      </div>

      <!-- Baris 2 -->
      <div class="row g-2">
        <div class="col-3 denah-box rak">Meja Belajar <br>dan Baca</div>
        <div class="col-1"></div>
        <div class="col-2 denah-box rak">RAK 3</div>
        <div class="col-2 denah-box rak">RAK 4</div>
      </div>

    <!-- Baris 3 -->
    <div class="row g-2 mt-2">
      <div class="col-3"></div>
      <div class="col-5 denah-box baca-kelompok">Meja Belajar<br>dan Baca</div>
      <div class="col-4">
        <div class="row g-2">
          <div class="col-6 denah-box rak">RAK 5</div>
          <div class="col-6 denah-box rak">RAK 6</div>
          <div class="col-6 denah-box rak">RAK 7</div>
          <div class="col-6 denah-box rak">RAK 8</div>
        </div>
      </div>
    </div>

    <!-- Baris 4 -->
    <div class="row g-2 mt-2">
      <div class="col-1 denah-box rak">RAK 13</div>
      <div class="col-2 denah-box rak">RAK 12</div>
    </div>

    <!-- Baris 5 -->
    <div class="row g-2 mt-2">
      <div class="col-1 denah-box rak">RAK 14</div>
      <div class="col-1 denah-box rak">RAK 15</div>
      <div class="col-1 denah-box rak">RAK 16</div>
      <div class="col-2"></div>
      <div class="col-3 denah-box kepala">Meja Pustakawan</div>
      <div class="col-2"></div>
      <div class="col-2 denah-box rak">RAK 9</div>
    </div>

   <!-- Baris 6 -->
    <div class="row g-2 mt-2">
      <div class="col-5"></div>
      <div class="col-2 denah-box kepala">Meja Pustakawan</div>
      <div class="col-1"></div>
      <div class="col-2 denah-box rak">RAK 11</div>
      <div class="col-2 denah-box rak">RAK 10</div>
    </div>

  <!-- Baris 7 -->
    <div class="row g-2 mt-2">
      <div class="col-4"></div>
      <div class="col-1 denah-box pintu">Pintu</div>
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

    {{-- FOOTER --}}
    @include('layouts.partials.footer')

<!-- Font Awesome (untuk icon user & bell) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
<!-- Bootstrap Bundle (sudah termasuk Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


</body>
</html>
