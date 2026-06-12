@extends('layouts.app')

@section('title', 'Resumo | FinanceHUB')

@section('content')
<div class="container page-shell">
    <section class="page-heading">
        <div>
            <span class="section-label">Controle financeiro</span>
            <h1>Visão financeira</h1>
            <p>Acompanhe os registros gerais de despesas e pagamentos.</p>
        </div>
        @auth
            <div class="heading-actions">
                <a class="btn btn-accent" href="{{ route('despesas.create') }}">
                    <i class="bi bi-plus-lg"></i> Nova despesa
                </a>
                <a class="btn btn-outline-light" href="{{ route('pagamentos.create') }}">
                    <i class="bi bi-plus-lg"></i> Novo pagamento
                </a>
            </div>
        @else
            <a class="btn btn-accent" href="{{ route('login') }}">
                <i class="bi bi-box-arrow-in-right"></i> Entrar para gerenciar
            </a>
        @endauth
    </section>

    <section class="metric-grid" aria-label="Resumo financeiro">
        <article class="metric-card">
            <span class="metric-icon"><i class="bi bi-receipt"></i></span>
            <span class="metric-label">Despesas cadastradas</span>
            <strong>{{ number_format($totalDespesas, 0, ',', '.') }}</strong>
        </article>
        <article class="metric-card">
            <span class="metric-icon"><i class="bi bi-cash-stack"></i></span>
            <span class="metric-label">Valor das despesas</span>
            <strong>R$ {{ number_format($valorDespesas, 2, ',', '.') }}</strong>
        </article>
        <article class="metric-card">
            <span class="metric-icon"><i class="bi bi-credit-card"></i></span>
            <span class="metric-label">Pagamentos registrados</span>
            <strong>{{ number_format($totalPagamentos, 0, ',', '.') }}</strong>
        </article>
        <article class="metric-card">
            <span class="metric-icon"><i class="bi bi-check2-circle"></i></span>
            <span class="metric-label">Valor dos pagamentos</span>
            <strong>R$ {{ number_format($valorPagamentos, 2, ',', '.') }}</strong>
        </article>
    </section>

    <section class="content-panel">
        <div class="panel-heading">
            <div>
                <h2>Despesas recentes</h2>
                <p>Últimos registros adicionados ao sistema.</p>
            </div>
            <a class="text-link" href="{{ route('despesas.index') }}">Ver todas <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="table-responsive">
            <table class="table app-table align-middle">
                <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th>Vencimento</th>
                    <th>Valor</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @forelse($despesasRecentes as $despesa)
                    <tr>
                        <td><a class="row-title" href="{{ route('despesas.show', $despesa) }}">{{ $despesa->descricao }}</a></td>
                        <td>{{ $despesa->categoria }}</td>
                        <td>{{ $despesa->vencimento->format('d/m/Y') }}</td>
                        <td class="money">R$ {{ number_format($despesa->valor, 2, ',', '.') }}</td>
                        <td><x-status :status="$despesa->status_atual" :dias-atraso="$despesa->dias_atraso" /></td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state">Nenhuma despesa cadastrada.</div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="content-panel">
        <div class="panel-heading">
            <div>
                <h2>Pagamentos recentes</h2>
                <p>Últimos pagamentos registrados.</p>
            </div>
            <a class="text-link" href="{{ route('pagamentos.index') }}">Ver todos <i class="bi bi-arrow-right"></i></a>
        </div>

        <div class="table-responsive">
            <table class="table app-table align-middle">
                <thead>
                <tr>
                    <th>Despesa</th>
                    <th>Data</th>
                    <th>Valor pago</th>
                    <th>Observações</th>
                </tr>
                </thead>
                <tbody>
                @forelse($pagamentosRecentes as $pagamento)
                    <tr>
                        <td><a class="row-title" href="{{ route('pagamentos.show', $pagamento) }}">{{ $pagamento->despesa->descricao }}</a></td>
                        <td>{{ $pagamento->data_pagamento->format('d/m/Y') }}</td>
                        <td class="money">R$ {{ number_format($pagamento->valor_pago, 2, ',', '.') }}</td>
                        <td>{{ $pagamento->observacoes ?: 'Sem observações' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4"><div class="empty-state">Nenhum pagamento cadastrado.</div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
