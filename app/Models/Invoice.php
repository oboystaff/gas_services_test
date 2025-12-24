<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function gasRequest()
    {
        return $this->belongsTo(GasRequest::class, 'request_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function (self $routine) {
    //         $routine->invoice_no =  $routine->generateInvoiceNo();
    //     });
    // }

    public function generateInvoiceNo()
    {
        $invoice_no = rand(10000000, 99999999);

        while (self::where('invoice_no', $invoice_no)->exists()) {
            $this->generateInvoiceNo();
        }

        return $invoice_no;
    }
}
