<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Laporan Penjualan - {{ $title ?? 'Sales Report' }}</title>
  <style>
    @font-face {
      font-family: 'Inter';
      src: url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'DejaVu Sans', sans-serif;
      font-size: 9px;
      line-height: 1.4;
      color: #1e293b;
      padding: 0;
    }

    .watermark {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%) rotate(-45deg);
      font-size: 100px;
      color: rgba(203, 213, 225, 0.2);
      z-index: -1;
      font-weight: bold;
      pointer-events: none;
    }

    /* Header */
    .header {
      width: 100%;
      border-bottom: 3px solid #10b981;
      padding-bottom: 20px;
      margin-bottom: 25px;
      display: table;
    }

    .logo-container {
      display: table-cell;
      width: 20%;
      vertical-align: middle;
    }

    .logo {
      max-height: 60px;
      width: auto;
    }

    .company-info {
      display: table-cell;
      width: 80%;
      vertical-align: middle;
      text-align: right;
    }

    .company-name {
      font-size: 20px;
      font-weight: bold;
      color: #10b981;
      margin-bottom: 5px;
      text-transform: uppercase;
    }

    .company-address {
      font-size: 10px;
      color: #64748b;
    }

    /* Summary Cards */
    .summary-grid {
      display: table;
      width: 100%;
      margin-bottom: 25px;
      border-spacing: 10px 0;
    }

    .summary-card {
      display: table-cell;
      width: 25%;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 15px;
      text-align: center;
    }

    .summary-title {
      font-size: 10px;
      color: #64748b;
      margin-bottom: 5px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .summary-value {
      font-size: 18px;
      font-weight: bold;
      color: #10b981;
    }

    .summary-sub {
      font-size: 8px;
      color: #94a3b8;
      margin-top: 3px;
    }

    /* Info Bar */
    .info-bar {
      background: #f0fdf4;
      padding: 10px 15px;
      border-radius: 6px;
      margin-bottom: 20px;
      border-left: 4px solid #10b981;
      font-size: 10px;
      color: #334155;
    }

    .info-bar strong {
      color: #064e3b;
    }

    /* Table */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }

    th {
      background: #f8fafc;
      color: #475569;
      padding: 12px 8px;
      text-align: left;
      font-size: 9px;
      font-weight: bold;
      text-transform: uppercase;
      border-bottom: 2px solid #e2e8f0;
      border-top: 1px solid #e2e8f0;
    }

    td {
      padding: 10px 8px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
      color: #334155;
    }

    tr:nth-child(even) {
      background: #fafafa;
    }

    /* Status Pills */
    .pill {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 9999px;
      font-size: 8px;
      font-weight: bold;
      text-transform: uppercase;
      line-height: 1;
    }

    .pill-selesai {
      background: #d1fae5;
      color: #065f46;
    }

    .pill-cancel {
      background: #fee2e2;
      color: #991b1b;
    }

    .pill-dikembalikan {
      background: #fef3c7;
      color: #92400e;
    }

    .pill-new {
      background: #e0f2fe;
      color: #075985;
    }

    .pill-dikirim {
      background: #f3e8ff;
      color: #6b21a8;
    }


    /* Footer */
    .footer {
      width: 100%;
      position: fixed;
      bottom: 0;
      left: 0;
      border-top: 1px solid #e2e8f0;
      padding-top: 10px;
      font-size: 8px;
      color: #94a3b8;
    }

    .footer-content {
      display: table;
      width: 100%;
    }

    .footer-left {
      display: table-cell;
      width: 50%;
      text-align: left;
    }

    .footer-right {
      display: table-cell;
      width: 50%;
      text-align: right;
    }

    /* Signatures */
    .signatures {
      width: 100%;
      margin-top: 40px;
      page-break-inside: avoid;
    }

    .signature-box {
      width: 33.33%;
      float: left;
      text-align: center;
      padding: 0 10px;
    }

    .signature-line {
      margin-top: 60px;
      border-top: 1px solid #cbd5e1;
      width: 80%;
      margin-left: auto;
      margin-right: auto;
    }

    .signature-title {
      font-weight: bold;
      color: #475569;
      margin-bottom: 5px;
      font-size: 10px;
    }

    .signature-date {
      color: #94a3b8;
      font-size: 9px;
    }

    /* Helper Classes */
    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    .text-bold {
      font-weight: bold;
    }
  </style>
</head>

