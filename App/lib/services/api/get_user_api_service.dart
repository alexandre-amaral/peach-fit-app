import 'package:peach_fit_app/services/auth_service.dart';
import 'package:peach_fit_app/models/user_model.dart';

class GetUserApiService {
  static final AuthService _authService = AuthService();

  // Method updated to use real API
  Future<Map<String, dynamic>> getUser(
    String email,
    String password,
    String code,
  ) async {
    try {
      // For now, use the code as a verification code approach
      // Since the original API might expect email verification
      
      if (code.isNotEmpty) {
        // Try verify code first
        final user = await _authService.verifyCode(email, code);
        return {
          'status': true,
          'data': user.toJson(),
        };
      } else {
        // Try direct login
        final user = await _authService.login(email, password);
        return {
          'status': true,
          'data': user.toJson(),
        };
      }
    } catch (e) {
      return {
        'status': false,
        'message': e.toString(),
        'data': null,
      };
    }
  }

  // New method to send verification code
  Future<Map<String, dynamic>> sendVerificationCode(String email) async {
    try {
      final response = await _authService.sendCode(email);
      return response;
    } catch (e) {
      return {
        'status': false,
        'message': e.toString(),
      };
    }
  }

  // Method to get current user
  Future<UserModel?> getCurrentUser() async {
    try {
      return await _authService.getCurrentUser();
    } catch (e) {
      return null;
    }
  }

  // Method for auto login
  Future<UserModel?> autoLogin() async {
    try {
      return await _authService.autoLogin();
    } catch (e) {
      return null;
    }
  }

  // Logout method
  Future<bool> logout() async {
    try {
      return await _authService.logout();
    } catch (e) {
      return false;
    }
  }
}
