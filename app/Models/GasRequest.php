<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class GasRequest extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $casts = ['community_id' => 'array'];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function driverAssigned()
    {
        return $this->belongsTo(Driver::class, 'driver_assigned');
    }

    public function deliveryBranch()
    {
        return $this->belongsTo(Community::class, 'delivery_branch');
    }

    public function community()
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateAttachmentName()
    {
        $fileName = Str::random();
        while (self::where('attachment', $fileName)->first()) {
            self::generateAttachmentName();
        }

        return $fileName;
    }
}
