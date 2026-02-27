<?php

namespace App\Models\Payments;

use App\Models\Participants\Participant;
use App\Models\Shared\BaseModel;
use App\Models\Species\Specie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Payment extends BaseModel
{
    use HasFactory, SoftDeletes, Filterable;
    
    protected $fillable = [
        'public_id',
        'specie_id',
        'value',
        'status',
        'transaction_tax',
        'paid_by',
        'received_by',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'id',
        'deleted_at'
    ];

    public function specie() {
        return $this->belongsTo(Specie::class);
    }

    public function receiver() {
        return $this->belongsTo(Participant::class, 'received_by');
    }

    public function payer() {
        return $this->belongsTo(Participant::class, 'paid_by');
    }
}
