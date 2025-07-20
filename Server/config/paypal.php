<?php
/**
 * PayPal Setting & API Credentials
 * Created by Raza Mehdi <srmk@outlook.com>.
 */

return [
    'mode' => env('PAYPAL_MODE', 'sandbox'), // sandbox para testes
    'sandbox' => [
        'client_id' => env('PAYPAL_SANDBOX_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET', ''),
        'app_id' => 'APP-80W284485P519543T', // Padrão para sandbox
    ],
    'live' => [
        'client_id' => env('PAYPAL_LIVE_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
        'app_id' => env('PAYPAL_LIVE_APP_ID', ''),
    ],

    'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'), // Captura imediata
    'currency' => env('PAYPAL_CURRENCY', 'BRL'), 
    'notify_url' => env('PAYPAL_NOTIFY_URL', ''),
    'locale' => env('PAYPAL_LOCALE', 'pt_BR'), 
    'validate_ssl' => env('PAYPAL_VALIDATE_SSL', true),
    
    // Add these for consistent API connections
    'http_timeout' => 30,
    'log.LogEnabled' => true,
    'log.FileName' => storage_path('logs/paypal.log'),
    'log.LogLevel' => 'INFO'
];