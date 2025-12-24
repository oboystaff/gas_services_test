<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class, 'driver_id');
    }

    public function delivery()
    {
        return $this->hasMany(GasRequest::class, 'driver_assigned')->where('status', 'Completed');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
