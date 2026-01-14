<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Change this
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable // Extend this
{
    use Notifiable;

    protected $table = 'employee';
    protected $primaryKey = 'employee_id';
    public $timestamps = false;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 
        'phone_number', 'hire_date', 'active_until', 'role', 'is_active'
    ];

    protected $hidden = [
        'password',
    ];
}