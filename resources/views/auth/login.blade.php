@extends('layouts.app')

@section('title', 'Entrar | FinanceHUB')

@section('content')
<div class="container auth-shell">
    <section class="auth-card">
        <div class="auth-heading">
            <span class="auth-icon"><i class="bi bi-box-arrow-in-right"></i></span>
            <div>
                <h1>Entrar</h1>
                <p>Acesse sua conta para gerenciar os registros.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="app-form">
            @csrf
            <div>
                <label class="form-label" for="email">E-mail</label>
                <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email"
                       value="{{ old('email') }}" autocomplete="email" required autofocus>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div>
                <label class="form-label" for="password">Senha</label>
                <input class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                       type="password" autocomplete="current-password" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <label class="form-check">
                <input class="form-check-input" name="remember" type="checkbox" value="1" @checked(old('remember'))>
                <span class="form-check-label">Manter sessão ativa</span>
            </label>

            <button class="btn btn-accent btn-lg w-100" type="submit">Entrar</button>
        </form>

        <p class="auth-footer">Ainda não possui conta? <a href="{{ route('register') }}">Criar conta</a></p>
    </section>
</div>
@endsection

