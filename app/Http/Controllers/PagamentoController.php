<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use App\Models\Pagamento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PagamentoController extends Controller
{
    public function index(Request $request): View
    {
        $busca = mb_substr(trim((string) $request->query('busca', '')), 0, 100);
        $despesaId = $request->integer('despesa_id') ?: null;

        $query = Pagamento::query()->with('despesa');

        if ($busca !== '') {
            $query->where(function ($query) use ($busca) {
                $query->where('observacoes', 'like', "%{$busca}%")
                    ->orWhereHas('despesa', fn ($despesas) => $despesas
                        ->where('descricao', 'like', "%{$busca}%"));
            });
        }

        if ($despesaId && Despesa::whereKey($despesaId)->exists()) {
            $query->where('despesa_id', $despesaId);
        } else {
            $despesaId = null;
        }

        return view('pagamentos.index', [
            'pagamentos' => $query->orderByDesc('data_pagamento')->orderByDesc('id')
                ->paginate(10)->withQueryString(),
            'despesasDisponiveis' => Despesa::query()->orderBy('descricao')->get(['id', 'descricao']),
            'busca' => $busca,
            'despesaAtual' => $despesaId,
        ]);
    }

    public function create(Request $request): View
    {
        $despesaId = $request->integer('despesa_id') ?: null;
        $despesasDisponiveis = $this->despesasDisponiveis();
        $despesaSelecionada = $despesasDisponiveis->firstWhere('id', $despesaId);

        return view('pagamentos.create', [
            'pagamento' => new Pagamento([
                'despesa_id' => $despesaSelecionada?->id,
                'data_pagamento' => now()->toDateString(),
            ]),
            'despesasDisponiveis' => $despesasDisponiveis,
            'saldoMaximo' => $despesaSelecionada?->saldo,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePagamento($request);

        $pagamento = DB::transaction(function () use ($validated) {
            $despesa = Despesa::query()->lockForUpdate()->findOrFail($validated['despesa_id']);
            $this->validarLimitePagamento($despesa, (float) $validated['valor_pago']);

            $pagamento = Pagamento::create($validated);
            $despesa->refresh()->sincronizarStatus();

            return $pagamento;
        });

        return redirect()->route('pagamentos.show', $pagamento)
            ->with('success', 'Pagamento cadastrado com sucesso.');
    }

    public function show(Pagamento $pagamento): View
    {
        $pagamento->load('despesa');

        return view('pagamentos.show', compact('pagamento'));
    }

    public function edit(Pagamento $pagamento): View
    {
        $despesasDisponiveis = $this->despesasDisponiveis($pagamento);
        $despesaAtual = $despesasDisponiveis->firstWhere('id', $pagamento->despesa_id);

        return view('pagamentos.edit', [
            'pagamento' => $pagamento,
            'despesasDisponiveis' => $despesasDisponiveis,
            'saldoMaximo' => $despesaAtual ? $despesaAtual->saldo + (float) $pagamento->valor_pago : null,
        ]);
    }

    public function update(Request $request, Pagamento $pagamento): RedirectResponse
    {
        $validated = $this->validatePagamento($request);
        $despesaAnteriorId = $pagamento->despesa_id;

        DB::transaction(function () use ($validated, $pagamento, $despesaAnteriorId) {
            $despesa = Despesa::query()->lockForUpdate()->findOrFail($validated['despesa_id']);
            $this->validarLimitePagamento($despesa, (float) $validated['valor_pago'], $pagamento->id);

            $pagamento->update($validated);
            $despesa->refresh()->sincronizarStatus();

            if ($despesaAnteriorId !== $despesa->id) {
                Despesa::find($despesaAnteriorId)?->sincronizarStatus();
            }
        });

        return redirect()->route('pagamentos.show', $pagamento)
            ->with('success', 'Pagamento atualizado com sucesso.');
    }

    public function destroy(Pagamento $pagamento): RedirectResponse
    {
        $despesa = $pagamento->despesa;

        DB::transaction(function () use ($pagamento, $despesa) {
            $pagamento->delete();
            $despesa->refresh()->sincronizarStatus();
        });

        return redirect()->route('pagamentos.index')
            ->with('success', 'Pagamento excluído com sucesso.');
    }

    private function validatePagamento(Request $request): array
    {
        return $request->validate([
            'despesa_id' => ['required', 'integer', 'exists:despesas,id'],
            'data_pagamento' => ['required', 'date'],
            'valor_pago' => ['required', 'numeric', 'min:0.01', 'max:999999999.99'],
            'observacoes' => ['nullable', 'string', 'max:3000'],
        ], [
            'required' => 'Este campo é obrigatório.',
            'integer' => 'Selecione uma despesa válida.',
            'exists' => 'A despesa selecionada não existe.',
            'date' => 'Informe uma data válida.',
            'numeric' => 'Informe um valor numérico válido.',
            'min' => 'Informe um valor igual ou maior que :min.',
            'max' => 'Informe um valor igual ou menor que :max.',
            'string' => 'Informe um texto válido.',
        ]);
    }

    private function despesasDisponiveis(?Pagamento $pagamento = null)
    {
        return Despesa::query()
            ->withSum('pagamentos', 'valor_pago')
            ->orderBy('descricao')
            ->get()
            ->filter(fn (Despesa $despesa) => $despesa->id === $pagamento?->despesa_id
                || ($despesa->status_atual !== 'Cancelado' && $despesa->saldo > 0))
            ->values();
    }

    private function validarLimitePagamento(Despesa $despesa, float $valorPago, ?int $ignorarPagamentoId = null): void
    {
        if ($despesa->getRawOriginal('status') === 'Cancelado') {
            throw ValidationException::withMessages([
                'despesa_id' => 'Não é possível registrar pagamentos para uma despesa cancelada.',
            ]);
        }

        $totalPago = (float) $despesa->pagamentos()
            ->when($ignorarPagamentoId, fn ($query) => $query->where('id', '!=', $ignorarPagamentoId))
            ->sum('valor_pago');
        $saldoDisponivel = max((float) $despesa->valor - $totalPago, 0);

        if (round($valorPago, 2) > round($saldoDisponivel, 2)) {
            throw ValidationException::withMessages([
                'valor_pago' => 'O pagamento não pode ultrapassar o saldo disponível de R$ '
                    .number_format($saldoDisponivel, 2, ',', '.').'.',
            ]);
        }
    }
}
