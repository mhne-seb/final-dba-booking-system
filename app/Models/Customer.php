<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;
    public $incrementing = true;
    
    
    protected $fillable = [
        'name', 
        'phone_number', 
        'email', 
        'created_since'
    ];
    
    // Relationships
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'customer_id');
    }
    
    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class, 'customer_id');
    }
}