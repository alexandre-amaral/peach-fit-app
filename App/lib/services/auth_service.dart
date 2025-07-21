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

  // Send verification code
  Future<Map<String, dynamic>> sendCode(String email) async {
    try {
      final response = await _httpService.post(
        ApiEndpoints.sendCode,
        body: {'email': email},
        requiresAuth: false,
      );
      return response;
    } catch (e) {
      throw e;
    }
  }

  // Verify code and login
  Future<UserModel> verifyCode(String email, String code) async {
    try {
      final response = await _httpService.post(
        ApiEndpoints.verifyCode,
        body: {
          'email': email,
          'code': code,
        },
        requiresAuth: false,
      );

      if (response['status'] == true && response['data'] != null) {
        final user = UserModel.fromJson(response['data']);
        
        // Save token if present
        if (user.token != null) {
          await _httpService.saveToken(user.token!);
        }
        
        // Save user data
        await user.persistUserData();
        
        _currentUser = user;
        return user;
      } else {
        throw HttpException(
          message: response['message'] ?? 'Código inválido',
          statusCode: 400,
        );
      }
    } catch (e) {
      throw e;
    }
  }

  // Direct login (if API supports it)
  Future<UserModel> login(String email, String password) async {
    try {
      final response = await _httpService.post(
        ApiEndpoints.login,
        body: {
          'email': email,
          'password': password,
        },
        requiresAuth: false,
      );

      if (response['status'] == true && response['data'] != null) {
        final user = UserModel.fromJson(response['data']);
        
        // Save token if present
        if (user.token != null) {
          await _httpService.saveToken(user.token!);
        }
        
        // Save user data
        await user.persistUserData();
        
        _currentUser = user;
        return user;
      } else {
        throw HttpException(
          message: response['message'] ?? 'Credenciais inválidas',
          statusCode: 401,
        );
      }
    } catch (e) {
      throw e;
    }
  }

  // Get current user from API
  Future<UserModel?> getCurrentUser() async {
    try {
      // Check if token exists
      if (!await _httpService.hasToken()) {
        return null;
      }

      final response = await _httpService.get(ApiEndpoints.getUser);
      
      if (response['data'] != null) {
        final user = UserModel.fromJson(response['data']);
        _currentUser = user;
        await user.persistUserData();
        return user;
      }
      
      return null;
    } catch (e) {
      // Token might be invalid, clear it
      await logout();
      return null;
    }
  }

  // Auto login on app start
  Future<UserModel?> autoLogin() async {
    try {
      if (await _httpService.hasToken()) {
        return await getCurrentUser();
      }
      return null;
    } catch (e) {
      return null;
    }
  }

  // Logout
  Future<bool> logout() async {
    try {
      // Try to call logout endpoint if token exists
      if (await _httpService.hasToken()) {
        try {
          await _httpService.post(ApiEndpoints.logout);
        } catch (e) {
          // Even if logout fails, we continue with local cleanup
        }
      }

      // Clear local data
      await _httpService.clearToken();
      await UserModel.clearUserData();
      _currentUser = null;
      
      return true;
    } catch (e) {
      return false;
    }
  }

  // Check if user is authenticated
  bool get isAuthenticated => _currentUser != null;

  // Check user type
  bool get isPersonalTrainer => _currentUser?.isPersonalTrainer ?? false;
  bool get isCustomer => _currentUser?.isCustomer ?? true;
} 