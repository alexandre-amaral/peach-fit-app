import 'package:peach_fit_app/services/http_service.dart';
import 'package:peach_fit_app/util/api_endpoints.dart';
import 'package:peach_fit_app/models/user_model.dart';

class AuthService {
  static final HttpService _httpService = HttpService();

  // Singleton pattern
  static final AuthService _instance = AuthService._internal();
  factory AuthService() => _instance;
  AuthService._internal();

  UserModel? _currentUser;
  UserModel? get currentUser => _currentUser;

  // Send verification code (2FA)
  Future<void> sendCode(String email) async {
    try {
      print('Sending 2FA code to $email');
      await _httpService.post(
        ApiEndpoints.sendCode,
        body: {'email': email},
        requiresAuth: false,
      );
      
      print('2FA code sent successfully');
    } catch (e) {
      print('Error sending 2FA code: $e');
      throw e;
    }
  }

  // Verify code and login (2FA)
  Future<UserModel> verifyCode(String email, String code) async {
    try {
      print('🔵 AuthService: Verificando código 2FA');
      final response = await _httpService.post(
        ApiEndpoints.verifyCode,
        body: {
          'email': email,
          'code': code,
        },
        requiresAuth: false,
      );

      print('🔵 AuthService: Resposta da verificação: $response');

      // ✅ Ajustado para a resposta real da API
      if (response['message'] == 'Login bem-sucedido.' && response['token'] != null) {
        // Save token
        await _httpService.saveToken(response['token']);
        
        // Buscar dados completos do usuário
        final userData = await _httpService.get(
          ApiEndpoints.getUser,
          requiresAuth: true,
        );
        
        final user = UserModel.fromJson(userData);
        user.token = response['token']; // Atribuir o token
        
        // Save user data
        await user.persistUserData();
        
        _currentUser = user;
        print('✅ AuthService: Login bem-sucedido');
        return user;
      } else {
        throw HttpException(
          message: response['message'] ?? 'Código inválido',
          statusCode: 422,
        );
      }
    } catch (e) {
      print('❌ AuthService: Erro na verificação: $e');
      throw e;
    }
  }

  // Get current user data
  Future<UserModel?> getCurrentUser() async {
    try {
      if (_currentUser != null) {
        return _currentUser;
      }
      
      // Try to load from storage
      _currentUser = await UserModel.getUserFromStorage();
      if (_currentUser != null) {
        return _currentUser;
      }
      
      // Try to fetch from API if we have a token
      if (await _httpService.hasValidToken()) {
        final userData = await _httpService.get(
          ApiEndpoints.getUser,
          requiresAuth: true,
        );
        
        _currentUser = UserModel.fromJson(userData);
        await _currentUser!.persistUserData();
        return _currentUser;
      }
      
      return null;
    } catch (e) {
      print('❌ AuthService: Erro ao obter usuário: $e');
      return null;
    }
  }

  // Auto-login from stored token
  Future<bool> autoLogin() async {
    try {
      final user = await getCurrentUser();
      return user != null;
    } catch (e) {
      print('❌ AuthService: Erro no auto-login: $e');
      return false;
    }
  }

  // Logout
  Future<void> logout() async {
    try {
      // Call logout API
      await _httpService.post(
        ApiEndpoints.logout,
        body: {},
        requiresAuth: true,
      );
    } catch (e) {
      print('❌ AuthService: Erro no logout API: $e');
    } finally {
      // Clear local data regardless of API response
      await _httpService.clearToken();
      await UserModel.clearUserData();
      _currentUser = null;
    }
  }

  // Check if user is logged in
  bool get isLoggedIn => _currentUser != null;
} 