<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class StockMovementPdfController extends Controller
{
  public function export(Request $request)
  {
    // Get filter parameters
    $type = $request->get('type');
    $startDate = $request->get('start_date');
    $endDate = $request->get('end_date');

    // Build query
    $query = StockMovement::with(['product', 'createdBy'])
      ->orderBy('created_at', 'desc');

    // Apply type filter
    if ($type && $type !== 'all') {
      $query->where('type', $type); // 'in' or 'out'
    }

    // Apply date range filter
    if ($startDate && $endDate) {
      $query->whereBetween('created_at', [
        $startDate . ' 00:00:00',
        $endDate . ' 23:59:59'
      ]);
    }

    $movements = $query->get();

    // Summary Statistics
    $totalIn = $movements->where('type', 'in')->sum('quantity');
    $totalOut = $movements->where('type', 'out')->sum('quantity');

    // Find most active product
    $mostActiveProduct = $movements->groupBy('product_id')
      ->sortByDesc(function ($group) {
        return $group->count();
      })->first();

    $mostActiveProductName = $mostActiveProduct ? $mostActiveProduct->first()->product->name : '-';
    $mostActiveProductCount = $mostActiveProduct ? $mostActiveProduct->count() : 0;

    // Prepare date range text
    $dateRange = null;
    if ($startDate && $endDate) {
      $dateRange = \Carbon\Carbon::parse($startDate)->format('d M Y') . ' - ' . \Carbon\Carbon::parse($endDate)->format('d M Y');
    }

    // Generate PDF
    $pdf = Pdf::loadView('pdf.stock-movements', [
      'movements' => $movements,
      'dateRange' => $dateRange,
      'title' => 'Laporan Mutasi Stok',
      'summary' => [
        'total_in' => $totalIn,
        'total_out' => $totalOut,
        'most_active_product' => $mostActiveProductName,
        'most_active_count' => $mostActiveProductCount,
        'total_transactions' => $movements->count(),
      ]
    ]);

    $pdf->setPaper('A4', 'landscape');

    // Generate filename
    $filename = 'stock-movements-' . now()->format('Y-m-d-His') . '.pdf';

    return $pdf->download($filename);
  }
}
