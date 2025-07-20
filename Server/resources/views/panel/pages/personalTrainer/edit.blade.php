@extends('panel.structure.loggedframe')
@section('title', 'Adicionar Usuários')
@section('content')
<div class="row d-flex justify-content-center ">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Editar Personal</h4>
                <p class="card-description"> Preencha os dados abaixo para o Personal Trainer. </p>
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
                <form class="forms-sample" action="{{ route('admin.update.personal', ['personalId' => $personalTrainer->id]) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="d-flex align-items-center flex-column gap-4 mb-5">
                        <img class="img-lg rounded-circle" src="{{ $personalTrainer->avatar != null ? asset('storage/users/avatars/' . $personalTrainer->avatar) :  asset('assets/images/noavatar.png') }}" alt="image">
                        <button
                            type="button"
                            class="btn btn-sm btn-primary" onclick="$('#avatar').click()">
                            Alterar Avatar
                        </button>

                    </div>
                    <input type="file" name="avatar" id="avatar" class="d-none">
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input type="text" value="{{ $personalTrainer->name }}" class="form-control" required name="name" id="name" placeholder="Nome Completo">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" value="{{ $personalTrainer->email }}" required name="email" class="form-control" id="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="speciality">Especialidade</label>
                        <input type="speciality" value="{{ $personalTrainer->speciality }}" required name="speciality" class="form-control" id="speciality" placeholder="Especialidade">
                    </div>
                    <div class="form-group">
                        <label for="cpf">cpf</label>
                        <input type="cpf" value="{{ $personalTrainer->cpf }}" required name="cpf" class="form-control" id="cpf" placeholder="CPF">
                    </div>
                    <div class="form-group">
                        <label for="tel">tel</label>
                        <input type="tel" value="{{ $personalTrainer->tel }}" required name="tel" class="form-control" id="tel" placeholder="Telefone">
                    </div>
                    <div class="form-group">
                        <label for="gender">Gênero</label>
                        <select name="gender" id="gender" class="form-control text-white" required>
                            <option value="male" 
                                @php
                                    echo $personalTrainer->gender == 'male' ? 'selected' : ''    
                                @endphp>
                                Masculino 
                            </option>
                            <option value="female"
                                @php
                                    echo $personalTrainer->gender == 'female' ? 'selected' : ''    
                                @endphp> 
                                Feminino 
                            </option>
                            <option value="other"
                                @php
                                    echo $personalTrainer->gender == 'other' ? 'selected' : ''    
                                @endphp> 
                                Outro
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rate">Valor Hora</label>
                        <input type="rate" value="R${{ $personalTrainer->hourly_rate }}" name="rate" class="form-control" id="rate" placeholder="rate" required>
                    </div>
                    <div class="form-group">
                        <label for="state">Estado</label>
                        <select name="state" id="state" class="form-control text-white" required>
                            <option value="">Selecione um estado</option>
                            @foreach ($states as $state)
                                <option value="{{$state->ibge_id}}" 
                                    {{ $personalTrainer->ibge_state_id == $state->ibge_id ? 'selected' : '' }}>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="city">Cidade</label>
                        <select name="city" id="city" class="form-control text-white" required>
                            <option value="">Selecione um estado primeiro</option>
                        </select>
                    </div>

                    <a href="{{ route('admin.reset.user', ['userid' => $personalTrainer->id]) }}" class="btn btn-success">Resetar Senha</a>
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

    $(document).ready(function(){
        var stateId = $("#state").val();
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
                        $('#city').append('<option value="'+city.id+'" '+(city.id == <?php echo $personalTrainer->ibge_city_id; ?> ? 'selected' : '')+'>'+city.name+'</option>');                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            $('#city').empty();
            $('#city').append('<option value="">Selecione um estado primeiro</option>');
        }
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