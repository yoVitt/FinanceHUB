@extends('layouts.app')

@section('title', 'Editar despesa | FinanceHUB')

@section('content')
<div class="container page-shell narrow-shell">
    <section class="page-heading">
        <div><span class="section-label">Alterar registro</span><h1>Editar despesa</h1><p>Atualize os dados da despesa selecionada.</p></div>
    </section>
    <section class="form-panel">
        <form method="POST" action="{{ route('despesas.update', $despesa) }}" enctype="multipart/form-data" class="app-form">
            @csrf
            @method('PUT')
            @include('despesas._form', ['submitLabel' => 'Salvar alterações'])
        </form>
    </section>
</div>
@endsection

