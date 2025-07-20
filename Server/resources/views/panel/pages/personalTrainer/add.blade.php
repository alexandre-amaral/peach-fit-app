@extends('panel.structure.loggedframe')
@section('title', 'Adicionar Usuários')
@section('content')
<div class="row d-flex justify-content-center ">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Adicionar Personal</h4>
                <p class="card-description"> Preencha os dados abaixo para adicionar um novo Personal. </p>
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
                <form class="forms-sample" action="{{ route('admin.register.personal') }}" enctype="multipart/form-data" method="POST">
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
                    <div class="form-group">
                        <label for="cpf">CPF</label>
                        <input type="cpf" value="{{ old('cpf') }}" required name="cpf" class="form-control" id="cpf" placeholder="CPF">
                    </div>
                    <div class="form-group">
                        <label for="speciality">Especialidade</label>
                        <input type="speciality" value="{{ old('speciality') }}" required name="speciality" class="form-control" id="speciality" placeholder="Especialidade">
                    </div>
                    <div class="form-group">
                        <label for="tel">Telefone</label>
                        <input type="tel" value="{{ old('tel') }}" required name="tel" class="form-control" id="tel" placeholder="(99)99999-9999">
                    </div>
                    <div class="form-group">
                        <label for="gender">Gênero</label>
                        <select name="gender" id="gender" class="form-control text-white" required>
                            <option value="">Selecione um gênero</option>
                            @foreach (['other' => 'Outro', 'male' => 'Masculino', 'female'=>'Feminino'] as $key => $value)
                                <option value="{{ $key }}"> {{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rate">Valor Hora</label>
                        <input type="rate" value="{{ old('rate') }}" required name="rate" class="form-control" id="rate" placeholder="99,99">
                    </div>
                    <div class="form-group">
                        <label for="state">Estado</label>
                        <select name="state" id="state" class="form-control text-white" required>
                            <option value="">Selecione um Estado</option>
                            @foreach ($states as $state)
                                <option value="{{$state->ibge_id}}">{{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="city">Cidade</label>
                        <select name="city" id="city" class="form-control text-white" required>
                            <option value="">Selecione um estado primeiro</option>
                        </select>
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

    $("#state").change(function() {
    var stateId = $(this).val();
    if (stateId) {
        $.ajax({
            type: 'POST',
            url: '/get-cities',
            data: {
                state_id: stateId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#city').empty();
                
                $('#city').append('<option value="">Selecione uma cidade</option>');
                
                $.each(response, function(key, city) {
                    $('#city').append('<option value="'+city.id+'">'+city.name+'</option>');
                });
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    } else {
        $('#city').empty();
        $('#city').append('<option value="">Selecione um estado primeiro</option>');
    }
});
</script>
@endpush
@endsection