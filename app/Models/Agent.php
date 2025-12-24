<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function delivery()
    {
        return $this->hasMany(GasRequest::class, 'agent_assigned')->where('status', 'Completed');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
