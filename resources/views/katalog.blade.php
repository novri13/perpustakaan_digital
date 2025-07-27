<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/x-icon" href="{{ asset('images/logo_perpus.png') }}">
  <title>Katalog Buku - Perpustakaan Digital</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  @php use Illuminate\Support\Str; @endphp

  <style>
    body {
      background: #f8f9fa;
      font-family: "Segoe UI", sans-serif;
    }

    /* SEARCH SECTION */
    .search-box {
      background: linear-gradient(135deg, #0d6efd, #4a8efc);
      padding: 40px 0;
      color: white;
    }
    .search-box h2 {
      font-weight: bold;
      margin-bottom: 15px;
    }
    .search-bar {
      max-width: 600px;
      margin: auto;
      display: flex;
      background: white;
      border-radius: 50px;
      overflow: hidden;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }
    .search-bar input {
      border: none;
      padding: 12px 20px;
      flex: 1;
      outline: none;
    }
    .search-bar button {
      background: #0d6efd;
      color: white;
      border: none;
      padding: 12px 20px;
      transition: 0.3s ease;
    }
    .search-bar button:hover {
      background: #0b5ed7;
    }

    /* SIDEBAR FILTER */
    .sidebar {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    }
    .sidebar h5 {
      font-weight: bold;
      margin-bottom: 15px;
      color: #333;
    }
    .sidebar strong {
      font-size: 0.95rem;
      color: #555;
    }
    .reset-btn {
      font-size: 0.8rem;
      background: #f1f1f1;
      padding: 4px 8px;
      border-radius: 6px;
      color: #555;
      text-decoration: none;
    }
    .reset-btn:hover {
      background: #e2e6ea;
    }

    /* GRID BUKU */
    .book-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
      gap: 20px;
    }

    /* KARTU BUKU */
    .book-card {
      background: white;
      border-radius: 12px;
      padding: 15px;
      display: flex;
      justify-content: space-between;
      gap: 15px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.05);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .book-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 14px rgba(0,0,0,0.1);
    }
    .book-main {
      display: flex;
      gap: 15px;
      flex: 1;
    }
    .book-cover {
      width: 80px;
      height: 120px;
      border-radius: 8px;
      object-fit: cover;
      background: #eee;
    }
    .book-info {
      flex: 1;
    }
    .book-info h5 {
      margin: 0 0 5px;
      font-weight: bold;
    }
    .label-penerbit {
      display: inline-block;
      background: #eef2f7;
      border-radius: 4px;
      padding: 3px 8px;
      font-size: 0.8rem;
      color: #555;
      margin-bottom: 8px;
    }
    .book-actions {
      text-align: right;
      min-width: 110px;
    }
    .stok-badge {
      font-size: 0.9rem;
      background: #198754;
      color: white;
      padding: 6px 10px;
      border-radius: 6px;
      display: inline-block;
      margin-bottom: 8px;
    }
    .btn-detail {
      border-radius: 30px;
      padding: 6px 15px;
    }

    .pagination {
      justify-content: center;
    }
  </style>
