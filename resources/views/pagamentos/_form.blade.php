@if($errors->any())
    <div class="form-errors" role="alert">
        <strong>Revise os campos informados.</strong>
        <ul>
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="form-grid">
    <div class="form-grid-full">
        <label class="form-label" for="despesa_id">Despesa vinculada</label>
        <select class="form-select @error('despesa_id') is-invalid @enderror" id="despesa_id" name="despesa_id" required>
            <option value="">Selecione uma despesa</option>
            @foreach($despesasDisponiveis as $despesa)
                <option value="{{ $despesa->id }}" @selected((int) old('despesa_id', $pagamento->despesa_id) === $despesa->id)>
                    {{ $despesa->descricao }} — saldo R$ {{ number_format($despesa->saldo + ($pagamento->exists && $pagamento->despesa_id === $despesa->id ? (float) $pagamento->valor_pago : 0), 2, ',', '.') }}
                </option>
            @endforeach
        </select>
        @error('despesa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if($despesasDisponiveis->isEmpty())
            <div class="form-hint">Não existem despesas com saldo disponível para pagamento.</div>
        @endif
    </div>

    <div>
        <label class="form-label" for="data_pagamento">Data do pagamento</label>
        <input class="form-control @error('data_pagamento') is-invalid @enderror" id="data_pagamento" name="data_pagamento"
               type="date" value="{{ old('data_pagamento', $pagamento->data_pagamento?->format('Y-m-d')) }}" required>
        @error('data_pagamento')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div>
        <label class="form-label" for="valor_pago">Valor pago</label>
        <div class="input-group">
            <span class="input-group-text">R$</span>
            <input class="form-control @error('valor_pago') is-invalid @enderror" id="valor_pago" name="valor_pago"
                   type="number" min="0.01" max="999999999.99" step="0.01"
                   value="{{ old('valor_pago', $pagamento->valor_pago) }}" required>
            @error('valor_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        @if($saldoMaximo !== null)
            <div class="form-hint">Valor máximo para a despesa selecionada: R$ {{ number_format($saldoMaximo, 2, ',', '.') }}.</div>
        @else
            <div class="form-hint">O pagamento não pode ultrapassar o saldo da despesa.</div>
        @endif
    </div>

    <div class="form-grid-full">
        <label class="form-label" for="observacoes">Observações</label>
        <textarea class="form-control @error('observacoes') is-invalid @enderror" id="observacoes" name="observacoes"
                  rows="5" maxlength="3000" placeholder="Informações adicionais sobre o pagamento">{{ old('observacoes', $pagamento->observacoes) }}</textarea>
        @error('observacoes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="form-actions">
    <a class="btn btn-ghost" href="{{ $pagamento->exists ? route('pagamentos.show', $pagamento) : route('pagamentos.index') }}">Cancelar</a>
    <button class="btn btn-accent" type="submit" @disabled($despesasDisponiveis->isEmpty())>
        <i class="bi bi-check-lg"></i> {{ $submitLabel }}
    </button>
</div>
