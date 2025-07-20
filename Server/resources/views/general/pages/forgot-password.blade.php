@extends('general.structure.outerframe')

@section('title', 'Esqueci a Senha')

@section('content')
<h3 class="card-title text-start mb-3">Esqueci a Senha</h3>

@if(session('success'))
<div
    class="alert alert-success alert-dismissible fade show"
    role="alert">
    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Close"></button>
    {{ session('success') }}
</div>
@endif
<script>
    var alertList = document.querySelectorAll(".alert");
    alertList.forEach(function(alert) {
        new bootstrap.Alert(alert);
    });
</script>



<form action="{{ route('general.post.forgot') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label>Email *</label>
        <input type="email" name="email" class="form-control p_input">
    </div>
    <div class="form-group d-flex align-items-center justify-content-between">
        <a href="{{ route('general.view.login') }}" class="forgot-pass">Lembrei a senha</a>
    </div>
    <div class="text-center d-grid gap-2">
        <button type="submit" class="btn btn-danger btn-block enter-btn">Recuperar</button>
    </div>
</form>
@endsection