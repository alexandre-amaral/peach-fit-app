class ApiEndpoints {
  // Base URL da API de produção
  static const String baseUrl = 'https://srv846765.hstgr.cloud/api';
  
  // Auth endpoints - CORRIGIDOS BASEADO NAS ROTAS REAIS
  static const String sendCode = '$baseUrl/login';
  static const String verifyCode = '$baseUrl/verify_login';
  static const String logout = '$baseUrl/logout';
  
  // User endpoints
  static const String getUser = '$baseUrl/user';
  static const String updateUser = '$baseUrl/user';
  
  // Registration endpoints - CORRIGIDOS
  static const String registerCustomer = '$baseUrl/customers/register';
  static const String registerPersonal = '$baseUrl/personal/register';
  
  // Personal Trainer endpoints
  static const String getPersonalTrainers = '$baseUrl/personal';
  static const String getPersonal = '$baseUrl/personal';
  
  // Customer endpoints
  static const String getCustomers = '$baseUrl/customers';
  
  // Training Sessions - CORRIGIDOS
  static const String trainingSessions = '$baseUrl/training';
  static const String sendSessionInvite = '$baseUrl/training/invite';
  static const String acceptSession = '$baseUrl/training';
  static const String denySession = '$baseUrl/training';
  
  // Notifications
  static const String notifications = '$baseUrl/notifications';
  
  // Payments - CORRIGIDOS
  static const String processPayment = '$baseUrl/paypal/pay';
  static const String processRefund = '$baseUrl/paypal/refund';
  
  // Reviews - CORRIGIDOS
  static const String reviews = '$baseUrl/training/review';
}
