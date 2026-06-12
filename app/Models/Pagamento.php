<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pagamento extends Model
{
    protected $fillable = [
        'despesa_id',
        'data_pagamento',
        'valor_pago',
        'observacoes',
    ];

    protected $casts = [
        'data_pagamento' => 'date',
        'valor_pago' => 'decimal:2',
    ];

    public function despesa(): BelongsTo
    {
        return $this->belongsTo(Despesa::class);
    }
}
