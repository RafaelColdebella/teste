<?php

namespace App\Models\Shared;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    /**
     * Convert camelCase to snake_case
     */
    public function setAttribute($key, $value)
    {
        parent::setAttribute(Str::snake($key), $value);
    }

    /**
     * Convert camelCase to snake_case
     */
    public function getAttribute($key)
    {
        if(!parent::getAttribute(Str::snake($key))){
            return parent::getAttribute($key);
        }

        return parent::getAttribute(Str::snake($key));
    }

    /**
     * Fill public_id when it's present in fillable and it's empty
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (in_array('public_id', $model->fillable) && empty($model->public_id)) {
                $model->public_id = (string) Str::uuid();
            }
        });
    }
}
