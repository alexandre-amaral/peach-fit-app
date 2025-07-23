<?php

namespace App\Services\General;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class GmailService
{
    /**
     * Configurações Gmail SMTP
     */
    private static $gmail_config = [
        'host' => 'smtp.gmail.com',
        'port' => 465,
        'encryption' => 'ssl',
        // ✅ CONFIGURADO E TESTADO:
        'username' => 'pauloclamberti@gmail.com', // Gmail correto
        'password' => 'mnbnseoejvpwkwrd', // App Password FUNCIONANDO!
        'from_name' => 'Peach Fit'
    ];
    
    /**
     * Enviar email via Gmail SMTP
     */
    public static function sendEmail($to, $subject, $body, $isHtml = true)
    {
        // Verificar se App Password foi configurada
        if (empty(self::$gmail_config['password'])) {
            Log::error("Gmail App Password não configurada");
            return [
                'success' => false, 
                'error' => 'Gmail App Password não configurada. Siga os passos no README.'
            ];
        }
        
        $mail = new PHPMailer(true);
        
        try {
            // 🔧 Configurações Gmail SMTP
            $mail->isSMTP();
            $mail->Host = self::$gmail_config['host'];
            $mail->Port = self::$gmail_config['port'];
            $mail->SMTPAuth = true;
            $mail->Username = self::$gmail_config['username'];
            $mail->Password = self::$gmail_config['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL para porta 465
            
            // 🛡️ SSL para Gmail (necessário)
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => true,
                    'verify_peer_name' => true,
                    'allow_self_signed' => false
                ]
            ];
            
            // 🎯 Configurações profissionais
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            $mail->Encoding = PHPMailer::ENCODING_BASE64;
            $mail->WordWrap = 78;
            
            // 📧 Headers profissionais
            $mail->XMailer = 'Peach Fit Authentication System';
            $mail->addCustomHeader('X-Priority', '3');
            $mail->addCustomHeader('X-MSMail-Priority', 'Normal');
            $mail->addCustomHeader('Importance', 'Normal');
            
            // 👤 Remetente e destinatário
            $mail->setFrom(
                self::$gmail_config['username'], 
                self::$gmail_config['from_name']
            );
            $mail->addReplyTo(
                self::$gmail_config['username'], 
                self::$gmail_config['from_name']
            );
            $mail->addAddress($to);
            
            // 📝 Conteúdo
            $mail->Subject = $subject;
            
            if ($isHtml) {
                $mail->isHTML(true);
                $mail->Body = $body;
                $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body));
            } else {
                $mail->isHTML(false);
                $mail->Body = $body;
            }
            
            // 🚀 Enviar
            $mail->send();
            
            Log::info("🎉 Email enviado via Gmail SMTP", [
                'to' => $to,
                'subject' => $subject,
                'method' => 'gmail_smtp'
            ]);
            
            return ['success' => true, 'method' => 'gmail_smtp'];
            
        } catch (Exception $e) {
            Log::error("❌ Erro Gmail SMTP", [
                'to' => $to,
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Template HTML para 2FA via Gmail
     */
    public static function createEmailHTML($code)
    {
        return '
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificação</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #ff6b6b; margin-bottom: 10px;">🍑 Peach Fit</h1>
            <h2 style="color: #2c3e50; margin-bottom: 10px;">Código de Verificação</h2>
        </div>
        
        <p style="font-size: 16px; margin-bottom: 20px;">Olá!</p>
        
        <p style="font-size: 16px; margin-bottom: 25px;">
            Você solicitou acesso ao <strong>Peach Fit</strong>. 
            Use o código abaixo para verificar sua identidade:
        </p>
        
        <div style="background-color: #f8f9fa; border: 2px solid #ff6b6b; border-radius: 8px; padding: 25px; text-align: center; margin: 25px 0;">
            <p style="margin: 0; font-size: 14px; color: #6c757d;">Seu código de verificação é:</p>
            <p style="font-size: 36px; font-weight: bold; color: #ff6b6b; letter-spacing: 8px; margin: 10px 0;">' . $code . '</p>
            <p style="margin: 0; font-size: 12px; color: #6c757d;">Este código é válido por 10 minutos</p>
        </div>
        
        <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; padding: 15px; margin: 20px 0;">
            <p style="margin: 0; font-size: 14px;"><strong>⚠️ Importante:</strong> Se você não solicitou este código, ignore este email.</p>
        </div>
        
        <p style="font-size: 14px; margin-top: 20px;">
            Atenciosamente,<br>
            <strong>Equipe Peach Fit</strong>
        </p>
        
        <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e9ecef;">
            <p style="font-size: 12px; color: #adb5bd;">
                Este é um email automático. Não responda a esta mensagem.<br>
                © 2025 Peach Fit. Todos os direitos reservados.
            </p>
        </div>
    </div>
</body>
</html>';
    }
    
    /**
     * Enviar código 2FA via Gmail
     */
    public static function send2FACode($email, $code)
    {
        $subject = 'Código de Verificação - Peach Fit';
        $htmlBody = self::createEmailHTML($code);
        
        return self::sendEmail($email, $subject, $htmlBody, true);
    }
    
    /**
     * Testar Gmail SMTP
     */
    public static function testGmail($to = 'aluiz9997@gmail.com')
    {
        $code = rand(100000, 999999);
        
        Log::info("🧪 Testando Gmail SMTP", [
            'to' => $to,
            'test_code' => $code
        ]);
        
        return self::send2FACode($to, $code);
    }
    
    /**
     * Configurar App Password
     */
    public static function setAppPassword($password)
    {
        self::$gmail_config['password'] = $password;
        return "App Password configurada com sucesso!";
    }
    
    /**
     * Verificar configuração
     */
    public static function checkConfig()
    {
        $config = self::$gmail_config;
        $config['password'] = empty($config['password']) ? '❌ NÃO CONFIGURADA' : '✅ CONFIGURADA';
        
        return $config;
    }
} 