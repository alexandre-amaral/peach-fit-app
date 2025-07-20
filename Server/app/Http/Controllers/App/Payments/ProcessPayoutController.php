<?php

namespace App\Http\Controllers\App\Payments;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Payment\ProcessPayoutService;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\ValidationException; 
use App\Services\Notifications\CreateNotificationService;
use App\Models\PersonalPaypalCredential;
use App\Models\TrainingSession;
use App\Models\PersonalTrainer;
use App\Models\PayoutPersonal;
use App\Events\PayoutProcessedSuccess;


class ProcessPayoutController extends Controller
{
    protected $processPayoutService;

    public function __construct(ProcessPayoutService $processPayoutService)
    {
        $this->processPayoutService = $processPayoutService;
    }

     /**
     * Processa um payout para um personal trainer, somando os valores de sessões de treino específicas.
     *
     * @param Request $request
     * @param int $personalId
     * @return JsonResponse
     */
    public function processPayout(Request $request, int $personalId)
    {
        try {
            $request->validate([
                'training_session_ids' => 'required|array',
                'training_session_ids.*' => 'required|integer|exists:training_sessions,id',
                'tip_amount' => 'nullable|numeric|min:0', // Campo opcional para a gorjeta
            ]);

            $personalPaypalCredential = PersonalPaypalCredential::where('personal_id', $personalId)->first();

            if (!$personalPaypalCredential) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credenciais PayPal não encontradas para este personal trainer.',
                ], 404);
            }

            $totalAmountForPayout = 0;

            foreach ($request->input('training_session_ids') as $sessionId) {
                $trainingSession = TrainingSession::find($sessionId);

                if ($trainingSession && $trainingSession->personal_id === $personalId) {
                    $totalAmountForPayout += $trainingSession->total_price;
                } 
            }

            //Permite passagem de gorjeta
            $tipAmount = (float) $request->input('tip_amount', 0);
            $totalAmountForPayout += $tipAmount;

            if ($totalAmountForPayout <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esse personal trainer não possui nenhum valor para saque.',
                ], 400);
            }

            $payoutItems = [
                [
                    'email' => $personalPaypalCredential->payment_email,
                    'amount' => (string) number_format($totalAmountForPayout, 2, '.', ''),
                    'note' => 'Pagamento de sessões de treino para o Personal ID ' . $personalId .
                                ' (Sessões: ' . (empty($processedSessionIds) ? 'N/A' : implode(', ', $processedSessionIds)) . ')' .
                                ($tipAmount > 0 ? ' + Gorjeta: R$' . number_format($tipAmount, 2, ',', '.') : ''),
                    'sender_item_id' => 'personal_payout_' . $personalId . '_' . time(),
                ]
            ];


            $result = $this->processPayoutService->createBatchPayout($payoutItems);

            $payoutBatch = PayoutPersonal::create([
                'personal_id' => $personalId,
                'paypal_batch_id' => $result['batch_id'] ?? null,
                'status' => $result['status'] ?? ($result['success'] ? 'UNKNOWN_SUCCESS' : 'UNKNOWN_FAILED'),
                'total_amount' => $totalAmountForPayout,
                'currency' => 'BRL',
                'items_sent' => $payoutItems,
                'paypal_response' => $result['paypal_response'] ?? null,
            ]);

            TrainingSession::whereIn('id', $request->input('training_session_ids'))
                 ->update(['payment_status' => 'personal_paid']);

            event(new PayoutProcessedSuccess($payoutBatch, $personalId, $request->input('training_session_ids')));
            
            $user = PersonalTrainer::find($personalId)->user;

            $msg = 'Você recebeu um pagamento da plataforma!';

            $notification = new CreateNotificationService(
                $user,
                'money',
                'Pagamento recebido da plataforma!',
                $msg
            );

            $notification->saveNotification();

            if ($result['success']) {
                return response()->json([
                    'message' => $result['message'],
                    'batch_id' => $result['batch_id'],
                    'status' => $result['status'],
                    'paypal_response' => $result['paypal_response'],
                    'payout_batch_record_id' => $payoutBatch->id,
                ], 200);
            }

            return response()->json([
                'error' => $result['message'],
                'status' => $result['status'],
                'paypal_response' => $result['paypal_response']
            ], 500);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Erro de validação nos dados fornecidos.',
                'details' => $e->errors()
            ], 422);
        } 
        
    }



    /**
     * Verifica o status de um lote de payout específico.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkPayoutStatus(Request $request, $batchId)
    {

        if (!$batchId) {
            return response()->json(['error' => 'ID do lote de payout não fornecido.'], 400);
        }

        try {
            $result = $this->processPayoutService->checkPayoutStatus($batchId);

            if ($result['success']) {
                return response()->json([
                    'batch_id' => $result['batch_id'],
                    'status' => $result['status'],
                    'total_items' => $result['total_items'],
                    'processed_items' => $result['processed_items'],
                    'time_created' => $result['time_created'],
                    'items' => $result['items'],
                    'paypal_response' => $result['paypal_response'] 
                ]);
            }

            return response()->json([
                'error' => $result['message'],
                'paypal_response' => $result['paypal_response'] 
            ], 500);

        } catch (\Exception $e) {
            Log::error('Erro ao verificar status do payout: ' . $e->getMessage());
            return response()->json([
                'error' => 'Ocorreu um erro interno ao verificar o status do payout: ' . $e->getMessage()
            ], 500);
        }
    }
}