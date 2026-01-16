<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeesPerShop extends Model
{
    protected $table = 'employees_per_shop';
    public $timestamps = false;
    protected $primaryKey = ['shop_id', 'employee_id'];
    public $incrementing = false;
    
    protected $fillable = [
        'shop_id',
        'employee_id'
    ];
    
    // Relationships
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}