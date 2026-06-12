@extends('layouts.app')

@section('title', 'Editar pagamento | FinanceHUB')

@section('content')
<div class="container page-shell narrow-shell">
    <section class="page-heading">
        <div><span class="section-label">Alterar registro</span><h1>Editar pagamento</h1><p>Atualize os dados do pagamento selecionado.</p></div>
    </section>
    <section class="form-panel">
        <form method="POST" action="{{ route('pagamentos.update', $pagamento) }}" class="app-form">
            @csrf
            @method('PUT')
            @include('pagamentos._form', ['submitLabel' => 'Salvar alterações'])
        </form>
    </section>
</div>
@endsection

