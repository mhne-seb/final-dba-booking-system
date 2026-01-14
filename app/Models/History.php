<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class History extends Model
{
    protected $table = 'history';
    protected $primaryKey = 'history_id';
    public $timestamps = false;
    
    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'service_details_id',
        'shop_id',
        'service_request_id',
        'duration',
        'transaction_date',
        'transaction_type',
        'transaction_description'
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
    
    public function serviceDetails(): BelongsTo
    {
        return $this->belongsTo(ServiceDetails::class, 'service_details_id');
    }
    
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id', 'request_id');
    }
}