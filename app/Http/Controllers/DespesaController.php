<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DespesaController extends Controller
{
    public function index(Request $request): View
    {
        $busca = mb_substr(trim((string) $request->query('busca', '')), 0, 100);
        $categoria = $request->query('categoria');
        $status = $request->query('status');

        $query = Despesa::query()->withCount('pagamentos')->withSum('pagamentos', 'valor_pago');

        if ($busca !== '') {
            $query->where('descricao', 'like', "%{$busca}%");
        }

        if (in_array($categoria, Despesa::CATEGORIAS, true)) {
            $query->where('categoria', $categoria);
        } else {
            $categoria = null;
        }

        if (in_array($status, Despesa::STATUS, true)) {
            $somaPagamentos = '(SELECT COALESCE(SUM(valor_pago), 0) FROM pagamentos WHERE pagamentos.despesa_id = despesas.id)';

            match ($status) {
                'Cancelado' => $query->where('status', 'Cancelado'),
                'Pago' => $query->where('status', '!=', 'Cancelado')
                    ->whereRaw("$somaPagamentos >= despesas.valor"),
                'Atrasado' => $query->where('status', '!=', 'Cancelado')
                    ->whereDate('vencimento', '<', today())
                    ->whereRaw("$somaPagamentos < despesas.valor"),
                'Pendente' => $query->where('status', '!=', 'Cancelado')
                    ->whereDate('vencimento', '>=', today())
                    ->whereRaw("$somaPagamentos < despesas.valor"),
            };
        } else {
            $status = null;
        }

        return view('despesas.index', [
            'despesas' => $query->orderBy('vencimento')->orderBy('id')->paginate(10)->withQueryString(),
            'categorias' => Despesa::CATEGORIAS,
            'statusDisponiveis' => Despesa::STATUS,
            'busca' => $busca,
            'categoriaAtual' => $categoria,
            'statusAtual' => $status,
        ]);
    }

    public function create(): View
    {
        return view('despesas.create', [
            'despesa' => new Despesa(['status' => 'Pendente']),
            'categorias' => Despesa::CATEGORIAS,
            'statusDisponiveis' => Despesa::STATUS_EDITAVEIS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateDespesa($request);
        $validated['imagem'] = $this->storeImagem($request);

        $despesa = Despesa::create($validated);

        return redirect()->route('despesas.show', $despesa)
            ->with('success', 'Despesa cadastrada com sucesso.');
    }

    public function show(Despesa $despesa): View
    {
        $despesa->load(['pagamentos' => fn ($query) => $query
            ->orderByDesc('data_pagamento')
            ->orderByDesc('id')]);

        return view('despesas.show', compact('despesa'));
    }

    public function edit(Despesa $despesa): View
    {
        return view('despesas.edit', [
            'despesa' => $despesa,
            'categorias' => Despesa::CATEGORIAS,
            'statusDisponiveis' => Despesa::STATUS_EDITAVEIS,
        ]);
    }

    public function update(Request $request, Despesa $despesa): RedirectResponse
    {
        $validated = $this->validateDespesa($request);
        $totalPago = (float) $despesa->pagamentos()->sum('valor_pago');

        if ((float) $validated['valor'] < $totalPago) {
            throw ValidationException::withMessages([
                'valor' => 'O valor da despesa não pode ser menor que o total já pago de R$ '
                    .number_format($totalPago, 2, ',', '.').'.',
            ]);
        }

        if ($request->hasFile('imagem')) {
            if ($despesa->imagem) {
                Storage::disk('public')->delete($despesa->imagem);
            }

            $validated['imagem'] = $this->storeImagem($request);
        }

        $despesa->update($validated);
        $despesa->refresh()->sincronizarStatus();

        return redirect()->route('despesas.show', $despesa)
            ->with('success', 'Despesa atualizada com sucesso.');
    }

    public function destroy(Despesa $despesa): RedirectResponse
    {
        if ($despesa->imagem) {
            Storage::disk('public')->delete($despesa->imagem);
        }

        $despesa->delete();

        return redirect()->route('despesas.index')
            ->with('success', 'Despesa e seus pagamentos foram excluídos.');
    }

    private function validateDespesa(Request $request): array
    {
        return $request->validate([
            'descricao' => ['required', 'string', 'min:3', 'max:2000'],
            'categoria' => ['required', Rule::in(Despesa::CATEGORIAS)],
            'valor' => ['required', 'numeric', 'min:0.01', 'max:999999999.99'],
            'vencimento' => ['required', 'date'],
            'status' => ['required', Rule::in(Despesa::STATUS_EDITAVEIS)],
            'imagem' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], $this->messages());
    }

    private function storeImagem(Request $request): ?string
    {
        return $request->hasFile('imagem')
            ? $request->file('imagem')->store('comprovantes', 'public')
            : null;
    }

    private function messages(): array
    {
        return [
            'required' => 'Este campo é obrigatório.',
            'string' => 'Informe um texto válido.',
            'min' => 'Informe um valor igual ou maior que :min.',
            'max' => 'Informe um valor igual ou menor que :max.',
            'numeric' => 'Informe um valor numérico válido.',
            'date' => 'Informe uma data válida.',
            'in' => 'Selecione uma opção válida.',
            'image' => 'Envie um arquivo de imagem válido.',
            'mimes' => 'O comprovante deve ser JPG, JPEG, PNG ou WEBP.',
        ];
    }
}
