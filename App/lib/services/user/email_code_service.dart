import 'package:peach_fit_app/models/notification_model.dart';
import 'package:peach_fit_app/models/user_model.dart';
import 'package:peach_fit_app/services/auth_service.dart';

class EmailCodeService {
  final AuthService _authService = AuthService();
  
  // ⚠️ DEPRECADO: Use AuthService.verifyCode() diretamente
  Future<UserModel> putEmailCode(String email, String code) async {
    try {
      // Usar AuthService para verificar código
      final user = await _authService.verifyCode(email, code);
      return user;
    } catch (e) {
      throw Exception(
        "Falha ao acessar o aplicativo. \nTente novamente mais tarde.",
      );
    }
  }
  
  // Método atualizado para verificar código com email específico
  Future<UserModel> verifyEmailCode(String email, String code) async {
    try {
      return await _authService.verifyCode(email, code);
    } catch (e) {
      throw Exception("Código inválido ou expirado.");
    }
  }
  
  // Método para reenviar código
  Future<void> resendCode(String email) async {
    try {
      await _authService.sendCode(email);
    } catch (e) {
      throw Exception("Falha ao reenviar código.");
    }
  }
}
