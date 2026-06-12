@extends('layouts.app')

@section('title', 'Pagamentos | FinanceHUB')

@section('content')
<div class="container page-shell">
    <section class="page-heading">
        <div>
            <span class="section-label">Registros relacionados</span>
            <h1>Pagamentos</h1>
            <p>Consulte e gerencie os pagamentos cadastrados.</p>
        </div>
        @auth
            <a class="btn btn-accent" href="{{ route('pagamentos.create') }}"><i class="bi bi-plus-lg"></i> Novo pagamento</a>
        @endauth
    </section>

    <form class="filter-bar" method="GET" action="{{ route('pagamentos.index') }}">
        <div class="filter-search">
            <i class="bi bi-search"></i>
            <input name="busca" value="{{ $busca }}" maxlength="100" placeholder="Buscar por despesa ou observação" aria-label="Buscar pagamentos">
        </div>
        <select name="despesa_id" aria-label="Filtrar por despesa">
            <option value="">Todas as despesas</option>
            @foreach($despesasDisponiveis as $despesa)
                <option value="{{ $despesa->id }}" @selected($despesaAtual === $despesa->id)>{{ $despesa->descricao }}</option>
            @endforeach
        </select>
        <button class="btn btn-accent" type="submit">Filtrar</button>
        @if($busca || $despesaAtual)
            <a class="btn btn-ghost" href="{{ route('pagamentos.index') }}">Limpar</a>
        @endif
    </form>

    <section class="content-panel">
        <div class="table-responsive">
            <table class="table app-table align-middle">
                <thead>
                <tr>
                    <th>Despesa</th>
                    <th>Data</th>
                    <th>Valor pago</th>
                    <th>Observações</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse($pagamentos as $pagamento)
                    <tr>
                        <td><a class="row-title" href="{{ route('despesas.show', $pagamento->despesa) }}">{{ $pagamento->despesa->descricao }}</a></td>
                        <td>{{ $pagamento->data_pagamento->format('d/m/Y') }}</td>
                        <td class="money">R$ {{ number_format($pagamento->valor_pago, 2, ',', '.') }}</td>
                        <td>{{ $pagamento->observacoes ?: 'Sem observações' }}</td>
                        <td>
                            <div class="row-actions justify-content-end">
                                <a class="icon-button" href="{{ route('pagamentos.show', $pagamento) }}" aria-label="Ver pagamento" title="Ver"><i class="bi bi-eye"></i></a>
                                @auth
                                    <a class="icon-button" href="{{ route('pagamentos.edit', $pagamento) }}" aria-label="Editar pagamento" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('pagamentos.destroy', $pagamento) }}" onsubmit="return confirm('Excluir este pagamento?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="icon-button icon-button-danger" type="submit" aria-label="Excluir pagamento" title="Excluir"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endauth
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state">Nenhum pagamento encontrado.</div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="pagination-shell">{{ $pagamentos->links() }}</div>
</div>
@endsection
