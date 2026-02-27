<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class MonetaryTenThousand implements CastsAttributes
{
    /**
     * Converte o valor do banco de dados para uso no modelo.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return float
     */
    public function get($model, $key, $value, $attributes)
    {
        return round(number_format($value / 1000000, 6, '.', ''), 2);
    }

    /**
     * Prepara o valor do modelo para ser armazenado no banco de dados.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    public function set($model, $key, $value, $attributes)
    {
        return intval(round($value * 1000000));
    }
}
