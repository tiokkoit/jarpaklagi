<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'reason',
        'quantity',
        'stock_before',
        'stock_after',
        'reference_type',
        'reference_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'stock_before' => 'integer',
        'stock_after' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke User (Admin/Staff)
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi Polymorphic
    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function getTypeTextAttribute(): string
{
    return $this->type === 'in' ? 'IN' : 'OUT';
}

/**
 * Get formatted reason
 */
public function getReasonTextAttribute(): string
{
    $reasons = [
        // IN
        'restock' => 'Restock',
        'return_from_order' => 'Return Order',
        'return_from_damage' => 'Return Damage',
        'adjustment_in' => 'Adjustment In',
        'initial_stock' => 'Initial Stock',
        
        // OUT
        'order' => 'Order Out',
        'damaged' => 'Damaged',
        'expired' => 'Expired',
        'lost' => 'Lost',
        'sample' => 'Sample',
        'adjustment_out' => 'Adjustment Out',
    ];

    return $reasons[$this->reason] ?? $this->reason;
}

/**
 * Get formatted quantity with +/- sign
 */
public function getFormattedQuantityAttribute(): string
{
    $sign = $this->type === 'in' ? '+' : '-';
    return $sign . number_format($this->quantity);
}
}
