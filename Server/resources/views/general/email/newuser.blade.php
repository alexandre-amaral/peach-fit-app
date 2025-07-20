@extends('general.structure.emailframe')

@section('title', 'Novo Cadastro | Peach')
@section('subject', 'Novo Cadastro')

@section('emailContent')
<style>
    .credentials {
        background-color: rgb(53, 53, 53);
        padding: 10px;
        border-radius: 3px;
        margin-bottom: 15px;
    }

    .label {
        font-weight: bold;
    }
</style>
<p>Olá <strong>{{ $name }}</strong>,</p>

<p>Sua Conta Criada com Sucesso!</p>
<p>Parabéns! Sua conta foi criada com sucesso.</p>
<p>Para acessar sua conta, utilize as seguintes credenciais:</p>
<div class="credentials">
    <p><span class="label">Email:</span> {{ $email }}</p>
    <p><span class="label">Senha:</span> {{ $password }}</p>
</div>
<p>Guarde estas informações em um local seguro. Recomendamos que você altere sua senha após o primeiro acesso.</p>

<p>Aproveite!</p>

@endsection