<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Login — Lima Gerbang</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:wght@400;700&family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      background: var(--bg-hero, #0f1c19);
      color: var(--text-light, #eaf2ef);
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
    }
    .auth-card {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid rgba(255, 255, 255, 0.12);
      border-radius: 16px;
      padding: 2.5rem;
      width: 100%;
      max-width: 440px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }
    .auth-brand {
      text-align: center;
      margin-bottom: 2rem;
    }
    .auth-brand h1 {
      font-family: 'Bricolage Grotesque', sans-serif;
      font-size: 1.75rem;
      margin: 0.5rem 0 0.25rem 0;
      color: #fff;
    }
    .auth-brand p {
      color: rgba(255, 255, 255, 0.7);
      font-size: 0.9rem;
      margin: 0;
    }
    .form-group {
      margin-bottom: 1.25rem;
    }
    .form-group label {
      display: block;
      font-size: 0.875rem;
      font-weight: 700;
      margin-bottom: 0.375rem;
      color: rgba(255, 255, 255, 0.9);
    }
    .form-group input[type="email"],
    .form-group input[type="password"] {
      width: 100%;
      padding: 0.75rem 1rem;
      border-radius: 8px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      background: rgba(0, 0, 0, 0.25);
      color: #fff;
      font-size: 1rem;
      box-sizing: border-box;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-group input:focus {
      outline: none;
      border-color: #38bdf8;
      box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.25);
    }
    .checkbox-row {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 1.5rem;
      font-size: 0.875rem;
      color: rgba(255, 255, 255, 0.8);
    }
    .checkbox-row input {
      accent-color: #22c55e;
      width: 16px;
      height: 16px;
    }
    .alert-danger {
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid rgba(239, 68, 68, 0.3);
      color: #fca5a5;
      padding: 0.75rem 1rem;
      border-radius: 8px;
      font-size: 0.875rem;
      margin-bottom: 1.25rem;
    }
    .btn-submit {
      width: 100%;
      padding: 0.875rem;
      border-radius: 8px;
      border: none;
      background: linear-gradient(135deg, #16a34a, #22c55e);
      color: #fff;
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      transition: transform 0.15s, box-shadow 0.15s;
    }
    .btn-submit:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }
    .back-home {
      display: block;
      text-align: center;
      margin-top: 1.5rem;
      color: rgba(255, 255, 255, 0.6);
      text-decoration: none;
      font-size: 0.875rem;
    }
    .back-home:hover {
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="auth-card">
    <div class="auth-brand">
      <h1>Lima Gerbang</h1>
      <p>Panel Masuk Administrator</p>
    </div>

    @if ($errors->any())
      <div class="alert-danger">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('admin.login') }}">
      @csrf
      <div class="form-group">
        <label for="email">Alamat Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@limagerbang.id">
      </div>

      <div class="form-group">
        <label for="password">Kata Sandi</label>
        <input type="password" id="password" name="password" required placeholder="••••••••">
      </div>

      <div class="checkbox-row">
        <input type="checkbox" id="remember" name="remember" value="1">
        <label for="remember">Ingat Sesi Saya</label>
      </div>

      <button type="submit" class="btn-submit">Masuk ke Dashboard</button>
    </form>

    <a href="{{ url('/') }}" class="back-home">← Kembali ke Halaman Utama</a>
  </div>
</body>
</html>