</head>
<body>

  {{-- NAVBAR --}}
  @include('layouts.partials.navbar')

  <!-- =========================== SEARCH BAR GLOBAL =========================== -->
  <div class="search-box text-center">
    <h2>Katalog Buku Digital</h2>
    <p class="mb-4">Temukan buku favorit Anda di perpustakaan kami</p>

    <form method="GET" action="{{ route('katalog') }}" class="search-bar">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Masukkan kata kunci buku...">
      <button type="submit"><i class="fas fa-search"></i></button>
    </form>
  </div>

  <!-- =========================== MAIN CONTENT =========================== -->
  <div class="container py-4">
    <form method="GET" action="{{ route('katalog') }}">
      <div class="row">

        <!-- Sidebar Filter -->
        <div class="col-md-3">
          <div class="sidebar">
            <h5>🎯 Filter Buku</h5>

            <!-- Reset Semua Filter -->
            @if(request()->hasAny(['tahun','rak','bahasa','q']))
              <div class="mb-2">
                <a href="{{ route('katalog') }}" class="reset-btn">🔄 Reset Semua Filter</a>
              </div>
            @endif

            <!-- Tahun Terbit -->
            <div class="mb-4">
              <strong>Tahun Terbit</strong>
              @if(request('tahun'))
                <div><a href="{{ request()->fullUrlWithQuery(['tahun' => null]) }}" class="reset-btn">⟳ Reset Tahun</a></div>
              @endif

              @php $tahunChunk = array_slice($tahunList,0,3); @endphp
              @foreach($tahunChunk as $tahun)
                <div>
                  <input type="radio" name="tahun" value="{{ $tahun }}" onchange="this.form.submit()" {{ request('tahun')==$tahun?'checked':'' }}> {{ $tahun }}
                </div>
              @endforeach

              <div id="tahun-more" style="display:none;">
                @foreach(array_slice($tahunList,3) as $tahun)
                  <div>
                    <input type="radio" name="tahun" value="{{ $tahun }}" onchange="this.form.submit()" {{ request('tahun')==$tahun?'checked':'' }}> {{ $tahun }}
                  </div>
                @endforeach
              </div>
              @if(count($tahunList)>3)
                <a href="#" class="toggle-filter" data-target="#tahun-more">Lihat Selengkapnya</a>
              @endif
            </div>
            <hr>

            <!-- Lokasi Rak -->
            <div class="mb-4">
              <strong>Lokasi Rak</strong>
              @if(request('rak'))
                <div><a href="{{ request()->fullUrlWithQuery(['rak'=>null]) }}" class="reset-btn">⟳ Reset Rak</a></div>
              @endif
              @php $rakChunk = array_slice($rakList,0,3,true); @endphp
              @foreach($rakChunk as $id=>$nama)
                <div>
                  <input type="radio" name="rak" value="{{ $id }}" onchange="this.form.submit()" {{ request('rak')==$id?'checked':'' }}> {{ $nama }}
                </div>
              @endforeach

              <div id="rak-more" style="display:none;">
                @foreach(array_slice($rakList,3,null,true) as $id=>$nama)
                  <div>
                    <input type="radio" name="rak" value="{{ $id }}" onchange="this.form.submit()" {{ request('rak')==$id?'checked':'' }}> {{ $nama }}
                  </div>
                @endforeach
              </div>
              @if(count($rakList)>3)
                <a href="#" class="toggle-filter" data-target="#rak-more">Lihat Selengkapnya</a>
              @endif
            </div>
            <hr>

            <!-- Bahasa -->
            <div class="mb-4">
              <strong>Bahasa</strong>
              @if(request('bahasa'))
                <div><a href="{{ request()->fullUrlWithQuery(['bahasa'=>null]) }}" class="reset-btn">⟳ Reset Bahasa</a></div>
              @endif
              @php $bahasaChunk=array_slice($bahasaList,0,3); @endphp
              @foreach($bahasaChunk as $bahasa)
                <div>
                  <input type="radio" name="bahasa" value="{{ $bahasa }}" onchange="this.form.submit()" {{ request('bahasa')==$bahasa?'checked':'' }}> {{ ucfirst($bahasa) }}
                </div>
              @endforeach
              <div id="bahasa-more" style="display:none;">
                @foreach(array_slice($bahasaList,3) as $bahasa)
                  <div>
                    <input type="radio" name="bahasa" value="{{ $bahasa }}" onchange="this.form.submit()" {{ request('bahasa')==$bahasa?'checked':'' }}> {{ ucfirst($bahasa) }}
                  </div>
                @endforeach
              </div>
              @if(count($bahasaList)>3)
                <a href="#" class="toggle-filter" data-target="#bahasa-more">Lihat Selengkapnya</a>
              @endif
            </div>
          </div>
        </div>

        <!-- BOOK GRID -->
        <div class="col-md-9">
          <!-- Jumlah hasil -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>Ditemukan <strong>{{ $bukus->total() }}</strong> buku</div>
          </div>
          
          <div class="book-grid">
            @forelse($bukus as $buku)
              @php 
                $gambarBuku = $buku->gambar 
                  ? asset('storage/'.$buku->gambar)
                  : asset('storage/no-cover.png');
              @endphp

              <div class="book-card">
                <div class="book-main">
                  <img src="{{ $gambarBuku }}" alt="Sampul" class="book-cover">

                  <div class="book-info">
                    <h5>{{ $buku->judul }}</h5>
                    <span class="label-penerbit">{{ $buku->penerbit->name ?? '-' }}</span>
                    <p class="small">{{ Str::limit(strip_tags($buku->deskripsi), 80) }}</p>
                    <p class="small">Edisi/Tahun: {{ $buku->edisi ?? '-' }} / {{ $buku->tahun_terbit ? \Carbon\Carbon::parse($buku->tahun_terbit)->format('Y') : '-' }}</p>
                    <p class="small">Kategori: {{ $buku->kategori->name ?? '-' }}</p>
                    <p class="small">Bahasa: {{ $buku->bahasa ?? '-' }}</p>
                  </div>
                </div>

                <div class="book-actions">
                  <div class="stok-badge">{{ $buku->stok }} Stok</div><br>
                  <a href="{{ route('detail-buku',$buku->id) }}" class="btn btn-outline-primary btn-sm btn-detail">Detail Buku</a>
                </div>
              </div>
            @empty
              <p class="text-muted">Tidak ada buku ditemukan.</p>
            @endforelse
          </div>

          <!-- Pagination -->
          @if($bukus->hasPages())
          <div class="mt-4 d-flex justify-content-center">
            {{ $bukus->onEachSide(1)->links('pagination::bootstrap-5') }}
          </div>
          @endif
        </div>
      </div>
    </form>
  </div>

  {{-- FOOTER --}}
  @include('layouts.partials.footer')

  <!-- Bootstrap & Icons -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Toggle Filter -->
  <script>
    document.querySelectorAll('.toggle-filter').forEach(link=>{
      link.addEventListener('click',function(e){
        e.preventDefault();
        let target=document.querySelector(this.dataset.target);
        if(target.style.display==='none'){
          target.style.display='block';
          this.textContent='Sembunyikan';
        } else {
          target.style.display='none';
          this.textContent='Lihat Selengkapnya';
        }
      });
    });
  </script>
</body>
</html>
