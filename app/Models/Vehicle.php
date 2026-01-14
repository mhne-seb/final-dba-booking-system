<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    protected $table = 'vehicle';
    protected $primaryKey = 'vehicle_id';
    public $incrementing = true;
    public $timestamps = false;
    
    protected $fillable = [
        'customer_id',
        'vehicle_type',
        'brand',
        'model',
        'plate_number'
    ];
    
    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}