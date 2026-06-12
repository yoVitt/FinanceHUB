@extends('layouts.app')

@section('title', 'Despesa | FinanceHUB')

@section('content')
<div class="container page-shell">
    <section class="page-heading">
        <div>
            <span class="section-label">{{ $despesa->categoria }}</span>
            <h1>{{ $despesa->descricao }}</h1>
            <p>Cadastrada em {{ $despesa->created_at->format('d/m/Y') }}.</p>
        </div>
        <div class="heading-actions">
            @auth
                @if($despesa->status_atual !== 'Cancelado' && $despesa->saldo > 0)
                <a class="btn btn-accent" href="{{ route('pagamentos.create', ['despesa_id' => $despesa->id]) }}"><i class="bi bi-plus-lg"></i> Registrar pagamento</a>
                @endif
                <a class="btn btn-ghost" href="{{ route('despesas.edit', $despesa) }}"><i class="bi bi-pencil"></i> Editar</a>
                <form method="POST" action="{{ route('despesas.destroy', $despesa) }}" onsubmit="return confirm('Excluir esta despesa e todos os seus pagamentos?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger-subtle" type="submit"><i class="bi bi-trash"></i> Excluir</button>
                </form>
            @endauth
        </div>
    </section>

    <section class="detail-grid detail-grid-five">
        <article class="detail-card"><span>Valor</span><strong>R$ {{ number_format($despesa->valor, 2, ',', '.') }}</strong></article>
        <article class="detail-card"><span>Vencimento</span><strong>{{ $despesa->vencimento->format('d/m/Y') }}</strong></article>
        <article class="detail-card"><span>Total pago</span><strong>R$ {{ number_format($despesa->total_pago, 2, ',', '.') }}</strong></article>
        <article class="detail-card"><span>Saldo restante</span><strong>R$ {{ number_format($despesa->saldo, 2, ',', '.') }}</strong></article>
        <article class="detail-card"><span>Status</span><strong><x-status :status="$despesa->status_atual" :dias-atraso="$despesa->dias_atraso" /></strong></article>
    </section>

    @if($despesa->imagem_url)
        <section class="content-panel">
            <div class="panel-heading"><div><h2>Comprovante</h2><p>Imagem vinculada a esta despesa.</p></div></div>
            <a class="receipt-preview" href="{{ $despesa->imagem_url }}" target="_blank" rel="noopener">
                <img src="{{ $despesa->imagem_url }}" alt="Comprovante da despesa {{ $despesa->descricao }}">
            </a>
        </section>
    @endif

    <section class="content-panel">
        <div class="panel-heading">
            <div><h2>Pagamentos vinculados</h2><p>Registros relacionados a esta despesa.</p></div>
        </div>
        <div class="table-responsive">
            <table class="table app-table align-middle">
                <thead><tr><th>Data</th><th>Valor pago</th><th>Observações</th><th class="text-end">Ações</th></tr></thead>
                <tbody>
                @forelse($despesa->pagamentos as $pagamento)
                    <tr>
                        <td>{{ $pagamento->data_pagamento->format('d/m/Y') }}</td>
                        <td class="money">R$ {{ number_format($pagamento->valor_pago, 2, ',', '.') }}</td>
                        <td>{{ $pagamento->observacoes ?: 'Sem observações' }}</td>
                        <td>
                            <div class="row-actions justify-content-end">
                                <a class="icon-button" href="{{ route('pagamentos.show', $pagamento) }}" aria-label="Ver pagamento"><i class="bi bi-eye"></i></a>
                                @auth
                                    <a class="icon-button" href="{{ route('pagamentos.edit', $pagamento) }}" aria-label="Editar pagamento"><i class="bi bi-pencil"></i></a>
                                @endauth
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4"><div class="empty-state">Nenhum pagamento vinculado.</div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
