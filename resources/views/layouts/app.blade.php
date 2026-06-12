<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="FinanceHUB - controle de despesas e pagamentos.">
    <title>@yield('title', 'FinanceHUB')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/financehub.css') }}" rel="stylesheet">
</head>
<body>
<div class="app-frame">
    <aside class="app-sidebar">
        <a class="brand-mark" href="{{ route('dashboard') }}" aria-label="FinanceHUB">
            <span class="brand-symbol">FH</span>
            <span class="brand-name">Finance<strong>HUB</strong></span>
        </a>

        <nav class="side-nav" aria-label="Navegação principal">
            <span class="side-nav-label">Navegação</span>
            <a class="side-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-columns-gap"></i><span>Resumo</span>
            </a>
            <a class="side-nav-link {{ request()->routeIs('despesas.*') ? 'active' : '' }}" href="{{ route('despesas.index') }}">
                <i class="bi bi-receipt-cutoff"></i><span>Despesas</span>
            </a>
            <a class="side-nav-link {{ request()->routeIs('pagamentos.*') ? 'active' : '' }}" href="{{ route('pagamentos.index') }}">
                <i class="bi bi-arrow-down-left-square"></i><span>Pagamentos</span>
            </a>
        </nav>

        <div class="sidebar-account">
            @guest
                <span class="side-nav-label">Acesso</span>
                <a class="side-nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i><span>Entrar</span></a>
                <a class="btn btn-accent w-100" href="{{ route('register') }}">Criar conta</a>
            @else
                <div class="account-identity">
                    <span class="account-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                    <span><small>Conectado como</small><strong>{{ auth()->user()->name }}</strong></span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="side-nav-link w-100" type="submit"><i class="bi bi-box-arrow-left"></i><span>Sair</span></button>
                </form>
            @endguest
        </div>
    </aside>

    <div class="app-workspace">
        <header class="app-topbar">
            <span>Controle financeiro</span>
            <span>{{ now()->format('d/m/Y') }}</span>
        </header>

        <main class="app-main">
            <div class="container">
        @if(session('success'))
            <div class="alert app-alert alert-success" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert app-alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            </div>
        @endif
            </div>

            @yield('content')
        </main>

        <footer class="app-footer">
            <span>FinanceHUB &copy; {{ date('Y') }}</span>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
