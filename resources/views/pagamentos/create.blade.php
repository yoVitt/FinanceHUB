@extends('layouts.app')

@section('title', 'Novo pagamento | FinanceHUB')

@section('content')
<div class="container page-shell narrow-shell">
    <section class="page-heading">
        <div><span class="section-label">Novo registro</span><h1>Cadastrar pagamento</h1><p>Vincule o pagamento a uma despesa existente.</p></div>
    </section>
    <section class="form-panel">
        <form method="POST" action="{{ route('pagamentos.store') }}" class="app-form">
            @csrf
            @include('pagamentos._form', ['submitLabel' => 'Cadastrar pagamento'])
        </form>
    </section>
</div>
@endsection

