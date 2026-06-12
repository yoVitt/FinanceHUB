@extends('layouts.app')

@section('title', 'Pagamento | FinanceHUB')

@section('content')
<div class="container page-shell narrow-shell">
    <section class="page-heading">
        <div>
            <span class="section-label">Pagamento #{{ $pagamento->id }}</span>
            <h1>{{ $pagamento->despesa->descricao }}</h1>
            <p>Pagamento vinculado à despesa selecionada.</p>
        </div>
        @auth
            <div class="heading-actions">
                <a class="btn btn-accent" href="{{ route('pagamentos.edit', $pagamento) }}"><i class="bi bi-pencil"></i> Editar</a>
                <form method="POST" action="{{ route('pagamentos.destroy', $pagamento) }}" onsubmit="return confirm('Excluir este pagamento?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger-subtle" type="submit"><i class="bi bi-trash"></i> Excluir</button>
                </form>
            </div>
        @endauth
    </section>

    <section class="detail-grid detail-grid-three">
        <article class="detail-card"><span>Valor pago</span><strong>R$ {{ number_format($pagamento->valor_pago, 2, ',', '.') }}</strong></article>
        <article class="detail-card"><span>Data do pagamento</span><strong>{{ $pagamento->data_pagamento->format('d/m/Y') }}</strong></article>
        <article class="detail-card"><span>Despesa</span><a class="text-link" href="{{ route('despesas.show', $pagamento->despesa) }}">Ver despesa <i class="bi bi-arrow-right"></i></a></article>
    </section>

    <section class="content-panel">
        <div class="panel-heading"><div><h2>Observações</h2><p>Detalhes adicionais do pagamento.</p></div></div>
        <p class="detail-text">{{ $pagamento->observacoes ?: 'Nenhuma observação informada.' }}</p>
    </section>
</div>
@endsection

