@extends('panel.structure.loggedframe')
@section('title', 'Personal Trainers')
@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Personais Trainers</h4>
                <p class="card-description"> Lista de Personais Trainers. Total de {{ $personalTrainers->count()}}
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
                            @foreach($personalTrainers as $personal)
                            <tr>
                                <td>{{ $personal->id }}</td>
                                <td class="py-1">
                                    <img src="{{ $personal->avatar != null ? asset('storage/users/avatars/' . $personal->avatar) :  asset('assets/images/noavatar.png') }}" alt="image">
                                </td>
                                <td> {{ $personal->name }} </td>
                                <td>{{ $personal->email }}</td>
                                <td>
                                    <span
                                        class="badge bg-success">{!! strtoupper($personal->speciality) !!}</span>
                                </td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('admin.personal.edit', ['personalId' => $personal->id]) }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.delete.personal', ['personalId' => $personal->id]) }}" class="d-block" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="{{ $personal->id }}">
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
            </div>
        </div>
    </div>
</div>

@section('addLink', route('admin.personal.add'))

@endsection