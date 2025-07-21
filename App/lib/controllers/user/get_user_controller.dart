import 'package:peach_fit_app/models/user_model.dart';
import 'package:peach_fit_app/services/auth_service.dart';

class GetUserController {
  final AuthService _authService = AuthService();
  
  // ⚠️ ATUALIZADO: Agora retorna Future<UserModel?> e usa AuthService
  Future<UserModel?> getUser() async {
    try {
      return await _authService.getCurrentUser();
    } catch (e) {
      return null;
    }
  }
  
  // Método para obter usuário com tratamento de erro
  Future<UserModel?> getUserSafely() async {
    try {
      return await _authService.getCurrentUser();
    } catch (e) {
      print('Erro ao obter usuário: $e');
      return null;
    }
  }
  
  // Método para auto-login
  Future<UserModel?> autoLogin() async {
    try {
      final success = await _authService.autoLogin();
      if (success) {
        return await _authService.getCurrentUser();
      }
      return null;
    } catch (e) {
      print('Erro no auto-login: $e');
      return null;
    }
  }
}
