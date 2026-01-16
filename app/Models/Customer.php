<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';

    // Define the relationship: One Customer has Many Vehicles
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'customer_id');
    }
}