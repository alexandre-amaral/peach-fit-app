<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\General\ForgotPasswordService;
use Illuminate\Support\Facades\Log;

class UserForgotPasswordController extends Controller
{
    public function index()
    {
        return view('general.pages.forgot-password');
    }

    public function doForgotPassword(Request $request)
    {
        try {
            new ForgotPasswordService($request->email);
            return redirect()->route('general.view.forgot')->with('success', 'Foi gerada uma nova senha e enviada ao Email cadastrado');
        } catch (\Exception $e) {
            Log::warning('Falha ao recuperar senha: ' . $e->getMessage());
            return redirect()->route('general.view.forgot')->with('error', 'Ocorreu um erro ao tentar recuperar a senha');
        }
    }
}
