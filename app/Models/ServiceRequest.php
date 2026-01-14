<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequest extends Model
{
    protected $table = 'service_request';
    protected $primaryKey = 'request_id';
    public $timestamps = false;
    
    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'shop_id',
        'service_details_id',
        'status',
        'created_at'
    ];
    
    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
    
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
    
    public function serviceDetails(): BelongsTo
    {
        return $this->belongsTo(ServiceDetails::class, 'service_details_id');
    }
}