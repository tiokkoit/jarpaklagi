<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Laporan Mutasi Stok - {{ $title ?? 'Stock Movement Report' }}</title>
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
      border-bottom: 2px solid #2563eb;
    }

    .header h1 {
      font-size: 18px;
      color: #2563eb;
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
      color: #2563eb;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th {
      background: #2563eb;
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

    .status-in {
      background: #dbeafe;
      color: #1e40af;
    }

    .status-out {
      background: #fee2e2;
      color: #991b1b;
    }

    .summary {
      margin-top: 20px;
      padding: 15px;
      background: #eff6ff;
      border-radius: 5px;
    }

    .summary h3 {
      color: #2563eb;
      margin-bottom: 10px;
      font-size: 12px;
    }

    .summary-grid {
      display: table;
      width: 100%;
    }

    .summary-item {
      display: table-cell;
      width: 33%;
      text-align: center;
      padding: 8px;
    }

    .summary-item .value {
      font-size: 14px;
      font-weight: bold;
      color: #2563eb;
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
    <h1>📦 LAPORAN MUTASI STOK</h1>
    <p>StockkuApp - CV Agrosehat Nusantara</p>
  </div>

  <div class="meta-info">
    <p><strong>Tanggal Cetak:</strong> {{ now()->format('d F Y, H:i') }} WIB</p>
    @if(isset($dateRange))
      <p><strong>Periode:</strong> {{ $dateRange }}</p>
    @endif
    <p><strong>Total Transaksi:</strong> {{ count($movements) }}</p>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width: 5%">No</th>
        <th style="width: 12%">Tanggal</th>
        <th style="width: 20%">Produk</th>
        <th style="width: 10%">SKU</th>
        <th style="width: 8%" class="text-center">Tipe</th>
        <th style="width: 15%">Alasan</th>
        <th style="width: 8%" class="text-right">Qty</th>
        <th style="width: 8%" class="text-right">Awal</th>
        <th style="width: 8%" class="text-right">Akhir</th>
        <th style="width: 10%">Admin</th>
      </tr>
    </thead>
    <tbody>
      @forelse($movements as $index => $item)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
          <td>{{ $item->product->name ?? '-' }}</td>
          <td>{{ $item->product->sku ?? '-' }}</td>
          <td class="text-center">
            <span class="status status-{{ strtolower($item->type) }}">
              {{ strtoupper($item->type) }}
            </span>
          </td>
          <td>{{ $item->reason_text }}</td>
          <td class="text-right">{{ number_format($item->quantity) }}</td>
          <td class="text-right">{{ number_format($item->stock_before) }}</td>
          <td class="text-right">{{ number_format($item->stock_after) }}</td>
          <td>{{ $item->createdBy->name ?? '-' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="10" class="text-center">Tidak ada data</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="summary">
    <h3>📈 RINGKASAN</h3>
    <div class="summary-grid">
      <div class="summary-item">
        <div class="value">{{ $movements->where('type', 'in')->sum('quantity') }}</div>
        <div class="label">Total Masuk (Qty)</div>
      </div>
      <div class="summary-item">
        <div class="value">{{ $movements->where('type', 'out')->sum('quantity') }}</div>
        <div class="label">Total Keluar (Qty)</div>
      </div>
      <div class="summary-item">
        <div class="value">{{ $movements->count() }}</div>
        <div class="label">Total Transaksi</div>
      </div>
    </div>
  </div>

  <div class="footer">
    <p>Dokumen ini digenerate otomatis oleh StockkuApp pada {{ now()->format('d F Y H:i:s') }}</p>
    <p>© {{ date('Y') }} CV Agrosehat Nusantara - All Rights Reserved</p>
  </div>
</body>

</html>