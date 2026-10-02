<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard Admin — Lima Gerbang</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:wght@400;700&family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    :root {
      --admin-bg: #091210;
      --admin-card-bg: rgba(255, 255, 255, 0.04);
      --admin-border: rgba(255, 255, 255, 0.1);
      --admin-accent: #22c55e;
    }
    body {
      background: var(--admin-bg);
      color: #eaf2ef;
      font-family: 'Atkinson Hyperlegible', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
    }
    .admin-header {
      background: rgba(15, 28, 25, 0.95);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--admin-border);
      position: sticky;
      top: 0;
      z-index: 100;
      padding: 1rem 2rem;
    }
    .header-wrap {
      max-width: 1280px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .admin-brand {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      text-decoration: none;
      color: #fff;
    }
    .admin-brand strong {
      font-family: 'Bricolage Grotesque', sans-serif;
      font-size: 1.25rem;
    }
    .admin-brand span {
      background: rgba(34, 197, 94, 0.2);
      color: #4ade80;
      border: 1px solid rgba(34, 197, 94, 0.4);
      font-size: 0.75rem;
      padding: 0.2rem 0.5rem;
      border-radius: 4px;
      font-weight: 700;
      text-transform: uppercase;
    }
    .user-nav {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .user-email {
      font-size: 0.875rem;
      color: rgba(255, 255, 255, 0.7);
    }
    .btn-logout {
      background: rgba(239, 68, 68, 0.2);
      color: #fca5a5;
      border: 1px solid rgba(239, 68, 68, 0.4);
      padding: 0.4rem 0.875rem;
      border-radius: 6px;
      font-size: 0.85rem;
      font-weight: 700;
      cursor: pointer;
      transition: background 0.2s;
    }
    .btn-logout:hover {
      background: rgba(239, 68, 68, 0.35);
    }

    .admin-main {
      max-width: 1280px;
      margin: 2rem auto;
      padding: 0 1.5rem;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 1.25rem;
      margin-bottom: 2rem;
    }
    .stat-card {
      background: var(--admin-card-bg);
      border: 1px solid var(--admin-border);
      border-radius: 12px;
      padding: 1.25rem 1.5rem;
    }
    .stat-card .label {
      font-size: 0.85rem;
      color: rgba(255, 255, 255, 0.6);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 0.5rem;
    }
    .stat-card .value {
      font-family: 'Bricolage Grotesque', sans-serif;
      font-size: 2.25rem;
      font-weight: 800;
      color: #fff;
      margin: 0;
    }
    .stat-card .sub {
      font-size: 0.8rem;
      color: #4ade80;
      margin-top: 0.35rem;
    }

    .controls-bar {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
      background: var(--admin-card-bg);
      border: 1px solid var(--admin-border);
      border-radius: 12px;
      padding: 1rem 1.25rem;
      margin-bottom: 1.5rem;
    }
    .tabs {
      display: flex;
      gap: 0.5rem;
    }
    .tab-btn {
      padding: 0.5rem 1rem;
      border-radius: 8px;
      background: transparent;
      border: 1px solid transparent;
      color: rgba(255, 255, 255, 0.7);
      font-weight: 700;
      font-size: 0.9rem;
      text-decoration: none;
      transition: all 0.2s;
    }
    .tab-btn:hover {
      color: #fff;
      background: rgba(255, 255, 255, 0.05);
    }
    .tab-btn.active {
      background: rgba(34, 197, 94, 0.2);
      color: #4ade80;
      border-color: rgba(34, 197, 94, 0.4);
    }

    .search-export {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    .search-form {
      display: flex;
      gap: 0.5rem;
    }
    .search-input {
      background: rgba(0, 0, 0, 0.3);
      border: 1px solid var(--admin-border);
      color: #fff;
      padding: 0.45rem 0.85rem;
      border-radius: 6px;
      font-size: 0.9rem;
    }
    .btn-action {
      background: rgba(255, 255, 255, 0.1);
      color: #fff;
      border: 1px solid var(--admin-border);
      padding: 0.45rem 0.85rem;
      border-radius: 6px;
      font-size: 0.85rem;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      transition: background 0.2s;
    }
    .btn-action:hover {
      background: rgba(255, 255, 255, 0.2);
    }
    .btn-export-csv {
      background: rgba(56, 189, 248, 0.2);
      color: #7dd3fc;
      border-color: rgba(56, 189, 248, 0.4);
    }
    .btn-export-csv:hover {
      background: rgba(56, 189, 248, 0.35);
    }
    .btn-export-json {
      background: rgba(168, 85, 247, 0.2);
      color: #c084fc;
      border-color: rgba(168, 85, 247, 0.4);
    }
    .btn-export-json:hover {
      background: rgba(168, 85, 247, 0.35);
    }

    .table-container {
      background: var(--admin-card-bg);
      border: 1px solid var(--admin-border);
      border-radius: 12px;
      overflow-x: auto;
    }
    table.data-table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      font-size: 0.9rem;
    }
    table.data-table th {
      background: rgba(0, 0, 0, 0.3);
      color: rgba(255, 255, 255, 0.7);
      font-weight: 700;
      padding: 0.85rem 1rem;
      border-bottom: 1px solid var(--admin-border);
      white-space: nowrap;
    }
    table.data-table td {
      padding: 0.85rem 1rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      vertical-align: middle;
    }
    table.data-table tr:hover td {
      background: rgba(255, 255, 255, 0.02);
    }

    .badge {
      display: inline-block;
      padding: 0.2rem 0.5rem;
      border-radius: 4px;
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
    }
    .badge-pass { background: rgba(34, 197, 94, 0.2); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.4); }
    .badge-stop { background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.4); }
    .badge-wait { background: rgba(234, 179, 8, 0.2); color: #fde047; border: 1px solid rgba(234, 179, 8, 0.4); }
    .badge-info { background: rgba(56, 189, 248, 0.2); color: #7dd3fc; border: 1px solid rgba(56, 189, 248, 0.4); }

    .btn-del {
      background: transparent;
      border: none;
      color: #ef4444;
      cursor: pointer;
      padding: 0.3rem;
      border-radius: 4px;
      font-size: 0.85rem;
    }
    .btn-del:hover {
      background: rgba(239, 68, 68, 0.2);
    }

    .pagination-wrapper {
      padding: 1rem;
      display: flex;
      justify-content: center;
    }

    .flash-alert {
      padding: 0.85rem 1.25rem;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      font-size: 0.9rem;
    }
    .flash-success { background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #4ade80; }
    .flash-error { background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; }

    .empty-state {
      padding: 3rem 1rem;
      text-align: center;
      color: rgba(255, 255, 255, 0.5);
    }
  </style>
</head>
<body>
  <header class="admin-header">
    <div class="header-wrap">
      <a href="{{ url('/') }}" class="admin-brand">
        <strong>Lima Gerbang</strong>
        <span>Admin Panel</span>
      </a>

      <div class="user-nav">
        <span class="user-email">{{ Auth::user()->email }}</span>
        <a href="{{ url('/') }}" class="btn-action" target="_blank">Lihat Situs ↗</a>
        <form method="POST" action="{{ route('admin.logout') }}" style="display:inline;">
          @csrf
          <button type="submit" class="btn-logout">Keluar</button>
        </form>
      </div>
    </div>
  </header>

  <main class="admin-main">
    @if (session('success'))
      <div class="flash-alert flash-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="flash-alert flash-error">{{ session('error') }}</div>
    @endif

    <!-- Metric Stat Cards -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="label">Total Cek Ide</div>
        <div class="value">{{ number_format($stats['total_checks']) }}</div>
        <div class="sub">✓ {{ number_format($stats['passed_checks']) }} Lolos Semua Gerbang</div>
      </div>
      <div class="stat-card">
        <div class="label">Total Hitung Beban</div>
        <div class="value">{{ number_format($stats['total_calculations']) }}</div>
        <div class="sub">Log simulasi beban limbah</div>
      </div>
      <div class="stat-card">
        <div class="label">Pesan Asisten AI</div>
        <div class="value">{{ number_format($stats['total_chats']) }}</div>
        <div class="sub">Pertanyaan petani / pengguna</div>
      </div>
    </div>

    <!-- Controls Bar -->
    <div class="controls-bar">
      <div class="tabs">
        <a href="{{ route('admin.dashboard', ['tab' => 'checks', 'search' => $search]) }}" class="tab-btn {{ $tab === 'checks' ? 'active' : '' }}">
          📋 Pengecekan Ide ({{ $ideaChecks->total() }})
        </a>
        <a href="{{ route('admin.dashboard', ['tab' => 'calculations', 'search' => $search]) }}" class="tab-btn {{ $tab === 'calculations' ? 'active' : '' }}">
          🧮 Perhitungan Beban ({{ $calcLogs->total() }})
        </a>
        <a href="{{ route('admin.dashboard', ['tab' => 'chats', 'search' => $search]) }}" class="tab-btn {{ $tab === 'chats' ? 'active' : '' }}">
          💬 Pesan AI ({{ $chatMessages->total() }})
        </a>
      </div>

      <div class="search-export">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="search-form">
          <input type="hidden" name="tab" value="{{ $tab }}">
          <input type="text" name="search" class="search-input" value="{{ $search }}" placeholder="Cari data...">
          <button type="submit" class="btn-action">Cari</button>
        </form>

        <a href="{{ route('admin.export', ['type' => $tab, 'format' => 'csv']) }}" class="btn-action btn-export-csv" title="Unduh CSV">
          ⬇ Export CSV
        </a>
        <a href="{{ route('admin.export', ['type' => $tab, 'format' => 'json']) }}" class="btn-action btn-export-json" title="Unduh JSON">
          ⬇ Export JSON
        </a>
      </div>
    </div>

    <!-- Table Container -->
    <div class="table-container">
      @if ($tab === 'checks')
        <!-- Idea Checks Table -->
        @if ($ideaChecks->count() > 0)
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Waktu</th>
                <th>Pengguna</th>
                <th>Peran / Jenis</th>
                <th>Deskripsi Ide</th>
                <th>Status Hasil</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($ideaChecks as $check)
                <tr>
                  <td>#{{ $check->id }}</td>
                  <td>{{ $check->created_at->format('d/m/Y H:i') }}</td>
                  <td>
                    <strong>{{ $check->name }}</strong><br>
                    <small style="color:rgba(255,255,255,0.6)">{{ $check->email ?: '(tanpa email)' }}</small>
                  </td>
                  <td>
                    <span class="badge badge-info">{{ $check->role }}</span><br>
                    <small style="color:rgba(255,255,255,0.6)">{{ $check->path }}</small>
                  </td>
                  <td style="max-width: 280px; word-wrap: break-word;">{{ $check->idea }}</td>
                  <td>
                    @if ($check->result_status === 'pass')
                      <span class="badge badge-pass">✓ Lolos 5 Gerbang</span>
                    @elseif ($check->result_status === 'stop')
                      <span class="badge badge-stop">✕ Terhenti G{{ $check->stopped_gate }}</span>
                    @else
                      <span class="badge badge-wait">? Perlu Bukti G{{ $check->stopped_gate }}</span>
                    @endif
                  </td>
                  <td>
                    <form method="POST" action="{{ route('admin.delete', ['type' => 'checks', 'id' => $check->id]) }}" onsubmit="return confirm('Hapus data ini?');" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-del" title="Hapus">🗑 Hapus</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="pagination-wrapper">
            {{ $ideaChecks->links() }}
          </div>
        @else
          <div class="empty-state">Belum ada data pengecekan ide.</div>
        @endif

      @elseif ($tab === 'calculations')
        <!-- Calculation Logs Table -->
        @if ($calcLogs->count() > 0)
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Waktu</th>
                <th>Sebelum</th>
                <th>Sesudah</th>
                <th>Perubahan Load</th>
                <th>Energi (CO₂)</th>
                <th>Hasil Verdict</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($calcLogs as $calc)
                <tr>
                  <td>#{{ $calc->id }}</td>
                  <td>{{ $calc->created_at->format('d/m/Y H:i') }}</td>
                  <td>
                    {{ $calc->flow_before }} m³/h<br>
                    <small style="color:rgba(255,255,255,0.6)">COD: {{ number_format($calc->cod_before) }} | Load: {{ number_format($calc->load_before) }} kg</small>
                  </td>
                  <td>
                    {{ $calc->flow_after }} m³/h<br>
                    <small style="color:rgba(255,255,255,0.6)">COD: {{ number_format($calc->cod_after) }} | Load: {{ number_format($calc->load_after) }} kg</small>
                  </td>
                  <td>
                    <strong style="color: {{ $calc->load_change < 0 ? '#4ade80' : '#fca5a5' }}">
                      {{ $calc->load_change > 0 ? '+' : '' }}{{ number_format($calc->load_change, 1) }} kg/hari
                    </strong><br>
                    <small style="color:rgba(255,255,255,0.6)">Unc: ±{{ number_format($calc->unc_combined, 1) }}</small>
                  </td>
                  <td>
                    {{ number_format($calc->co2_net, 1) }} kg CO₂e
                  </td>
                  <td>
                    @if (str_contains($calc->verdict, 'clear') || str_contains($calc->verdict, 'v_clear'))
                      <span class="badge badge-pass">Beban Turun Jelas</span>
                    @elseif (str_contains($calc->verdict, 'unclear') || str_contains($calc->verdict, 'v_unclear'))
                      <span class="badge badge-wait">Dalam Toleransi Err</span>
                    @else
                      <span class="badge badge-stop">Gagal / Beban Naik</span>
                    @endif
                  </td>
                  <td>
                    <form method="POST" action="{{ route('admin.delete', ['type' => 'calculations', 'id' => $calc->id]) }}" onsubmit="return confirm('Hapus data ini?');" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-del" title="Hapus">🗑 Hapus</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="pagination-wrapper">
            {{ $calcLogs->links() }}
          </div>
        @else
          <div class="empty-state">Belum ada data perhitungan beban.</div>
        @endif

      @elseif ($tab === 'chats')
        <!-- Chat Messages Table -->
        @if ($chatMessages->count() > 0)
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Waktu</th>
                <th>Session ID</th>
                <th>Role</th>
                <th>Pesan / Prompt</th>
                <th>Model</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($chatMessages as $msg)
                <tr>
                  <td>#{{ $msg->id }}</td>
                  <td>{{ $msg->created_at->format('d/m/Y H:i') }}</td>
                  <td><small style="font-family:monospace; color:rgba(255,255,255,0.7)">{{ substr($msg->session_id, 0, 8) }}...</small></td>
                  <td>
                    <span class="badge {{ $msg->role === 'user' ? 'badge-info' : 'badge-pass' }}">{{ $msg->role }}</span>
                  </td>
                  <td style="max-width: 400px; word-wrap: break-word;">{{ $msg->content }}</td>
                  <td><small style="color:rgba(255,255,255,0.6)">{{ $msg->model ?: 'default' }}</small></td>
                  <td>
                    <form method="POST" action="{{ route('admin.delete', ['type' => 'chats', 'id' => $msg->id]) }}" onsubmit="return confirm('Hapus pesan ini?');" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn-del" title="Hapus">🗑 Hapus</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="pagination-wrapper">
            {{ $chatMessages->links() }}
          </div>
        @else
          <div class="empty-state">Belum ada data pesan AI.</div>
        @endif
      @endif
    </div>
  </main>
</body>
</html>
