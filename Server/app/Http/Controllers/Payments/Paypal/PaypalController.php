<?php

namespace App\Http\Controllers\Payments\Paypal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    public function pay(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken(); // Garante que temos um token válido
        $provider->setCurrency('BRL'); // Compatível com o arquivo blade

        $data = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'BRL', // Compatível com setCurrency
                        'value' => '100.00', // Valor do pagamento
                    ],
                ],
            ],
            'application_context' => [
                'return_url' => route('paypal.success'),
                'cancel_url' => route('paypal.cancel'),
            ],
        ];

        $order = $provider->createOrder($data);

        foreach ($order['links'] as $link) {
            if ($link['rel'] === 'approve') {
                return redirect($link['href']);
            }
        }

        return redirect()->route('paypal.cancel');
    }

    public function success(Request $request)
    {
        info('Entrou no método success');
        
        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken(); // Garante que temos um token válido
        
            $orderId = $request->input('token');
            info('Order ID: ' . $orderId);
        
            // Em vez de capturar, apenas verifica o status do pedido
            $orderDetails = $provider->showOrderDetails($orderId);
            info('Detalhes do pedido: ' . json_encode($orderDetails));
        
            // Verifica se o pedido já foi capturado com sucesso
            if (isset($orderDetails['status']) && $orderDetails['status'] === 'COMPLETED') {
                info('Pagamento confirmado com sucesso');
                
                // Aqui você pode processar informações adicionais do pagamento se necessário
                // Por exemplo, salvar detalhes na base de dados, etc.
                
                return redirect()->route('admin.view.dashboard')->with('success', 'Pagamento concluído com sucesso!');
            }
            
            // Se o status não for COMPLETED, pode ser um problema
            info('Status do pedido não é COMPLETED: ' . ($orderDetails['status'] ?? 'Desconhecido'));
            return redirect()->route('paypal.cancel')->with('error', 'Erro ao verificar o pagamento: Status não COMPLETED');
        } catch (\Exception $e) {
            info('Erro ao verificar pagamento: ' . $e->getMessage());
            return redirect()->route('paypal.cancel')->with('error', 'Erro ao verificar o pagamento: ' . $e->getMessage());
        }
    }

    public function cancel()
    {
        info('caiu aqui no erro');
        return redirect()->route('admin.view.dashboard')->with('error', 'Pagamento cancelado.');
    }

    public function processPayout(Request $request)
    {
        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken(); // Garante que temos um token válido
            
            // Registrar os dados recebidos para depuração
            info('Dados de payout recebidos: ' . json_encode($request->all()));
            
            // Preparar os itens de payout com base nos dados do formulário
            $payoutItems = [];
            
            // Verificar se estamos recebendo dados do formulário AJAX
            if ($request->has('items')) {
                foreach ($request->items as $index => $item) {
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
            } else {
                // Configuração estática de fallback (como você tinha originalmente)
                $payoutItems = [
                    [
                        'recipient_type' => 'EMAIL',
                        'receiver' => 'victorhugo.almeidamag@gmail.com',
                        'amount' => [
                            'value' => '45.00',
                            'currency' => 'BRL',
                        ],
                        'note' => 'Pagamento pela venda #123',
                        'sender_item_id' => 'item_1_' . time(),
                    ],
                    [
                        'recipient_type' => 'EMAIL',
                        'receiver' => 'victor.fut_@hotmail.com',
                        'amount' => [
                            'value' => '45.00',
                            'currency' => 'BRL',
                        ],
                        'note' => 'Pagamento pela venda #123',
                        'sender_item_id' => 'item_2_' . time(),
                    ],
                ];
            }
            
            $payout = [
                'sender_batch_header' => [
                    'sender_batch_id' => 'batch_' . uniqid(),
                    'email_subject' => 'Você recebeu um pagamento!',
                ],
                'items' => $payoutItems,
            ];
            
            // Registrar o payload que será enviado ao PayPal
            info('Enviando payout para o PayPal: ' . json_encode($payout));
            
            $response = $provider->createBatchPayout($payout);
            info('Resposta do payout PayPal: ' . json_encode($response));
            
            if (isset($response['batch_header']['batch_status']) && 
                ($response['batch_header']['batch_status'] === 'PENDING' || 
                 $response['batch_header']['batch_status'] === 'SUCCESS')) {
                
                $batchId = $response['batch_header']['payout_batch_id'] ?? 'ID não disponível';
                return response()->json([
                    'message' => "Payout processado com sucesso! ID do lote: {$batchId}",
                    'batch_id' => $batchId,
                    'status' => $response['batch_header']['batch_status']
                ]);
            }
            
            return response()->json([
                'error' => 'Erro ao processar payout. Status recebido: ' . 
                           ($response['batch_header']['batch_status'] ?? 'Status não disponível')
            ], 500);
            
        } catch (\Exception $e) {
            info('Erro ao processar payout: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erro ao processar payout: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verifica o status de um lote de payout específico
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPayoutStatus(Request $request)
    {
        $batchId = $request->input('batch_id');
        
        if (!$batchId) {
            return response()->json(['error' => 'ID do lote de payout não fornecido'], 400);
        }
        
        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            
            info('Verificando status do payout: ' . $batchId);
            
            // Buscar detalhes do payout
            $payoutDetails = $provider->showBatchPayoutDetails($batchId);
            info('Detalhes do payout: ' . json_encode($payoutDetails));
            
            // Verificar se temos um status válido
            if (isset($payoutDetails['batch_header']['batch_status'])) {
                $status = $payoutDetails['batch_header']['batch_status'];
                $itemsInfo = [];
                
                // Extrair informações sobre os itens individuais do payout
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
                
                return response()->json([
                    'batch_id' => $batchId,
                    'status' => $status,
                    'total_items' => $payoutDetails['batch_header']['total_items'] ?? 0,
                    'processed_items' => $payoutDetails['batch_header']['total_completed_items'] ?? 0,
                    'time' => $payoutDetails['batch_header']['time_created'] ?? 'N/A',
                    'items' => $itemsInfo
                ]);
            }
            
            return response()->json([
                'error' => 'Não foi possível obter o status do payout'
            ], 500);
            
        } catch (\Exception $e) {
            info('Erro ao verificar status do payout: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erro ao verificar status do payout: ' . $e->getMessage()
            ], 500);
        }
    }
}