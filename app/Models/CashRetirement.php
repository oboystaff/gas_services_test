<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRetirement extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function manager() {}

    public function retiredBy()
    {
        return $this->belongsTo(User::class, 'retired_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
