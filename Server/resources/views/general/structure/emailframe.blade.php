<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
</head>

<body style="margin: 0; padding: 0; background-color: #1a1a1a; color: #ffffff; font-family: Arial, sans-serif;">
    <!-- Container principal -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #1a1a1a;">
        <tr>
            <td align="center">
                <!-- Conteúdo do email -->
                <table width="600" cellpadding="20" cellspacing="0" border="0" style="background-color: #2a2a2a; border-radius: 10px; margin: 20px auto;">
                    <!-- Cabeçalho -->
                    <tr>
                        <td align="center" style="padding: 20px 0;">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="display: block; margin: 0 auto; max-width: 150px;">
                            <h1 style="color: #ffffff; font-size: 24px; margin: 20px 0;">@yield('subject')</h1>
                        </td>
                    </tr>
                    <!-- Corpo do email -->
                    <tr>
                        <td style="color: #e0e0e0; font-size: 16px; line-height: 1.6;">
                            @yield('emailContent')

                            <p>Caso você não tenha solicitado esta ação ou tenha alguma dúvida, por favor, entre em contato conosco imediatamente através do e-mail <a href="mailto:{{ $admin['email'] }}">{{ $admin['email'] }}</a> ou pelo telefone <strong>{{ $admin['tel'] }}</strong>.</p>

                            <p>Agradecemos a sua confiança em nossos serviços!</p>
                        </td>
                    </tr>
                    <!-- Rodapé -->
                    <tr>
                        <td align="center" style="padding: 20px 0; border-top: 1px solid #444; color: #a0a0a0; font-size: 14px;">
                            <p>&copy; {{ now()->format('Y') }} Peach Fit Delivery. Todos os direitos reservados.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>