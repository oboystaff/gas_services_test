<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $casts = ['community_id' => 'array'];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'customer_id', 'customer_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'customer_id', 'customer_id');
    }

    public function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $routine) {
            $routine->customer_id =  $routine->generateCustomerId();
        });
    }


    public function generateCustomerId()
    {
        $lastCustomer = self::where('customer_id', 'like', 'MG%')
            ->orderByRaw("CAST(SUBSTRING(customer_id, 3) AS UNSIGNED) DESC")
            ->first();

        $lastNumber = $lastCustomer
            ? intval(substr($lastCustomer->customer_id, 2))
            : 0;

        $nextNumber = $lastNumber + 1;

        do {
            $nextId = 'MG' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $exists = self::where('customer_id', $nextId)->exists();
            $nextNumber++;
        } while ($exists);

        return $nextId;
    }
}
