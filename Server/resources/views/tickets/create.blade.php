@extends('panel.structure.loggedframe')
@section('title', 'Adicionar Usuários')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Abrir Novo Chamado</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('tickets.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Título do Chamado</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição do Problema</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="priority" class="form-label">Prioridade</label>
                            <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                <option value="baixa" {{ old('priority') == 'baixa' ? 'selected' : '' }}>Baixa</option>
                                <option value="media" {{ old('priority') == 'media' ? 'selected' : '' }} selected>Média</option>
                                <option value="alta" {{ old('priority') == 'alta' ? 'selected' : '' }}>Alta</option>
                                <option value="urgente" {{ old('priority') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('tickets.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Abrir Chamado</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection