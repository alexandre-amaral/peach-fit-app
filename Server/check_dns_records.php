<?php

echo "🔍 VERIFICAÇÃO DNS - srv846765.hstgr.cloud\n";
echo "=========================================\n\n";

$domain = 'srv846765.hstgr.cloud';

echo "📧 Verificando registros de email para: {$domain}\n\n";

// Verificar MX records
echo "🔸 MX Records:\n";
$mx_records = dns_get_record($domain, DNS_MX);
if ($mx_records) {
    foreach ($mx_records as $mx) {
        echo "  - {$mx['target']} (prioridade: {$mx['pri']})\n";
    }
} else {
    echo "  ❌ Nenhum registro MX encontrado\n";
}

echo "\n🔸 SPF Records:\n";
$txt_records = dns_get_record($domain, DNS_TXT);
$spf_found = false;
if ($txt_records) {
    foreach ($txt_records as $txt) {
        if (strpos($txt['txt'], 'v=spf1') !== false) {
            echo "  ✅ SPF: {$txt['txt']}\n";
            $spf_found = true;
        }
    }
}
if (!$spf_found) {
    echo "  ❌ Nenhum registro SPF encontrado\n";
}

echo "\n🔸 DKIM Records:\n";
$dkim_selector = 'default'; // Padrão do Hostinger
$dkim_domain = "{$dkim_selector}._domainkey.{$domain}";
$dkim_records = dns_get_record($dkim_domain, DNS_TXT);
if ($dkim_records) {
    foreach ($dkim_records as $dkim) {
        if (strpos($dkim['txt'], 'v=DKIM1') !== false) {
            echo "  ✅ DKIM: " . substr($dkim['txt'], 0, 100) . "...\n";
        }
    }
} else {
    echo "  ❌ Nenhum registro DKIM encontrado\n";
}

echo "\n🔸 DMARC Records:\n";
$dmarc_domain = "_dmarc.{$domain}";
$dmarc_records = dns_get_record($dmarc_domain, DNS_TXT);
if ($dmarc_records) {
    foreach ($dmarc_records as $dmarc) {
        if (strpos($dmarc['txt'], 'v=DMARC1') !== false) {
            echo "  ✅ DMARC: {$dmarc['txt']}\n";
        }
    }
} else {
    echo "  ❌ Nenhum registro DMARC encontrado\n";
}

echo "\n🔸 A Records:\n";
$a_records = dns_get_record($domain, DNS_A);
if ($a_records) {
    foreach ($a_records as $a) {
        echo "  - IP: {$a['ip']}\n";
        
        // Verificar se IP está em blacklist
        $reversed_ip = implode('.', array_reverse(explode('.', $a['ip'])));
        echo "    🔍 Verificando blacklist...\n";
        
        $blacklists = [
            'zen.spamhaus.org',
            'bl.spamcop.net',
            'dnsbl.sorbs.net',
            'b.barracudacentral.org'
        ];
        
        foreach ($blacklists as $bl) {
            $check_host = "{$reversed_ip}.{$bl}";
            $result = gethostbyname($check_host);
            if ($result !== $check_host) {
                echo "    ❌ BLACKLISTED em {$bl}\n";
            } else {
                echo "    ✅ OK em {$bl}\n";
            }
        }
    }
} else {
    echo "  ❌ Nenhum registro A encontrado\n";
}

echo "\n📋 RESUMO:\n";
echo "=========\n";
echo "Para Gmail funcionar, você precisa:\n";
echo "1. ✅ Configurar SPF no DNS do Hostinger\n";
echo "2. ✅ Ativar DKIM no painel do Hostinger\n";
echo "3. ✅ Configurar DMARC (opcional mas recomendado)\n";
echo "4. ✅ Verificar se IP não está em blacklist\n";
echo "\n🔧 Se estiver em blacklist, contate o Hostinger!\n";

?> 