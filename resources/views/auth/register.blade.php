@extends('layouts.app')

@section('title', 'Criar conta | FinanceHUB')

@section('content')
<div class="container auth-shell">
    <section class="auth-card">
        <div class="auth-heading">
            <span class="auth-icon"><i class="bi bi-person-plus"></i></span>
            <div>
                <h1>Criar conta</h1>
                <p>Cadastre-se para inserir, editar e excluir registros.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('register.store') }}" class="app-form">
            @csrf
            <div>
                <label class="form-label" for="name">Nome</label>
                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                       value="{{ old('name') }}" autocomplete="name" required autofocus>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="form-label" for="email">E-mail</label>
                <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email"
                       value="{{ old('email') }}" autocomplete="email" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="form-label" for="password">Senha</label>
                <input class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                       type="password" autocomplete="new-password" required>
                <div class="form-hint">Use pelo menos 8 caracteres.</div>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="form-label" for="password_confirmation">Confirmar senha</label>
                <input class="form-control" id="password_confirmation" name="password_confirmation"
                       type="password" autocomplete="new-password" required>
            </div>

            <button class="btn btn-accent btn-lg w-100" type="submit">Criar conta</button>
        </form>

        <p class="auth-footer">Já possui conta? <a href="{{ route('login') }}">Entrar</a></p>
    </section>
</div>
@endsection

