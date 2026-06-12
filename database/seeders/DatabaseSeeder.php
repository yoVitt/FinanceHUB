<?php

namespace Database\Seeders;

use App\Models\Despesa;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $aluguel = Despesa::create([
            'descricao' => 'Aluguel do escritório',
            'categoria' => 'Moradia',
            'valor' => 1850.00,
            'vencimento' => now()->addDays(5)->toDateString(),
            'status' => 'Pendente',
        ]);

        $internet = Despesa::create([
            'descricao' => 'Plano de internet',
            'categoria' => 'Assinaturas',
            'valor' => 129.90,
            'vencimento' => now()->addDays(12)->toDateString(),
            'status' => 'Pago',
        ]);

        Despesa::create([
            'descricao' => 'Curso de especialização',
            'categoria' => 'Educação',
            'valor' => 480.00,
            'vencimento' => now()->subDays(3)->toDateString(),
            'status' => 'Atrasado',
        ]);

        $internet->pagamentos()->create([
            'data_pagamento' => now()->subDays(2)->toDateString(),
            'valor_pago' => 129.90,
            'observacoes' => 'Pagamento realizado por transferência.',
        ]);

        $aluguel->pagamentos()->create([
            'data_pagamento' => now()->toDateString(),
            'valor_pago' => 500.00,
            'observacoes' => 'Pagamento parcial registrado para demonstração.',
        ]);
    }
}

