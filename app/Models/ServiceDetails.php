<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceDetails extends Model
{
    protected $table = 'service_details';
    protected $primaryKey = 'service_details_id';
    public $timestamps = false;
    
    protected $fillable = [
        'preferred_date',
        'preferred_time',
        'service_type',
        'description'
    ];
}