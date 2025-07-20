@extends('panel.structure.loggedframe')
@section('title', 'Adicionar Usuários')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Chamado #{{ $ticket->id }}: {{ $ticket->title }}</h5>
                    <div>
                        <a href="{{ route('tickets.index') }}" class="btn btn-secondary btn-sm">Voltar</a>
                        @if(auth()->user()->id == $ticket->created_by || auth()->user()->role == 'admin')
                            <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-warning btn-sm">Editar</a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="ticket-details mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Status:</strong> 
                                    @switch($ticket->status)
                                        @case('aberto')
                                            <span class="badge bg-danger">Aberto</span>
                                            @break
                                        @case('em_andamento')
                                            <span class="badge bg-warning">Em andamento</span>
                                            @break
                                        @case('resolvido')
                                            <span class="badge bg-success">Resolvido</span>
                                            @break
                                        @case('fechado')
                                            <span class="badge bg-secondary">Fechado</span>
                                            @break
                                    @endswitch
                                </p>
                                <p><strong>Prioridade:</strong> 
                                    @switch($ticket->priority)
                                        @case('baixa')
                                            <span class="badge bg-info">Baixa</span>
                                            @break
                                        @case('media')
                                            <span class="badge bg-primary">Média</span>
                                            @break
                                        @case('alta')
                                            <span class="badge bg-warning">Alta</span>
                                            @break
                                        @case('urgente')
                                            <span class="badge bg-danger">Urgente</span>
                                            @break
                                    @endswitch
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Criado por:</strong> {{ $ticket->creator->name }}</p>
                                <p><strong>Data de criação:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                                <p><strong>Atribuído a:</strong> {{ $ticket->assignedTo ? $ticket->assignedTo->name : 'Não atribuído' }}</p>
                            </div>
                        </div>

                        <div class="description mt-3">
                            <h6>Descrição do Problema:</h6>
                            <div class="text-black p-3 bg-light border rounded">
                                {!! nl2br(e($ticket->description)) !!}
                            </div>
                        </div>
                    </div>

                    @if($ticket->status != 'fechado' && (auth()->user()->role == 'admin' || auth()->user()->role == 'support'))
                        <div class="assign-section mb-4">
                            <h6>Atribuir Chamado</h6>
                            <form method="POST" action="{{ route('tickets.assign', $ticket) }}" class="row g-3 align-items-center">
                                @csrf
                                <div class="col-md-6">
                                    <select name="assigned_to" class="form-select">
                                        <option value="">Selecione um técnico</option>
                                        @foreach($supportUsers as $user)
                                            <option value="{{ $user->id }}" {{ $ticket->assigned_to == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">Atribuir</button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <hr>

                    <h6>Comentários e Atualizações</h6>
                    <div class="comments-section mb-4">
                        @forelse($ticket->comments as $comment)
                            <div class="comment p-3 mb-2 {{ $comment->is_solution ? 'border border-success' : 'border' }} rounded">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $comment->user->name }}</strong>
                                        @if($comment->is_solution)
                                            <span class="badge bg-success ms-2">Solução</span>
                                        @endif
                                    </div>
                                    <small>{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="mt-2">
                                    {!! nl2br(e($comment->comment)) !!}
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">Nenhum comentário ainda.</p>
                        @endforelse
                    </div>

                    @if($ticket->status != 'fechado')
                        <div class="add-comment-section">
                            <h6>Adicionar Comentário</h6>
                            <form method="POST" action="{{ route('ticket.comments.store', $ticket) }}">
                                @csrf
                                <div class="mb-3">
                                    <textarea class="form-control" name="comment" rows="3" placeholder="Digite seu comentário..." required></textarea>
                                </div>
                                
                                @if(auth()->user()->id == $ticket->assigned_to || auth()->user()->role == 'admin')
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="is_solution" name="is_solution">
                                        <label class="form-check-label" for="is_solution">Este comentário contém a solução do problema</label>
                                    </div>
                                @endif
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-primary">Enviar Comentário</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection