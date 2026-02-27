<?php

namespace App\Models;

use App\Models\Shared\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentFee extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'public_id',
        'payer_id',
        'receiver_id',
        'value',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'id',
        'deleted_at',
    ];
}
