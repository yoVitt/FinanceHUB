@extends('layouts.app')

@section('title', 'Nova despesa | FinanceHUB')

@section('content')
<div class="container page-shell narrow-shell">
    <section class="page-heading">
        <div><span class="section-label">Novo registro</span><h1>Cadastrar despesa</h1><p>Preencha os dados principais da despesa.</p></div>
    </section>
    <section class="form-panel">
        <form method="POST" action="{{ route('despesas.store') }}" enctype="multipart/form-data" class="app-form">
            @csrf
            @include('despesas._form', ['submitLabel' => 'Cadastrar despesa'])
        </form>
    </section>
</div>
@endsection

