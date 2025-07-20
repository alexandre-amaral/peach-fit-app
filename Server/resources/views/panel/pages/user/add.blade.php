@extends('panel.structure.loggedframe')
@section('title', 'Adicionar Usuários')
@section('content')
<div class="row d-flex justify-content-center ">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Adicionar Administrador</h4>
                <p class="card-description"> Preencha os dados abaixo para adicionar um novo administrador. </p>
                @if($errors->any())
                @foreach($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                    {{ $error }}
                </div>
                @endforeach
                @endif

                @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
                @endif
                <form class="forms-sample" action="{{ route('admin.register.user') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="d-flex align-items-center flex-column gap-4 mb-5">
                        <img class="img-lg rounded-circle" src="{{  asset('assets/images/noavatar.png') }}" alt="">
                        <button
                            type="button"
                            class="btn btn-sm btn-primary" onclick="$('#avatar').click()">
                            Alterar Avatar
                        </button>

                    </div>
                    <input type="file" name="avatar" id="avatar" class="d-none">
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" value="{{ old('name') }}" class="form-control" required name="name" id="name" placeholder="Nome Completo">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" value="{{ old('email') }}" required name="email" class="form-control" id="email" placeholder="Email">
                    </div>
                    <button type="submit" class="btn btn-primary me-2">Salvar</button>
                    <button type="reset" class="btn btn-dark resetAll">Limpar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@push('footer')
<script>
    $('#avatar').change(function() {
        let input = this;
        let url = URL.createObjectURL(input.files[0]);
        $('.img-lg').attr('src', url);
    });

    $('.resetAll').on('click', () => {
        $('input[type=text], input[type=email]').val('');
        $('.img-lg').attr('src', `{{ asset('assets/images/noavatar.png') }}`);
    })
</script>
@endpush
@endsection