<?php

namespace App\Http\Controllers;

use App\Models\SalesReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SalesReportPdfController extends Controller
{
  public function export(Request $request)
  {
    // Get filter parameters from query string
    $status = $request->get('status');
    $startDate = $request->get('start_date');
    $endDate = $request->get('end_date');

    // Build query
    $query = SalesReport::with('productPackage')
      ->orderBy('report_date', 'desc');

    // Apply status filter
    if ($status && $status !== 'all') {
      $query->where('status', strtoupper($status));
    }

    // Apply date range filter
    if ($startDate && $endDate) {
      $query->whereBetween('report_date', [$startDate, $endDate]);
    }

    $reports = $query->get();

    // Prepare date range text
    $dateRange = null;
    if ($startDate && $endDate) {
      $dateRange = \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($endDate)->format('d M Y');
    }

    // Generate PDF
    $pdf = Pdf::loadView('pdf.sales-report', [
      'reports' => $reports,
      'dateRange' => $dateRange,
      'title' => 'Laporan Penjualan',
    ]);

    $pdf->setPaper('A4', 'landscape');

    // Generate filename
    $filename = 'sales-report-' . now()->format('Y-m-d-His') . '.pdf';

    return $pdf->download($filename);
  }
}
