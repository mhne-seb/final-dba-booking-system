<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    protected $table = 'shop';
    protected $primaryKey = 'shop_id';
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'address',
        'city',
        'phone_number',
        'email',
        'description'
    ];
    
    // Relationships
    public function employees(): HasMany
    {
        return $this->hasMany(EmployeesPerShop::class, 'shop_id');
    }
}