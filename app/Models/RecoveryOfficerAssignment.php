<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoveryOfficerAssignment extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function recoveryOfficer()
    {
        return $this->belongsTo(RecoveryOfficer::class, 'discovery_officer_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
