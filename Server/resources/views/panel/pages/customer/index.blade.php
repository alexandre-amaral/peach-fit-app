@extends('panel.structure.loggedframe')
@section('title', 'customer Trainers')
@section('content')
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Clientes</h4>
                <p class="card-description"> Lista de Clientes. Total de {{$customers->count()}}
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
                                <th> </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td class="py-1">
                                    <img src="{{ $customer->avatar != null ? asset('storage/users/avatars/' . $customer->avatar) :  asset('assets/images/noavatar.png') }}" alt="image">
                                </td>
                                <td> {{ $customer->name }} </td>
                                <td>{{ $customer->email }}</td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('admin.customer.edit', ['customerId' => $customer->id]) }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.delete.customer', ['customerId' => $customer->id]) }}" class="d-block" method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="{{ $customer->id }}">
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

@section('addLink', route('admin.customer.add'))

@endsection