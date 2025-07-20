@extends('panel.structure.loggedframe')
@section('title', 'Adicionar Usuários')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Meus Chamados Atribuídos</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Título</th>
                                    <th>Status</th>
                                    <th>Prioridade</th>
                                    <th>Criado por</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->id }}</td>
                                        <td>{{ $ticket->title }}</td>
                                        <td>
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
                                        </td>
                                        <td>
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
                                        </td>
                                        <td>{{ $ticket->creator->name ?? 'N/A' }}</td>
                                        <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>
                                                <a href="{{ route('tickets.edit', $ticket) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Nenhum chamado atribuído encontrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12 text-end">
            <a href="{{ route('tickets.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Novo Chamado
            </a>
            <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                <i class="fas fa-list"></i> Todos os Chamados
            </a>
        </div>
    </div>
</div>
@endsection