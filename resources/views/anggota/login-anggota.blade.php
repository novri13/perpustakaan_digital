<!-- resources/views/anggota/login.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Anggota - SiPERTAL SMANSA</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
    body {
      background-color: #e0e0e0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-card {
      background-color: #fff;
      border-radius: 20px;
      padding: 40px 30px;
      width: 350px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      text-align: center;
    }
    .login-card img {
      width: 80px;
      height: 80px;
      margin-bottom: 15px;
      background-color: #ccc;
      display: inline-block;
      border-radius: 5px;
    }
    .login-card h2 { margin: 10px 0 5px; font-weight: bold; }
    .login-card p { margin: 0; font-size: 14px; color: #333; }
    .login-card small {
      display: block;
      margin: 15px 0 20px;
      color: gray;
    }
    .input-group {
      margin-bottom: 15px;
      text-align: left;
      position: relative;
    }
    .input-group input {
      width: 100%;
      padding: 12px 45px 12px 40px;
      border: 1px solid #ccc;
      border-radius: 15px;
      font-size: 14px;
    }
    .input-group .icon {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      font-size: 16px;
      color: #888;
    }
    button {
      width: 100%;
      background-color: #333;
      color: #fff;
      padding: 12px;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    button:hover {
      background-color: #222;
    }
    .footer-text {
      margin-top: 20px;
      font-size: 13px;
      color: #0044cc;
      font-style: italic;
    }
    .login-switch {
      margin-top: 15px;
      display: flex;
      justify-content: space-between;
      gap: 10px;
    }
    .login-switch a {
      flex: 1;
      text-decoration: none;
      background-color: #007bff;
      color: #fff;
      padding: 10px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: bold;
      transition: background-color 0.3s;
      text-align: center;
      display: inline-block;
    }
    .login-switch a:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="login-card">
    <div><img src="{{ asset('images/logo_perpus.png') }}" alt="Logo Sekolah"></div>
    <h2>SiPERTAL SMANSA</h2>
    <p>Sistem Informasi Perpustakaan Digital<br>SMA Negeri 1 Bengkulu Selatan</p>
    <small>Masukkan NISN/NIP dan password untuk login</small>

    <form action="{{ route('anggota.login.submit') }}" method="POST">
    @csrf

    {{-- Redirect back --}}
    @if(request('redirect'))
        <input type="hidden" name="redirect" value="{{ request('redirect') }}">
    @endif

    @if ($errors->any())
        <small style="display:block; margin:10px 0; color:red;">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </small>
    @endif

    <div class="input-group">
        <i class="fas fa-id-card icon"></i>
        <input type="text" name="nisn" placeholder="NISN atau NIP" value="{{ old('nisn') }}" required>
    </div>
    <div class="input-group">
        <i class="fas fa-lock icon"></i>
        <input type="password" name="password" placeholder="Password" required>
    </div>
    <button type="submit">Log In</button>
    </form>

    <div class="footer-text">
      Dikembangkan Oleh SMA Negeri 1 Bengkulu Selatan - 2025
    </div>

    <!-- Tombol Login Admin dan Anggota -->
    <div class="login-switch">
      <a class="btn btn-primary" href="{{ url('/admin') }}">Login Untuk Admin</a>
      {{-- <a class="btn btn-primary" href="{{ route('anggota.login.form') }}">Login Anggota</a> --}}
    </div>
  </div>
</body>
</html>
