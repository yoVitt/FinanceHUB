<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('despesa_id')->constrained('despesas')->cascadeOnDelete();
            $table->date('data_pagamento');
            $table->decimal('valor_pago', 12, 2);
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->index('data_pagamento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};

