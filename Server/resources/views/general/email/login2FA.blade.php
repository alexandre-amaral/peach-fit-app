@extends('general.structure.emailframe')

@section('title', 'Autenticação de Dois Fatores | Peach')
@section('subject', 'Código de Verificação de Login')

@section('emailContent')
<p>Olá,</p>

<p>Detectamos uma tentativa de login na sua conta a partir de um novo dispositivo.</p>

<p>Para garantir a sua segurança, utilize o código abaixo para confirmar o acesso:</p>

<p><strong>Código de verificação:</strong> <span style="color: #ff0000; font-size: 18px;">{{ $code }}</span></p>

<p>Se você não reconhece essa atividade, por favor, desconsidere este e-mail e entre em contato com nossa equipe de suporte imediatamente.</p>

<p>Atenciosamente,<br>Equipe Peach</p>
@endsection
