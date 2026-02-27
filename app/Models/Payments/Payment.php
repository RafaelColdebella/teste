<?php

namespace App\Models\Payments;

use App\Models\Shared\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Payment extends BaseModel
{
    use HasFactory, SoftDeletes, Filterable;
    
    protected $fillable = [
        'public_id',
        'value',
        'payment_type',
        'tax',
        'payer_id',
        'receiver_id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'id',
        'deleted_at',
    ];

}
