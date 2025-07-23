class ApiEndpoints {
  // 🏠 DESENVOLVIMENTO LOCAL - Conectar com servidor local
  static const String baseUrl = 'http://10.0.2.2:8000/api';
  
  // API Endpoints
  static String get sendCode => '$baseUrl/login';
  static String get verifyCode => '$baseUrl/verify_login';
  static String get logout => '$baseUrl/logout';
  static String get registerCustomer => '$baseUrl/customers/register';
  static String get registerPersonal => '$baseUrl/personal/register';
  static String get getStates => '$baseUrl/states';
  static String get getCitiesByState => '$baseUrl/cities';
  static String get getUser => '$baseUrl/users/me';

  static void logCurrentConfig() {
    print('🌍 AMBIENTE: DESENVOLVIMENTO LOCAL');
    print('🔗 BASE URL: $baseUrl');
  }
}
