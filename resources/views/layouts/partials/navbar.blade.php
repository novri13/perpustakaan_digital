<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3">
  <div class="container">
    <!-- Logo & Title -->
    <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
      <img src="{{ asset('images/logo_perpus.png') }}" alt="Logo" width="45" class="me-2">
      <div class="lh-sm">
        <span class="fw-bold text-primary" style="font-size: 1.1rem;">Perpustakaan Digital</span><br>
        <small class="text-muted">SMA Negeri 1 Bengkulu Selatan</small>
      </div>
    </a>

    <!-- Mobile Toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu -->
    <div class="collapse navbar-collapse justify-content-end" id="mainNavbar">
      <ul class="navbar-nav align-items-center gap-lg-3">
        <li class="nav-item">
          <a class="nav-link fw-medium {{ request()->routeIs('home') ? 'text-primary' : 'text-dark' }} hover-underline" href="{{ route('home') }}">Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-medium {{ request()->routeIs('denah') ? 'text-primary' : 'text-dark' }} hover-underline" href="{{ route('denah') }}">Denah Pustaka</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fw-medium {{ request()->routeIs('pustakawan') ? 'text-primary' : 'text-dark' }} hover-underline" href="{{ route('pustakawan') }}">Pustakawan</a>
        </li>

        @guest
          <li class="nav-item ms-lg-3">
            <a class="btn btn-primary rounded-pill px-4" href="{{ route('anggota.login.form') }}">
              <i class="fas fa-sign-in-alt me-1"></i> Login
            </a>
          </li>
        @else
          @php
            $user = auth()->user();
            $unreadCount = $user->unreadNotifications->count();
            $notifications = $user->notifications()->latest()->take(5)->get();
          @endphp

          <!-- 🔔 Notifikasi Dropdown -->
          <li class="nav-item dropdown me-2">
            <a href="#" id="notifDropdown" class="nav-link position-relative" data-bs-toggle="dropdown">
              <i class="fas fa-bell fa-lg"></i>
              @if($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                  {{ $unreadCount }}
                </span>
              @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 300px; max-height: 350px; overflow-y: auto;">
              <li class="dropdown-header fw-bold px-3 py-2">Notifikasi Terbaru</li>
              @forelse($notifications as $notif)
                <li class="px-3 py-2 border-bottom small">
                  <div class="fw-bold">{{ $notif->data['judul'] ?? '-' }}</div>
                  <div class="text-muted">{{ $notif->data['pesan'] ?? $notif->data['message'] ?? '' }}</div>
                  <small class="text-secondary">{{ $notif->created_at->diffForHumans() }}</small>
                </li>
              @empty
                <li class="px-3 py-2 text-muted">Tidak ada notifikasi</li>
              @endforelse
              <li><hr class="dropdown-divider"></li>
              <li><span class="dropdown-item text-center small text-muted">Menampilkan 5 notifikasi terakhir</span></li>
            </ul>
          </li>

          <!-- 👤 Dropdown Profil -->
          <li class="nav-item dropdown">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown">
              @if($user->gambar)
                <img src="{{ asset('storage/' . $user->gambar) }}" alt="Foto Profil" class="rounded-circle me-2" width="34" height="34" style="object-fit: cover;">
              @else
                <i class="fas fa-user-circle fa-lg me-2 text-secondary"></i>
              @endif
              <span class="d-none d-md-inline fw-medium">{{ $user->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
              <li><a class="dropdown-item" href="{{ route('anggota.dashboard') }}"><i class="fas fa-home me-2"></i> Dashboard</a></li>
              <li><a class="dropdown-item" href="{{ route('anggota.profil') }}"><i class="fas fa-user me-2"></i> Profil</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                </form>
              </li>
            </ul>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>

@auth
<script>
document.addEventListener("DOMContentLoaded", function() {
    const notifDropdown = document.getElementById("notifDropdown");

    notifDropdown.addEventListener("click", function () {
        let badge = this.querySelector(".badge");
        if (badge) {
            fetch("{{ route('notifications.markAsRead') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    badge.remove(); // Hilangkan badge tanpa reload
                }
            })
            .catch(err => console.error("Error mark-as-read:", err));
        }
    });
});
</script>
@endauth
