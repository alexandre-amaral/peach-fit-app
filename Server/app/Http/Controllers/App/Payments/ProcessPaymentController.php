<?php

namespace App\Http\Controllers\App\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\TrainingSession;
use App\Models\Customer;
use App\Events\PaymentProcessed;
use App\Models\Payment;
use App\Services\Payment\ProcessPaymentService; 
use App\Services\Notifications\CreateNotificationService;

class ProcessPaymentController extends Controller
{
    protected ProcessPaymentService $paymentService;
    protected $provider;

    public function __construct(ProcessPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken(); 
    }
    /**
     * Cria o pagamento via PayPal
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function createPaymentOrder(Request $request, $session_id)
    {
        $trainingSession = TrainingSession::find($session_id);
        if (!$trainingSession) {
            return response()->json([
                'success' => false,
                'message' => 'Sessão de treino não encontrada.'
            ], 404);
        }

        try {
            $approvalLink = $this->paymentService->createPaymentOrder($trainingSession);

            if ($approvalLink) {
                return response()->json([
                    'success' => true,
                    'approval_url' => $approvalLink
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao processar o pagamento.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lida com o retorno de sucesso do PayPal.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function success(Request $request)
    {
        $orderId = $request->query('token');
        $trainingSessionId = $request->query('training_session_id');

        if (empty($orderId)) {
            return response()->json([
                'success' => false,
                'message' => 'ID do pedido PayPal não encontrado na requisição de retorno.',
            ], 400);
        }

        try {
           
            $response = $this->provider->capturePaymentOrder($orderId);

            if (isset($response['status']) && $response['status'] === 'COMPLETED') {
                $capture = $response['purchase_units'][0]['payments']['captures'][0];

                $user = $request->user();
                $trainingSession = TrainingSession::find($trainingSessionId); 
                
                if (!$trainingSession) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sessão de treino não encontrada para o ID fornecido.',
                    ], 404);
                }

                $payment = Payment::create([
                    'payable_id' => $trainingSession->id,
                    'payable_type' => get_class($trainingSession),
                    'amount' => $capture['amount']['value'],
                    'currency' => $capture['amount']['currency_code'],
                    'fee_amount' => $capture['seller_receivable_breakdown']['paypal_fee']['value'] ?? null,
                    'description' => 'Pagamento da sessão de treino ' . $trainingSession->id,
                    'paypal_order_id' => $orderId,
                    'paypal_capture_id' => $capture['id'],
                    'status' => $capture['status'],
                ]);

               
                $trainingSession->update(['payment_status' => 'paid']);
                
                $user = Customer::find($trainingSession->customer_id)->user;

                event(new PaymentProcessed($payment, $trainingSession));

                $msg = 'Pagamento efetuado com sucesso! Treino:' . $trainingSession->id;

                $notification = new CreateNotificationService(
                    $user,
                    'money',
                    'Pagamento realizado!',
                    $msg
                );

                $notification->saveNotification();

                return response()->json([
                    'success' => true,
                    'message' => 'Pagamento capturado com sucesso.',
                    'paypal_response' => $capture,
                    'payment_record' => $payment,
                ], 200);

            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao processar o pagamento.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Lida com o cancelamento do pagamento.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function cancel(Request $request)
    {
        $trainingSessionId = $request->query('training_session_id'); 
        $orderId = $request->query('token');

        Log::info('Pagamento PayPal cancelado pelo usuário.', [
            'training_session_id' => $trainingSessionId,
            'order_id' => $orderId,
        ]);

        if ($trainingSessionId) {
            $session = TrainingSession::find($trainingSessionId);
            if ($session && $session->status === 'pending_payment_approval') {
                $session->status = 'payment_cancelled';
                $session->paypal_order_id = null;
                $session->save();
                Log::info("Training Session ID {$trainingSessionId} status changed to payment_cancelled.");
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Pagamento cancelado pelo usuário.',
        ], 200);
    }
}
