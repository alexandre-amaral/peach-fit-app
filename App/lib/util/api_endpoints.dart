class ApiEndpoints {
  // Base URL da API de produção
  static const String baseUrl = 'https://srv846765.hstgr.cloud/api';
  
  // Auth endpoints
  static const String sendCode = '$baseUrl/send-code';
  static const String verifyCode = '$baseUrl/verify-code';
  static const String login = '$baseUrl/login';
  static const String logout = '$baseUrl/logout';
  
  // User endpoints
  static const String getUser = '$baseUrl/user';
  static const String updateUser = '$baseUrl/user';
  
  // Personal Trainer endpoints
  static const String getPersonalTrainers = '$baseUrl/personal-trainers';
  static const String registerPersonal = '$baseUrl/personal-trainers';
  static const String getPersonal = '$baseUrl/personal-trainers';
  
  // Customer endpoints
  static const String getCustomers = '$baseUrl/customers';
  static const String registerCustomer = '$baseUrl/customers';
  
  // Training Sessions
  static const String trainingSessions = '$baseUrl/training-sessions';
  static const String sendSessionInvite = '$baseUrl/training-sessions/invite';
  static const String acceptSession = '$baseUrl/training-sessions/accept';
  static const String denySession = '$baseUrl/training-sessions/deny';
  
  // Notifications
  static const String notifications = '$baseUrl/notifications';
  
  // Payments
  static const String processPayment = '$baseUrl/payments';
  static const String processRefund = '$baseUrl/payments/refund';
  
  // Reviews
  static const String reviews = '$baseUrl/reviews';
}
