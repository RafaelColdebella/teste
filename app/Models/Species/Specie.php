<?php

namespace App\Models\Species;

use App\Models\Payments\Payment;
use App\Models\Shared\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Specie extends BaseModel
{
    use HasFactory, SoftDeletes, Filterable;
    
    protected $fillable = [
        'public_id',
        'name',
        'tax',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'deleted_at',
        'id'
    ];

    public function payment() {
        return $this->hasMany(Payment::class);
    }
}
