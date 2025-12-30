<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Laporan Penjualan - {{ $title ?? 'Sales Report' }}</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'DejaVu Sans', sans-serif;
      font-size: 10px;
      line-height: 1.4;
      color: #333;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 2px solid #10b981;
    }

    .header h1 {
      font-size: 18px;
      color: #10b981;
      margin-bottom: 5px;
    }

    .header p {
      font-size: 11px;
      color: #666;
    }

    .meta-info {
      margin-bottom: 15px;
      padding: 10px;
      background: #f8fafc;
      border-radius: 5px;
    }

    .meta-info p {
      margin: 3px 0;
    }

    .meta-info strong {
      color: #10b981;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th {
      background: #10b981;
      color: white;
      padding: 8px 5px;
      text-align: left;
      font-size: 9px;
      text-transform: uppercase;
    }

    td {
      padding: 6px 5px;
      border-bottom: 1px solid #e5e7eb;
      font-size: 9px;
    }

    tr:nth-child(even) {
      background: #f9fafb;
    }

    .status {
      padding: 2px 6px;
      border-radius: 3px;
      font-size: 8px;
      font-weight: bold;
      text-transform: uppercase;
    }

    .status-selesai {
      background: #d1fae5;
      color: #065f46;
    }

    .status-cancel {
      background: #fee2e2;
      color: #991b1b;
    }

    .status-dikembalikan {
      background: #fef3c7;
      color: #92400e;
    }

    .summary {
      margin-top: 20px;
      padding: 15px;
      background: #ecfdf5;
      border-radius: 5px;
    }

    .summary h3 {
      color: #10b981;
      margin-bottom: 10px;
      font-size: 12px;
    }

    .summary-grid {
      display: table;
      width: 100%;
    }

    .summary-item {
      display: table-cell;
      width: 25%;
      text-align: center;
      padding: 8px;
    }

    .summary-item .value {
      font-size: 14px;
      font-weight: bold;
      color: #10b981;
    }

    .summary-item .label {
      font-size: 9px;
      color: #666;
    }

    .footer {
      margin-top: 30px;
      text-align: center;
      font-size: 9px;
      color: #999;
      border-top: 1px solid #e5e7eb;
      padding-top: 10px;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }
  </style>
</head>

<body>
  <div class="header">
    <h1>📊 LAPORAN PENJUALAN</h1>
    <p>StockkuApp - CV Agrosehat Nusantara</p>
  </div>

  <div class="meta-info">
    <p><strong>Tanggal Cetak:</strong> {{ now()->format('d F Y, H:i') }} WIB</p>
    @if(isset($dateRange))
      <p><strong>Periode:</strong> {{ $dateRange }}</p>
    @endif
    <p><strong>Total Data:</strong> {{ count($reports) }} transaksi</p>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width: 8%">No</th>
        <th style="width: 10%">Tanggal</th>
        <th style="width: 15%">Customer</th>
        <th style="width: 12%">Kota</th>
        <th style="width: 18%">Paket</th>
        <th style="width: 5%" class="text-center">Qty</th>
        <th style="width: 12%" class="text-right">Total</th>
        <th style="width: 10%" class="text-center">Status</th>
        <th style="width: 10%">Pembayaran</th>
      </tr>
    </thead>
    <tbody>
      @forelse($reports as $index => $report)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}</td>
          <td>{{ $report->customer_name ?? '-' }}</td>
          <td>{{ $report->kota ?? '-' }}</td>
          <td>{{ $report->productPackage->name ?? '-' }}</td>
          <td class="text-center">{{ $report->quantity }}</td>
          <td class="text-right">Rp {{ number_format($report->total_price, 0, ',', '.') }}</td>
          <td class="text-center">
            <span class="status status-{{ strtolower($report->status) }}">
              {{ $report->status }}
            </span>
          </td>
          <td>{{ $report->payment ?? '-' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="9" class="text-center">Tidak ada data</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="summary">
    <h3>📈 RINGKASAN</h3>
    <div class="summary-grid">
      <div class="summary-item">
        <div class="value">{{ $reports->where('status', 'SELESAI')->count() }}</div>
        <div class="label">Selesai</div>
      </div>
      <div class="summary-item">
        <div class="value">{{ $reports->where('status', 'CANCEL')->count() }}</div>
        <div class="label">Cancel</div>
      </div>
      <div class="summary-item">
        <div class="value">{{ $reports->where('status', 'DIKEMBALIKAN')->count() }}</div>
        <div class="label">Dikembalikan</div>
      </div>
      <div class="summary-item">
        <div class="value">Rp {{ number_format($reports->where('status', 'SELESAI')->sum('total_price'), 0, ',', '.') }}
        </div>
        <div class="label">Total Pendapatan</div>
      </div>
    </div>
  </div>

  <div class="footer">
    <p>Dokumen ini digenerate otomatis oleh StockkuApp pada {{ now()->format('d F Y H:i:s') }}</p>
    <p>© {{ date('Y') }} CV Agrosehat Nusantara - All Rights Reserved</p>
  </div>
</body>

</html>