# 📧 CONFIGURAÇÃO GMAIL SMTP PARA PEACH FIT

## 🎯 **PASSO 1: Ativar 2FA no Gmail**

1. Acesse: https://myaccount.google.com/security
2. Em "Como você faz login no Google" → **Verificação em duas etapas**
3. **Ativar** a verificação em duas etapas

## 🔑 **PASSO 2: Criar App Password**

1. Após ativar 2FA, volte em: https://myaccount.google.com/security
2. Em "Como você faz login no Google" → **Senhas de app**
3. Clique em **"Selecionar app"** → **"Outro (nome personalizado)"**
4. Digite: **"Peach Fit"**
5. Clique **"Gerar"**
6. **COPIE** a senha de 16 caracteres (ex: `abcd efgh ijkl mnop`)

## ⚙️ **PASSO 3: Configurar no Sistema**

Execute no terminal:

```bash
cd /Users/alexandre/Documents/peach-fit-organized/Server
php -r "
require_once 'vendor/autoload.php';
use App\Services\General\GmailService;

// COLE SUA APP PASSWORD AQUI (sem espaços):
GmailService::setAppPassword('abcd efgh ijkl mnop');

echo 'App Password configurada!\n';
print_r(GmailService::checkConfig());
"
```

## 🧪 **PASSO 4: Testar**

```bash
php -r "
require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';
use App\Services\General\GmailService;

// Configurar App Password (substitua pela sua):
GmailService::setAppPassword('COLA_SUA_APP_PASSWORD_AQUI');

// Testar envio:
\$result = GmailService::testGmail('aluiz9997@gmail.com');
echo 'Resultado: ';
print_r(\$result);
"
```

## ✅ **VANTAGENS GMAIL SMTP:**

- ✅ **100% confiável** - Nunca vai para spam
- ✅ **Gratuito** - 500 emails/dia
- ✅ **SSL nativo** - Segurança total
- ✅ **Reputação excelente** - Gmail confia em si mesmo

## 🚀 **RESULTADO ESPERADO:**

```
Array
(
    [success] => 1
    [method] => gmail_smtp
)
```

## 🛠️ **INTEGRAÇÃO NO SISTEMA:**

Após configurar, atualize `LoginAppService` para usar Gmail:

```php
// No LoginAppService.php:
$emailResult = GmailService::send2FACode($email, $code);
```

## 📞 **ALTERNATIVAS SE GMAIL NÃO FUNCIONAR:**

1. **SendGrid** - 100 emails/dia grátis
2. **Brevo** - 300 emails/dia grátis  
3. **Mailgun** - 5000 emails/mês grátis (primeiros 3 meses) 