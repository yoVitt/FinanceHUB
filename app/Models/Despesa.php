<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Despesa extends Model
{
    public const CATEGORIAS = [
        'Moradia',
        'Alimentação',
        'Transporte',
        'Saúde',
        'Educação',
        'Lazer',
        'Assinaturas',
        'Impostos',
        'Outros',
    ];

    public const STATUS = [
        'Pendente',
        'Pago',
        'Atrasado',
        'Cancelado',
    ];

    public const STATUS_EDITAVEIS = [
        'Pendente',
        'Cancelado',
    ];

    protected $fillable = [
        'descricao',
        'categoria',
        'valor',
        'vencimento',
        'status',
        'imagem',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'vencimento' => 'date',
    ];

    public function pagamentos(): HasMany
    {
        return $this->hasMany(Pagamento::class);
    }

    public function getImagemUrlAttribute(): ?string
    {
        return $this->imagem ? Storage::disk('public')->url($this->imagem) : null;
    }

    public function getTotalPagoAttribute(): float
    {
        if ($this->relationLoaded('pagamentos')) {
            return (float) $this->pagamentos->sum('valor_pago');
        }

        if (isset($this->attributes['pagamentos_sum_valor_pago'])) {
            return (float) $this->attributes['pagamentos_sum_valor_pago'];
        }

        return (float) $this->pagamentos()->sum('valor_pago');
    }

    public function getSaldoAttribute(): float
    {
        return max((float) $this->valor - $this->total_pago, 0);
    }

    public function getDiasAtrasoAttribute(): int
    {
        if ($this->getRawOriginal('status') === 'Cancelado' || $this->saldo <= 0 || ! $this->vencimento->isPast()) {
            return 0;
        }

        return $this->vencimento->startOfDay()->diffInDays(now()->startOfDay());
    }

    public function getStatusAtualAttribute(): string
    {
        if ($this->getRawOriginal('status') === 'Cancelado') {
            return 'Cancelado';
        }

        if ($this->saldo <= 0) {
            return 'Pago';
        }

        return $this->dias_atraso > 0 ? 'Atrasado' : 'Pendente';
    }

    public function sincronizarStatus(): void
    {
        if ($this->status !== $this->status_atual) {
            $this->updateQuietly(['status' => $this->status_atual]);
        }
    }
}
