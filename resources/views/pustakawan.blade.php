<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pustakawan | Perpustakaan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/logo_perpus.png') }}" type="image/x-icon">
</head>

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

    .card-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    footer a:hover {
    color: #0d6efd !important; /* efek hover biru elegan */
  }
</style>

<body class="bg-light">

    {{-- NAVBAR --}}
    @include('layouts.partials.navbar')

<!-- Judul dan Breadcrumb -->
<div class="bg-gradient py-4" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
    <div class="container text-center">
        <h2 class="fw-bold mb-1 text-dark">Daftar Staf Perpustakaan</h2>
        <p class="text-muted mb-0">Berikut adalah staf aktif di Perpustakaan SMA Negeri 1 Bengkulu Selatan</p>
    </div>
</div>

<!-- Daftar Pustakawan -->
<div class="container py-5">
    <div class="row g-4 justify-content-center">
        @forelse($pustakawan as $user)
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center p-4 card-hover">
                    
                    {{-- Foto Profil atau Icon Default --}}
                    @if ($user->gambar)
                        <img src="{{ asset('storage/' . $user->gambar) }}" 
                             class="rounded-circle mx-auto mb-3 border" 
                             style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center bg-light border shadow-sm"
                             style="width: 120px; height: 120px; font-size: 50px; color: #6c757d;">
                             
                            {{-- Tampilkan icon sesuai role --}}
                            @if($user->hasRole('admin'))
                                <i class="fas fa-user-shield"></i>
                            @elseif($user->hasRole('kepala_sekolah'))
                                <i class="fa fa-user"></i>
                            @elseif($user->hasRole('pustakawan'))
                                <i class="fa fa-user-secret"></i>
                            @else
                                <i class="fas fa-user"></i>
                            @endif
                        </div>
                    @endif

                    {{-- Nama & Role --}}
                    <h5 class="fw-semibold mb-1 text-dark">{{ $user->name }}</h5>

                    {{-- Badge Role --}}
                    @if($user->hasRole('admin'))
                        <span class="badge bg-danger mb-2 px-3 py-1 rounded-pill">Admin</span>
                    @elseif($user->hasRole('kepala_sekolah'))
                        <span class="badge bg-success mb-2 px-3 py-1 rounded-pill">Kepala Sekolah</span>
                    @elseif($user->hasRole('pustakawan'))
                        <span class="badge bg-primary mb-2 px-3 py-1 rounded-pill">Pustakawan</span>
                    @endif

                    {{-- Email --}}
                    <p class="text-muted small mb-0">{{ $user->email }}</p>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p class="text-muted">Belum ada staf perpustakaan yang tersedia.</p>
            </div>
        @endforelse
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