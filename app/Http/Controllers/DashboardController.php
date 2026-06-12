<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use App\Models\Pagamento;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard.index', [
            'totalDespesas' => Despesa::count(),
            'valorDespesas' => Despesa::sum('valor'),
            'totalPagamentos' => Pagamento::count(),
            'valorPagamentos' => Pagamento::sum('valor_pago'),
            'despesasRecentes' => Despesa::query()
                ->withSum('pagamentos', 'valor_pago')
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->take(5)
                ->get(),
            'pagamentosRecentes' => Pagamento::query()
                ->with('despesa')
                ->orderByDesc('data_pagamento')
                ->orderByDesc('id')
                ->take(5)
                ->get(),
        ]);
    }
}