<body>
  <!-- Watermark -->
  <div class="watermark">CONFIDENTIAL</div>

  <!-- Header -->
  <div class="header">
    <div class="logo-container">
      <img src="{{ public_path('images/stockku-logo.png') }}" class="logo" alt="Logo">
    </div>
    <div class="company-info">
      <div class="company-name">CV AGROSEHAT NUSANTARA</div>
      <div class="company-address">
        Jl. Raya Maju Mundur No. 123, Kab. Sleman, D.I. Yogyakarta<br>
        Telp: (0274) 123456 | Email: info@agrosehat.id
      </div>
    </div>
  </div>

  <!-- Summary Cards -->
  @if(isset($summary))
    <div class="summary-grid">
      <div class="summary-card">
        <div class="summary-title">Total Pendapatan</div>
        <div class="summary-value">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
        <div class="summary-sub">Status Selesai</div>
      </div>
      <div class="summary-card">
        <div class="summary-title">Total Transaksi</div>
        <div class="summary-value">{{ number_format($summary['total_transactions']) }}</div>
        <div class="summary-sub">Semua Status</div>
      </div>
      <div class="summary-card">
        <div class="summary-title">Dibatalkan</div>
        <div class="summary-value" style="color: #991b1b;">{{ number_format($summary['total_cancelled']) }}</div>
        <div class="summary-sub">Transaksi</div>
      </div>
      <div class="summary-card">
        <div class="summary-title">Produk Terlaris</div>
        <div class="summary-value" style="font-size: 12px; line-height: 1.2; margin-top: 5px;">
          {{ \Illuminate\Support\Str::limit($summary['best_selling_product'], 20) }}
        </div>
        <div class="summary-sub">{{ $summary['best_selling_count'] }} Terjual</div>
      </div>
    </div>
  @endif

  <!-- Info Bar -->
  <div class="info-bar">
    <div style="display: table; width: 100%;">
      <div style="display: table-cell;">
        <strong>PERIODE LAPORAN:</strong> {{ $dateRange ?? 'Semua Waktu' }}
      </div>
      <div style="display: table-cell; text-align: right;">
        <strong>DICETAK OLEH:</strong> {{ auth()->user()->name }}
      </div>
    </div>
  </div>

  <!-- Main Table -->
  <table>
    <thead>
      <tr>
        <th style="width: 5%">No</th>
        <th style="width: 10%">Tanggal</th>
        <th style="width: 15%">Customer</th>
        <th style="width: 10%">Kota</th>
        <th style="width: 20%">Paket</th>
        <th style="width: 5%" class="text-center">Qty</th>
        <th style="width: 15%" class="text-right">Total</th>
        <th style="width: 10%" class="text-center">Status</th>
        <th style="width: 10%">Pembayaran</th>
      </tr>
    </thead>
    <tbody>
      @forelse($reports as $index => $report)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>
            <div style="font-weight: bold;">{{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}</div>
          </td>
          <td>
            <div style="font-weight: 600;">{{ $report->customer_name ?? '-' }}</div>
            <div style="font-size: 8px; color: #64748b;">{{ $report->phone ?? '' }}</div>
          </td>
          <td>{{ $report->kota ?? '-' }}</td>
          <td>{{ $report->productPackage->name ?? '-' }}</td>
          <td class="text-center">{{ $report->quantity }}</td>
          <td class="text-right" style="font-weight: bold;">Rp {{ number_format($report->total_price, 0, ',', '.') }}</td>
          <td class="text-center">
            <span class="pill pill-{{ strtolower($report->status) }}">
              {{ $report->status }}
            </span>
          </td>
          <td>{{ $report->payment ?? '-' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="9" class="text-center" style="padding: 20px; color: #94a3b8; font-style: italic;">
            Tidak ada data transaksi.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <!-- Signatures -->
  <div class="signatures">
    <div class="signature-box">
      <div class="signature-title">Dibuat Oleh</div>
      <div class="signature-line"></div>
      <div class="signature-date">Admin Penjualan</div>
    </div>
    <div class="signature-box">
      <div class="signature-title">Diperiksa Oleh</div>
      <div class="signature-line"></div>
      <div class="signature-date">Manager Keuangan</div>
    </div>
    <div class="signature-box">
      <div class="signature-title">Disetujui Oleh</div>
      <div class="signature-line"></div>
      <div class="signature-date">Direktur Utama</div>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <div class="footer-content">
      <div class="footer-left">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} | ID: {{ Str::random(8) }}
      </div>
      <div class="footer-right">
        Halaman
        <script type="text/php">if (isset($pdf)) { echo $pdf->get_page_number(); }</script> dari
        <script type="text/php">if (isset($pdf)) { echo $pdf->get_page_count(); }</script>
      </div>
    </div>
  </div>
</body>

</html>