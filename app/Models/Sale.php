<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function gasRequest()
    {
        return $this->belongsTo(GasRequest::class, 'request_id');
    }

    public function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (self $routine) {
            $routine->transaction_id =  $routine->generateTransactionId();
        });
    }

    public function generateTransactionId()
    {
        $transaction_id = rand(100000, 999999);

        while (self::where('transaction_id', $transaction_id)->exists()) {
            $this->generateTransactionId();
        }

        return $transaction_id;
    }
}
