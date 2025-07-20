@extends('panel.structure.loggedframe')
@section('title', 'Adicionar Usuários')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Editar Chamado #{{ $ticket->id }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('tickets.update', $ticket) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">Título do Chamado</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $ticket->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição do Problema</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description', $ticket->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="priority" class="form-label">Prioridade</label>
                            <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                <option value="baixa" {{ old('priority', $ticket->priority) == 'baixa' ? 'selected' : '' }}>Baixa</option>
                                <option value="media" {{ old('priority', $ticket->priority) == 'media' ? 'selected' : '' }}>Média</option>
                                <option value="alta" {{ old('priority', $ticket->priority) == 'alta' ? 'selected' : '' }}>Alta</option>
                                <option value="urgente" {{ old('priority', $ticket->priority) == 'urgente' ? 'selected' : '' }}>Urgente</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="aberto" {{ old('status', $ticket->status) == 'aberto' ? 'selected' : '' }}>Aberto</option>
                                <option value="em_andamento" {{ old('status', $ticket->status) == 'em_andamento' ? 'selected' : '' }}>Em andamento</option>
                                <option value="resolvido" {{ old('status', $ticket->status) == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                                <option value="fechado" {{ old('status', $ticket->status) == 'fechado' ? 'selected' : '' }}>Fechado</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="assigned_to" class="form-label">Atribuir para</label>
                            <select class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
                                <option value="">Não atribuído</option>
                                @foreach($supportUsers as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to', $ticket->assigned_to) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar Chamado</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
