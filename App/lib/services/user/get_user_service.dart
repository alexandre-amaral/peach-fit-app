import 'package:peach_fit_app/models/user_model.dart';
import 'package:peach_fit_app/services/auth_service.dart';

class GetUserService {
  // ⚠️ DEPRECADO: Use AuthService em vez deste método
  Future<void> putEmailPass(String email, String password) async {
    try {
      // Usar o novo AuthService em vez de UserModel diretamente
      final authService = AuthService();
      await authService.login(email, password);
    } catch (e) {
      throw Exception("Falha ao fazer login: ${e.toString()}");
    }
  }
  
  // Método alternativo para compatibilidade
  Future<UserModel?> getUserByEmailPassword(String email, String password) async {
    try {
      final authService = AuthService();
      final user = await authService.login(email, password);
      return user;
    } catch (e) {
      return null;
    }
  }
}
