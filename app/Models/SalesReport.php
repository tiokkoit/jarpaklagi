<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReport extends Model
{
    use HasFactory;

    protected $table = 'sales_reports';

    protected $fillable = [
        'report_date',
        'customer_name',
        'customer_address',
        'phone',
        'kecamatan',
        'kota',
        'province',
        'product_package_id',
        'quantity',
        'price',
        'total_price',
        'status',
        'payment',
    ];

    protected $casts = [
        'report_date' => 'date',
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function productPackage()
    {
        return $this->belongsTo(ProductPackage::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Create a sales report snapshot from an Order instance.
     */
    public static function createFromOrder(Order $order, string $finalStatus): self
    {
        return self::create([
            'report_date' => $order->order_date ?? now()->toDateString(),
            'customer_name' => $order->customer_name,
            'customer_address' => $order->customer_address,
            'phone' => $order->phone,
            'kecamatan' => $order->kecamatan,
            'kota' => $order->kota,
            'province' => $order->province ?? null,
            'product_package_id' => $order->product_package_id,
            'quantity' => $order->quantity,
            'price' => $order->price,
            'total_price' => $order->total_price,
            'status' => $finalStatus,
            'payment' => $order->payment,
        ]);
    }
}
