@extends('general.structure.outerframe')

@section('title', 'Login')

@section('content')
<h3 class="card-title text-start mb-3">Acessar</h3>
@if ($errors->any())
<div
    class="alert alert-danger alert-dismissible fade show"
    role="alert">
    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Close"></button>

    @foreach($errors->all() as $error)
    {{ $error }}<br>
    @endforeach
</div>
@endif

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    {{ session('success') }}
</div>
@endif

<form action="{{ route('general.post.login') }}" enctype="multipart/form-data" method="POST">
    @csrf
    <div class="form-group">
        <label>Email *</label>
        <input type="email" class="form-control p_input" name="email" value="{{ old('email') }}" required>
    </div>
    <div class="form-group">
        <label>Senha *</label>
        <input type="password" class="form-control p_input" name="password" required>
    </div>
    <div class="form-group d-flex align-items-center justify-content-between">
        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" class="form-check-input" name="remember" value="true"> Ficar logado </label>
        </div>
        <a href="{{ route('general.view.forgot') }}" class="forgot-pass">Esqueci a senha</a>
    </div>
    <div class="text-center d-grid gap-2">
        <button type="submit" class="btn btn-danger btn-block enter-btn">Acessar</button>
    </div>
</form>
@endsection