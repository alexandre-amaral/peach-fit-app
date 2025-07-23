<?php

require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "🧪 TESTE GMAIL ANTI-SPAM MELHORADO\n";
echo "=================================\n\n";

$email = 'aluiz9997@gmail.com';
$code = rand(100000, 999999);

echo "📧 Email: {$email}\n";
echo "🔐 Código: {$code}\n\n";

// Template HTML ULTRA anti-spam para Gmail
function createAntiSpamEmailHTML($code) {
    return '
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Acesso</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #ffffff;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #2c3e50; margin-bottom: 10px;">Código de Acesso</h1>
            <p style="color: #7f8c8d; font-size: 16px;">Peach Fit</p>
        </div>
        
        <p style="font-size: 16px; margin-bottom: 20px;">Olá,</p>
        
        <p style="font-size: 16px; margin-bottom: 25px;">
            Você solicitou acesso ao seu aplicativo. 
            Use o código abaixo para continuar:
        </p>
        
        <div style="background-color: #f8f9fa; border: 2px solid #e9ecef; border-radius: 8px; padding: 25px; text-align: center; margin: 25px 0;">
            <p style="margin: 0; font-size: 14px; color: #6c757d;">Seu código é:</p>
            <p style="font-size: 32px; font-weight: bold; color: #2c3e50; letter-spacing: 4px; margin: 10px 0;">' . $code . '</p>
            <p style="margin: 0; font-size: 12px; color: #6c757d;">Válido por 10 minutos</p>
        </div>
        
        <p style="font-size: 14px; color: #7f8c8d; margin-top: 30px;">
            Se você não solicitou este código, ignore esta mensagem.
        </p>
        
        <p style="font-size: 14px; margin-top: 20px;">
            Atenciosamente,<br>
            Equipe Peach Fit
        </p>
        
        <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e9ecef;">
            <p style="font-size: 12px; color: #adb5bd;">
                Esta é uma mensagem automática. Não responda a este email.
            </p>
        </div>
    </div>
</body>
</html>';
}

$mail = new PHPMailer(true);

try {
    echo "🔧 Configurando PHPMailer ANTI-SPAM...\n";
    
    // Configurações SMTP
    $mail->isSMTP();
    $mail->Host = 'srv846765.hstgr.cloud';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'peach@srv846765.hstgr.cloud';
    $mail->Password = 'peach@123';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    
    // SSL bypass
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
            'check_hostname' => false
        ]
    ];
    
    // CONFIGURAÇÕES ANTI-SPAM GMAIL
    $mail->CharSet = PHPMailer::CHARSET_UTF8;
    $mail->Encoding = PHPMailer::ENCODING_BASE64; // Mais seguro que 8bit
    $mail->WordWrap = 78;
    
    // Headers profissionais (SEM emojis - podem ser spam)
    $mail->XMailer = 'Peach Fit Authentication System 1.0';
    $mail->addCustomHeader('X-Priority', '3');
    $mail->addCustomHeader('X-MSMail-Priority', 'Normal');
    $mail->addCustomHeader('Importance', 'Normal');
    $mail->addCustomHeader('X-Mailer', 'Peach Fit Auth');
    $mail->addCustomHeader('List-Unsubscribe', '<mailto:noreply@srv846765.hstgr.cloud>');
    
    // Remetente e destinatário
    $mail->setFrom('peach@srv846765.hstgr.cloud', 'Peach Fit Auth');
    $mail->addReplyTo('noreply@srv846765.hstgr.cloud', 'Peach Fit');
    $mail->addAddress($email);
    
    // Conteúdo SEM palavras que podem ser spam
    $mail->isHTML(true);
    $mail->Subject = 'Codigo de Acesso - Peach Fit';
    $mail->Body = createAntiSpamEmailHTML($code);
    $mail->AltBody = "Seu codigo de acesso Peach Fit e: {$code}\n\nEste codigo e valido por 10 minutos.\n\nSe voce nao solicitou este codigo, ignore esta mensagem.\n\nEquipe Peach Fit";
    
    echo "📤 Enviando email ANTI-SPAM...\n";
    $mail->send();
    
    echo "\n🎯 EMAIL ANTI-SPAM ENVIADO!\n";
    echo "==========================\n";
    echo "✅ Assunto sem emojis\n";
    echo "✅ Conteúdo profissional\n";
    echo "✅ Headers List-Unsubscribe\n";
    echo "✅ Encoding BASE64 (mais seguro)\n";
    echo "✅ Sem palavras-chave de spam\n";
    echo "✅ Template minimalista\n";
    echo "\n🔍 VERIFIQUE:\n";
    echo "1. Caixa de entrada Gmail\n";
    echo "2. Pasta SPAM/Lixo eletrônico\n";
    echo "3. Aba Promoções/Social\n";
    echo "\n🔐 Código enviado: {$code}\n";
    
} catch (Exception $e) {
    echo "\n❌ ERRO: " . $e->getMessage() . "\n";
}

echo "\n🏁 Teste finalizado.\n"; 