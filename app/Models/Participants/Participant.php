<?php

namespace App\Models\Participants;

use App\Models\Shared\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Participant extends BaseModel
{
    use HasFactory, SoftDeletes, Filterable;
    
    protected $fillable = [
        'public_id',
        'name',
        'email',
        'document',
        'type',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'id',
        'deleted_at',
    ];
}
