@extends('panel.structure.loggedframe')
@section('title', 'Usuários')
@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Usuários</h4>
                <p class="card-description"> Aqui estarão todos os usuários Administrativos
                </p>
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('success') }}
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> </th>
                                <th> Nome </th>
                                <th> Email </th>
                                <th> Função </th>
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td class="py-1">
                                    <img src="{{ $user->avatar != null ? asset('storage/users/avatars/' . $user->avatar) :  asset('assets/images/noavatar.png') }}" alt="image">
                                </td>
                                <td> {{ $user->name }} </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span
                                        class="badge bg-danger">Administrador</span>
                                </td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('admin.view.edit', ['userid' => $user->id]) }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.delete.user', ['userid' => $user->id]) }}" class="d-block" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="{{ $user->id }}">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                {{ $users->links('panel.structure.pagination') }}
            </div>
        </div>
    </div>
</div>

@section('addLink', route('admin.view.add'))

@endsection