<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InSchedule extends Model
{
    protected $table = 'in_schedule';
    protected $primaryKey = 'in_schedule_id';
    public $timestamps = false;
    
    protected $fillable = [
        'request_id',
        'billing_amount',
        'date_started',
        'date_ended',
        'progress'
    ];
    
    // Relationships
    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id', 'request_id');
    }
}