<?php

namespace App\Services\Payment;

use App\Models\TrainingSession;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Log;
use App\Models\PersonalTrainer;

class ProcessPaymentService
{
    protected PayPalClient $paypalClient;

    /**
     * Cria pagamento via PayPal
     * @return jsonResponse
     */
    public function createPaymentOrder(TrainingSession $trainingSession): ?string
    {
        $personalId = $trainingSession->personal_id;

        $value = $trainingSession->total_price;
        //$returnUrl ='https://2069-2804-30c-856-400-d96-c5e5-3911-2432.ngrok-free/api/paypal/success/?training_session_id=' . $trainingSession->id;
        //$cancelUrl = 'https://2069-2804-30c-856-400-d96-c5e5-3911-2432.ngrok-free/api/paypal/cancel/?training_session_id=' . $trainingSession->id;

        $returnUrl = route('paypal.success', ['training_session_id' => $trainingSession->id]);
        $cancelUrl = route('paypal.cancel', ['training_session_id' => $trainingSession->id]);

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken(); 
        $provider->setCurrency('BRL'); 
        $data = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'BRL',
                        'value' => $value,
                    ],
                ],
            ],
        'application_context' => [
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
            ],
        ];

        $order = $provider->createOrder($data);
        $links = [];

           if (isset($order['status']) && $order['status'] === 'CREATED') {
            foreach ($order['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return $link['href']; 
                }
            }
        }

        return null;
    }

}