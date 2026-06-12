<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('despesas', function (Blueprint $table) {
            $table->id();
            $table->text('descricao');
            $table->string('categoria', 50);
            $table->decimal('valor', 12, 2);
            $table->date('vencimento');
            $table->string('status', 30);
            $table->string('imagem')->nullable();
            $table->timestamps();

            $table->index('categoria');
            $table->index('status');
            $table->index('vencimento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('despesas');
    }
};

