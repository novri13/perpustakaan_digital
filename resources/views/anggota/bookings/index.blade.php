{{-- <x-layout>
    <h1 class="text-2xl font-bold mb-4">Booking Saya</h1>

    <table class="table-auto w-full border">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-2 border">Judul Buku</th>
                <th class="p-2 border">Tanggal Booking</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td class="p-2 border">{{ $booking->buku->judul }}</td>
                    <td class="p-2 border">{{ $booking->created_at->format('d M Y H:i') }}</td>
                    <td class="p-2 border">
                        @if($booking->status === 'booking')
                            <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded">Booking</span>
                        @elseif($booking->status === 'dipinjam')
                            <span class="bg-green-200 text-green-800 px-2 py-1 rounded">Disetujui</span>
                        @else
                            <span class="bg-red-200 text-red-800 px-2 py-1 rounded">Ditolak</span>
                        @endif
                    </td>
                    <td class="p-2 border">
                        {{ $booking->catatan ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center p-4">Belum ada booking</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layout> --}}

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Booking Saya</title>

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

    /* Breadcrumb dengan jam */
    .breadcrumb-custom {
      background: #f1f3f5;
      padding: 12px 20px;
      border-radius: 8px;
      margin-bottom: 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
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
    {{-- SIDEBAR --}}
    @include('layouts.partials.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="main-content">

      {{-- Breadcrumb + Jam --}}
      <div class="breadcrumb-custom">
        <div>
          <a href="{{ route('anggota.dashboard') }}" class="text-decoration-none text-secondary">
            <i class="fas fa-home"></i> Beranda
          </a> /
          <span class="text-dark">Booking Saya</span>
        </div>

        <div class="d-flex flex-column align-items-end">
          <div class="d-flex align-items-center">
            <i class="fas fa-clock me-1 text-secondary"></i>
            <span id="live-time" class="fw-semibold"></span>
          </div>
          {{-- Hari + Tanggal dalam Bahasa Indonesia --}}
          <small class="text-muted">
            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
          </small>
        </div>
      </div>

      {{-- Card Konten --}}
      <div class="content-card">
        <h4 class="mb-4"><i class="fas fa-calendar-check me-2"></i> Daftar Booking Buku</h4>

        <table class="table table-bordered table-striped align-middle">
          <thead class="table-light">
            <tr>
              <th>Judul Buku</th>
              <th>Tanggal Booking</th>
              <th>Status</th>
              <th>Catatan</th>
            </tr>
          </thead>
          <tbody>
            @forelse($bookings as $booking)
              <tr>
                <td>{{ $booking->buku->judul }}</td>
                <td>{{ $booking->created_at->translatedFormat('d M Y H:i') }}</td>
                <td>
                  @if($booking->status === 'booking')
                    <span class="badge bg-warning text-dark">Booking</span>
                  @elseif($booking->status === 'dipinjam')
                    <span class="badge bg-success">Disetujui</span>
                  @else
                    <span class="badge bg-danger">Ditolak</span>
                  @endif
                </td>
                <td>{{ $booking->catatan ?? '-' }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center p-4">Belum ada booking</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- FOOTER --}}
  @include('layouts.partials.footer')

  <!-- Font Awesome & Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Jam Live -->
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
