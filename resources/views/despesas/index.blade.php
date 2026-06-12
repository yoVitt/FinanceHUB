@extends('layouts.app')

@section('title', 'Despesas | FinanceHUB')

@section('content')
<div class="container page-shell">
    <section class="page-heading">
        <div>
            <span class="section-label">Registros principais</span>
            <h1>Despesas</h1>
            <p>Consulte e gerencie as despesas cadastradas.</p>
        </div>
        @auth
            <a class="btn btn-accent" href="{{ route('despesas.create') }}"><i class="bi bi-plus-lg"></i> Nova despesa</a>
        @endauth
    </section>

    <form class="filter-bar" method="GET" action="{{ route('despesas.index') }}">
        <div class="filter-search">
            <i class="bi bi-search"></i>
            <input name="busca" value="{{ $busca }}" maxlength="100" placeholder="Buscar pela descrição" aria-label="Buscar despesas">
        </div>
        <select name="categoria" aria-label="Filtrar por categoria">
            <option value="">Todas as categorias</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria }}" @selected($categoriaAtual === $categoria)>{{ $categoria }}</option>
            @endforeach
        </select>
        <select name="status" aria-label="Filtrar por status">
            <option value="">Todos os status</option>
            @foreach($statusDisponiveis as $status)
                <option value="{{ $status }}" @selected($statusAtual === $status)>{{ $status }}</option>
            @endforeach
        </select>
        <button class="btn btn-accent" type="submit">Filtrar</button>
        @if($busca || $categoriaAtual || $statusAtual)
            <a class="btn btn-ghost" href="{{ route('despesas.index') }}">Limpar</a>
        @endif
    </form>

    <section class="content-panel">
        <div class="table-responsive">
            <table class="table app-table align-middle">
                <thead>
                <tr>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th>Vencimento</th>
                    <th>Valor</th>
                    <th>Saldo</th>
                    <th>Status</th>
                    <th>Pagamentos</th>
                    <th class="text-end">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse($despesas as $despesa)
                    <tr>
                        <td><a class="row-title" href="{{ route('despesas.show', $despesa) }}">{{ $despesa->descricao }}</a></td>
                        <td>{{ $despesa->categoria }}</td>
                        <td>{{ $despesa->vencimento->format('d/m/Y') }}</td>
                        <td class="money">R$ {{ number_format($despesa->valor, 2, ',', '.') }}</td>
                        <td class="money">R$ {{ number_format($despesa->saldo, 2, ',', '.') }}</td>
                        <td><x-status :status="$despesa->status_atual" :dias-atraso="$despesa->dias_atraso" /></td>
                        <td>{{ $despesa->pagamentos_count }}</td>
                        <td>
                            <div class="row-actions justify-content-end">
                                <a class="icon-button" href="{{ route('despesas.show', $despesa) }}" aria-label="Ver despesa" title="Ver"><i class="bi bi-eye"></i></a>
                                @auth
                                    <a class="icon-button" href="{{ route('despesas.edit', $despesa) }}" aria-label="Editar despesa" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <form method="POST" action="{{ route('despesas.destroy', $despesa) }}" onsubmit="return confirm('Excluir esta despesa e todos os seus pagamentos?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="icon-button icon-button-danger" type="submit" aria-label="Excluir despesa" title="Excluir"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endauth
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8"><div class="empty-state">Nenhuma despesa encontrada.</div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="pagination-shell">{{ $despesas->links() }}</div>
</div>
@endsection
