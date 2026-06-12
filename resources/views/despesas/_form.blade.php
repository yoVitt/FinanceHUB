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
        <label class="form-label" for="descricao">Descrição</label>
        <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao"
                  rows="4" maxlength="2000" required>{{ old('descricao', $despesa->descricao) }}</textarea>
        @error('descricao')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div>
        <label class="form-label" for="categoria">Categoria</label>
        <select class="form-select @error('categoria') is-invalid @enderror" id="categoria" name="categoria" required>
            <option value="">Selecione</option>
            @foreach($categorias as $categoria)
                <option value="{{ $categoria }}" @selected(old('categoria', $despesa->categoria) === $categoria)>{{ $categoria }}</option>
            @endforeach
        </select>
        @error('categoria')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div>
        <label class="form-label" for="status">Status</label>
        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
            @foreach($statusDisponiveis as $status)
                <option value="{{ $status }}" @selected(old('status', $despesa->status) === $status)>{{ $status }}</option>
            @endforeach
        </select>
        <div class="form-hint">Os status Pago e Atrasado são definidos automaticamente.</div>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div>
        <label class="form-label" for="valor">Valor</label>
        <div class="input-group">
            <span class="input-group-text">R$</span>
            <input class="form-control @error('valor') is-invalid @enderror" id="valor" name="valor" type="number"
                   min="0.01" max="999999999.99" step="0.01" value="{{ old('valor', $despesa->valor) }}" required>
            @error('valor')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div>
        <label class="form-label" for="vencimento">Vencimento</label>
        <input class="form-control @error('vencimento') is-invalid @enderror" id="vencimento" name="vencimento"
               type="date" value="{{ old('vencimento', $despesa->vencimento?->format('Y-m-d')) }}" required>
        @error('vencimento')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="form-grid-full">
        <label class="form-label" for="imagem">Comprovante em imagem</label>
        <input class="form-control @error('imagem') is-invalid @enderror" id="imagem" name="imagem" type="file"
               accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        <div class="form-hint">JPG, PNG ou WEBP, com no máximo 2 MB.</div>
        @error('imagem')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @if($despesa->imagem_url)
            <a class="current-file" href="{{ $despesa->imagem_url }}" target="_blank" rel="noopener">
                <i class="bi bi-paperclip"></i> Visualizar comprovante atual
            </a>
        @endif
    </div>
</div>

<div class="form-actions">
    <a class="btn btn-ghost" href="{{ $despesa->exists ? route('despesas.show', $despesa) : route('despesas.index') }}">Cancelar</a>
    <button class="btn btn-accent" type="submit"><i class="bi bi-check-lg"></i> {{ $submitLabel }}</button>
</div>
