<?php

namespace App\Models;

use App\Services\StockMovementService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_date',
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
        'order_date' => 'date',
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public const STATUS_NEW = 'NEW';
    public const STATUS_CANCEL = 'CANCEL';
    public const STATUS_DIKIRIM = 'DIKIRIM';
    public const STATUS_SELESAI = 'SELESAI';
    public const STATUS_DIKEMBALIKAN = 'DIKEMBALIKAN';

    public function productPackage()
    {
        return $this->belongsTo(ProductPackage::class);
    }

    public static function booted()
    {
        static::creating(function (Order $order) {
            if (is_null($order->price) && $order->product_package_id) {
                $pkg = ProductPackage::find($order->product_package_id);
                if ($pkg) {
                    $order->price = $pkg->price;
                    $order->total_price = ($order->quantity ?? 1) * $pkg->price;
                }
            }

            if (empty($order->status)) {
                $order->status = self::STATUS_NEW;
            }
        });
    }

    /**
     * Change order status following business flow and update stock movements and sales reports.
     *
     * @param string $newStatus
     * @param int|null $performedBy
     * @throws \Exception
     */
    public function changeStatus(string $newStatus, ?int $performedBy = null): void
    {
        $newStatus = strtoupper($newStatus);

        if ($this->status === $newStatus) {
            return;
        }

        DB::transaction(function () use ($newStatus, $performedBy) {
            $this->load('productPackage.product');

            $pkg = $this->productPackage;
            if (! $pkg) {
                throw new \Exception('ProductPackage not found.');
            }

            $product = $pkg->product;
            if (! $product) {
                throw new \Exception('Product not found.');
            }

            $requiredUnits = (int)$pkg->pcs_per_package * (int)$this->quantity;

            if ($newStatus === self::STATUS_DIKIRIM) {
                try {
                    StockMovementService::stockOut(
                        $product->id,
                        'order',
                        $requiredUnits,
                        [
                            'reference_type' => self::class,
                            'reference_id' => $this->id,
                            'created_by' => $performedBy,
                        ]
                    );

                    $this->status = self::STATUS_DIKIRIM;
                    $this->save();
                } catch (\Exception $e) {
                    // insufficient stock -> cancel and create sales report snapshot
                    $this->status = self::STATUS_CANCEL;
                    $this->save();
                    SalesReport::createFromOrder($this, self::STATUS_CANCEL);
                    throw $e;
                }

                return;
            }

            if ($newStatus === self::STATUS_DIKEMBALIKAN) {
                StockMovementService::stockIn(
                    $product->id,
                    'return_from_order',
                    $requiredUnits,
                    [
                        'reference_type' => self::class,
                        'reference_id' => $this->id,
                        'created_by' => $performedBy,
                    ]
                );

                $this->status = self::STATUS_DIKEMBALIKAN;
                $this->save();
                SalesReport::createFromOrder($this, self::STATUS_DIKEMBALIKAN);
                return;
            }

            if ($newStatus === self::STATUS_SELESAI) {
                $this->status = self::STATUS_SELESAI;
                $this->save();
                SalesReport::createFromOrder($this, self::STATUS_SELESAI);
                return;
            }

            if ($newStatus === self::STATUS_CANCEL) {
                $this->status = self::STATUS_CANCEL;
                $this->save();
                SalesReport::createFromOrder($this, self::STATUS_CANCEL);
                return;
            }

            // allow NEW
            if ($newStatus === self::STATUS_NEW) {
                $this->status = self::STATUS_NEW;
                $this->save();
                return;
            }

            throw new \Exception('Invalid status transition: ' . $newStatus);
        });
    }
}
