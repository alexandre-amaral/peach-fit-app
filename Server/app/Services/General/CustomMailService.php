<?php

namespace App\Services\General;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CustomMailService
{
    /**
     * Enviar email usando PHPMailer direto (melhorado para Gmail)
     */
    public static function sendEmail($to, $subject, $body, $isHtml = true)
    {
        // Se for desenvolvimento, usar Laravel Mail normal (modo log)
        if (config('mail.default') === 'log') {
            try {
                Mail::raw($body, function ($message) use ($to, $subject) {
                    $message->to($to)->subject($subject);
                });
                
                Log::info("✅ Email enviado via Laravel Log", [
                    'to' => $to,
                    'subject' => $subject
                ]);
                
                return ['success' => true, 'method' => 'laravel_log'];
            } catch (\Exception $e) {
                Log::error("❌ Erro Laravel Mail", ['error' => $e->getMessage()]);
                return ['success' => false, 'error' => $e->getMessage()];
            }
        }
        
        // Se for produção, usar PHPMailer direto com melhorias Gmail
        return self::sendWithPHPMailerImproved($to, $subject, $body, $isHtml);
    }
    
    /**
     * Enviar com PHPMailer MELHORADO para Gmail (baseado na análise de entregabilidade)
     */
    private static function sendWithPHPMailerImproved($to, $subject, $body, $isHtml = true)
    {
        $mail = new PHPMailer(true);
        
        try {
            // 🔧 Configurações SMTP básicas
            $mail->isSMTP();
            $mail->Host = 'srv846765.hstgr.cloud';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->Username = 'peach@srv846765.hstgr.cloud';
            $mail->Password = 'peach@123';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            
            // 🛡️ SSL bypass (necessário para funcionar)
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                    'check_hostname' => false
                ]
            ];
            
            // 🎯 MELHORIAS PARA GMAIL - baseadas na análise
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            $mail->Encoding = PHPMailer::ENCODING_BASE64; // Mais seguro que 8bit
            $mail->WordWrap = 78;
            
            // 📧 HELO válido - resolve problema identificado
            $mail->Helo = 'srv846765.hstgr.cloud';
            
            // 🏷️ Headers profissionais ANTI-SPAM
            $mail->XMailer = 'Peach Fit Authentication System';
            $mail->addCustomHeader('X-Priority', '3');
            $mail->addCustomHeader('X-MSMail-Priority', 'Normal');
            $mail->addCustomHeader('Importance', 'Normal');
            $mail->addCustomHeader('X-Auto-Response-Suppress', 'All');
            $mail->addCustomHeader('List-Unsubscribe', '<mailto:noreply@srv846765.hstgr.cloud>');
            $mail->addCustomHeader('Precedence', 'bulk');
            
            // 👤 Configuração de remetente profissional
            $mail->setFrom('peach@srv846765.hstgr.cloud', 'Peach Fit Auth');
            $mail->addReplyTo('noreply@srv846765.hstgr.cloud', 'Peach Fit');
            $mail->addAddress($to);
            
            // 📝 Configurar conteúdo
            $mail->Subject = $subject;
            
            if ($isHtml) {
                $mail->isHTML(true);
                $mail->Body = $body;
                // Alt text sem acentos para compatibilidade
                $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body));
                $mail->AltBody = self::removeAccents($mail->AltBody);
            } else {
                $mail->isHTML(false);
                $mail->Body = $body;
            }
            
            // 🚀 Enviar
            $mail->send();
            
            Log::info("🎉 Email enviado via PHPMailer Gmail-Optimized", [
                'to' => $to,
                'subject' => $subject,
                'method' => 'phpmailer_gmail_optimized',
                'html' => $isHtml
            ]);
            
            return ['success' => true, 'method' => 'phpmailer_gmail_optimized'];
            
        } catch (Exception $e) {
            Log::error("❌ Erro PHPMailer Gmail-Optimized", [
                'to' => $to,
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Remover acentos para compatibilidade anti-spam
     */
    private static function removeAccents($string)
    {
        $map = [
            'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
            'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o',
            'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'ç' => 'c', 'ñ' => 'n',
            'Á' => 'A', 'À' => 'A', 'Ã' => 'A', 'Â' => 'A', 'Ä' => 'A',
            'É' => 'E', 'È' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Í' => 'I', 'Ì' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ó' => 'O', 'Ò' => 'O', 'Õ' => 'O', 'Ô' => 'O', 'Ö' => 'O',
            'Ú' => 'U', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
            'Ç' => 'C', 'Ñ' => 'N'
        ];
        
        return strtr($string, $map);
    }
    
    /**
     * Criar template HTML ULTRA anti-spam para Gmail
     */
    public static function createGmailFriendlyHTML($code)
    {
        return '
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codigo de Acesso</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #ffffff;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #2c3e50; margin-bottom: 10px;">Codigo de Acesso</h1>
            <p style="color: #7f8c8d; font-size: 16px;">Peach Fit</p>
        </div>
        
        <p style="font-size: 16px; margin-bottom: 20px;">Ola,</p>
        
        <p style="font-size: 16px; margin-bottom: 25px;">
            Voce solicitou acesso ao seu aplicativo. 
            Use o codigo abaixo para continuar:
        </p>
        
        <div style="background-color: #f8f9fa; border: 2px solid #e9ecef; border-radius: 8px; padding: 25px; text-align: center; margin: 25px 0;">
            <p style="margin: 0; font-size: 14px; color: #6c757d;">Seu codigo e:</p>
            <p style="font-size: 32px; font-weight: bold; color: #2c3e50; letter-spacing: 4px; margin: 10px 0;">' . $code . '</p>
            <p style="margin: 0; font-size: 12px; color: #6c757d;">Valido por 10 minutos</p>
        </div>
        
        <p style="font-size: 14px; color: #7f8c8d; margin-top: 30px;">
            Se voce nao solicitou este codigo, ignore esta mensagem.
        </p>
        
        <p style="font-size: 14px; margin-top: 20px;">
            Atenciosamente,<br>
            Equipe Peach Fit
        </p>
        
        <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #e9ecef;">
            <p style="font-size: 12px; color: #adb5bd;">
                Esta e uma mensagem automatica. Nao responda a este email.
            </p>
        </div>
    </div>
</body>
</html>';
    }
    
    /**
     * Método específico para enviar código 2FA otimizado para Gmail
     */
    public static function send2FACode($email, $code)
    {
        $subject = 'Codigo de Acesso - Peach Fit';
        $htmlBody = self::createGmailFriendlyHTML($code);
        
        return self::sendEmail($email, $subject, $htmlBody, true);
    }
    
    /**
     * Testar com configurações otimizadas para Gmail
     */
    public static function testGmailOptimized($to = 'aluiz9997@gmail.com')
    {
        $code = rand(100000, 999999);
        
        Log::info("🧪 Testando configurações Gmail otimizadas", [
            'to' => $to,
            'test_code' => $code,
            'improvements' => [
                'HELO válido',
                'Headers anti-spam',
                'Encoding BASE64',
                'Template sem acentos',
                'List-Unsubscribe header'
            ]
        ]);
        
        return self::send2FACode($to, $code);
    }
} 