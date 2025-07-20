<?php

namespace App\Services\Payment;

use App\Models\TrainingSession;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Log;

class ProcessPayoutService
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken(); 
    }

    /**
     * Cria pagamento para os personais
     *
     * @param array $items 
     * @return array 
     */

    public function createBatchPayout(array $items): array
    {
            $payoutItems = [];
        
            foreach ($items as $index => $item) {
                $payoutItems[] = [
                    'recipient_type' => 'EMAIL',
                    'receiver' => $item['email'],
                    'amount' => [
                        'value' => number_format((float)$item['amount'], 2, '.', ''),
                        'currency' => 'BRL',
                    ],
                    'note' => $item['note'],
                    'sender_item_id' => 'item_' . ($index + 1) . '_' . time(),
                ];
            }

            $payout = [
                'sender_batch_header' => [
                    'sender_batch_id' => 'batch_' . uniqid(),
                    'email_subject' => 'Você recebeu um pagamento!',
                ],
                'items' => $payoutItems,
            ];

            try {
                $response = $this->provider->createBatchPayout($payout);

                if (isset($response['batch_header']['batch_status']) &&
                    ($response['batch_header']['batch_status'] === 'PENDING' ||
                    $response['batch_header']['batch_status'] === 'SUCCESS')) {
                    return [
                        'success' => true,
                        'message' => 'Payout processado com sucesso!',
                        'batch_id' => $response['batch_header']['payout_batch_id'] ?? 'ID não disponível',
                        'status' => $response['batch_header']['batch_status'],
                        'paypal_response' => $response 
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'Erro ao processar payout. Status recebido: ' .
                                ($response['batch_header']['batch_status'] ?? 'Status não disponível'),
                    'status' => $response['batch_header']['batch_status'] ?? 'UNKNOWN',
                    'paypal_response' => $response 
                ];

            } catch (\Exception $e) {
                throw new \Exception('Falha na comunicação com o PayPal: ' . $e->getMessage());
            }
        }

        /**
         * Verifica o status de pagamento de um lote específico.
         *
         * @param string $batchId
         * @return array
         */
        public function checkPayoutStatus(string $batchId): array
        {

            try {
                $payoutDetails = $this->provider->showBatchPayoutDetails($batchId);

                if (isset($payoutDetails['batch_header']['batch_status'])) {
                    $status = $payoutDetails['batch_header']['batch_status'];
                    $itemsInfo = [];

                    if (isset($payoutDetails['items']) && is_array($payoutDetails['items'])) {
                        foreach ($payoutDetails['items'] as $item) {
                            $itemsInfo[] = [
                                'receiver' => $item['payout_item']['receiver'] ?? 'N/A',
                                'amount' => $item['payout_item']['amount']['value'] ?? 'N/A',
                                'currency' => $item['payout_item']['amount']['currency'] ?? 'N/A',
                                'status' => $item['transaction_status'] ?? 'N/A',
                                'transaction_id' => $item['transaction_id'] ?? 'N/A',
                            ];
                        }
                    }

                    return [
                        'success' => true,
                        'batch_id' => $batchId,
                        'status' => $status,
                        'total_items' => $payoutDetails['batch_header']['total_items'] ?? 0,
                        'processed_items' => $payoutDetails['batch_header']['total_completed_items'] ?? 0,
                        'time_created' => $payoutDetails['batch_header']['time_created'] ?? 'N/A',
                        'items' => $itemsInfo,
                        'paypal_response' => $payoutDetails 
                    ];
                }
            } catch (\Exception $e) {
                throw new \Exception('Falha ao verificar status com o PayPal: ' . $e->getMessage());
            }
        }
    }
