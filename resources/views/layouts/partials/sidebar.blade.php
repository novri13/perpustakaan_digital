<!-- SIDEBAR -->
    <aside class="sidebar">
      <h4><i class="fas fa-book-reader me-2"></i> Menu Dashboard</h4>

      <nav class="nav flex-column">

        <!-- Dashboard -->
        <a href="{{ route('anggota.dashboard') }}" 
           class="nav-link {{ request()->routeIs('anggota.dashboard') ? 'active' : '' }}">
          <i class="fas fa-home"></i> Dashboard
        </a>

        <!-- Profil -->
        <a href="{{ route('anggota.profil') }}" 
           class="nav-link {{ request()->routeIs('anggota.profil') ? 'active' : '' }}">
          <i class="fas fa-user"></i> Profil
        </a>

        <!-- Riwayat Peminjaman -->
        <a href="{{ route('anggota.history-transaksi') }}" 
           class="nav-link {{ request()->routeIs('anggota.history-transaksi') ? 'active' : '' }}">
          <i class="fas fa-book-open"></i> Riwayat Peminjaman
        </a>

        <!-- Riwayat Booking -->
        <a href="{{ route('anggota.bookings.index') }}" 
           class="nav-link {{ request()->routeIs('anggota.bookings.index') ? 'active' : '' }}">
          <i class="fas fa-calendar-check"></i> Riwayat Booking
        </a>

        <!-- Notifikasi -->
        {{-- <a href="{{ route('anggota.notifikasi') ?? '#' }}" 
           class="nav-link {{ request()->routeIs('anggota.notifikasi') ? 'active' : '' }}">
          <i class="fas fa-bell"></i> Notifikasi
        </a> --}}

        <!-- Logout -->
        <form action="{{ route('logout') }}" method="POST" class="mt-3">
          @csrf
          <button type="submit" class="nav-link bg-transparent border-0 text-start w-100">
            <i class="fas fa-sign-out-alt"></i> Logout
          </button>
        </form>

      </nav>
    </aside>