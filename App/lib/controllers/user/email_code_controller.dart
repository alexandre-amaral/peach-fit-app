import 'package:flutter/material.dart';
import 'package:peach_fit_app/controllers/controller.dart';
import 'package:peach_fit_app/services/auth_service.dart';

class EmailCodeController extends Controller {
  final AuthService _authService = AuthService();
  
  // ⚠️ DEPRECADO: Use AuthService.verifyCode() diretamente
  Future<bool> emailCode(BuildContext context, String code, {String? email}) async {
    try {
      if (code.isEmpty) {
        throw Exception("Você precisa informar o código.");
      }

      if (email == null || email.isEmpty) {
        throw Exception("Email é obrigatório para verificar o código.");
      }

      // Usar AuthService para verificar código
      await _authService.verifyCode(email, code);

      return true;
    } catch (e) {
      handleErrorMessage(context, e);
      return false;
    }
  }
  
  // Novo método que funciona com email e código
  Future<bool> verifyEmailCode(BuildContext context, String email, String code) async {
    try {
      if (code.isEmpty) {
        throw Exception("Você precisa informar o código.");
      }
      
      if (email.isEmpty) {
        throw Exception("Email é obrigatório.");
      }

      await _authService.verifyCode(email, code);
      return true;
    } catch (e) {
      handleErrorMessage(context, e);
      return false;
    }
  }
}
