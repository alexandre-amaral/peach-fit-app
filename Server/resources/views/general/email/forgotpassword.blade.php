@extends('general.structure.emailframe')

@section('title', 'Esqueci a Senha | Peach')
@section('subject', 'Esqueci a Senha')

@section('emailContent')
<p>Olá <strong>{{ $name }}</strong>,</p>

<p>Identificamos que você solicitou a recuperação de senha para a sua conta. Segue abaixo a sua nova senha de acesso:</p>

<p><strong>Nova Senha:</strong> <span style="color: #ff0000;">{{ $password }}</span></p>

<p>Por questões de segurança, recomendamos que você altere esta senha assim que fizer o login. Para isso, acesse sua conta e vá até as configurações de perfil.</p>

@endsection