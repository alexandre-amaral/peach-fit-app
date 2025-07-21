import 'package:peach_fit_app/services/http_service.dart';
import 'package:peach_fit_app/util/api_endpoints.dart';
import 'package:peach_fit_app/models/user_model.dart';

class RegistrationService {
  static final HttpService _httpService = HttpService();
  static final RegistrationService _instance = RegistrationService._internal();
  factory RegistrationService() => _instance;
  RegistrationService._internal();

  // Register Customer
  Future<UserModel> registerCustomer({
    required String name,
    required String email,
    required String password,
    required String cpf,
    required String phone,
    required int state,    // ✅ Agora obrigatório
    required int city,     // ✅ Agora obrigatório
    String? gender,
    String? birthDate,
    double? height,
    double? weight,
    String? fitnessGoals,
    String? fitnessLevel,
  }) async {
    try {
      // ✅ Limpar formatação do CPF (manter só números)
      final cleanCpf = cpf.replaceAll(RegExp(r'[^0-9]'), '');
      final cleanPhone = phone.replaceAll(RegExp(r'[^0-9]'), '');
      
      print('🔵 RegistrationService: Iniciando registro de cliente...');
      print('🔵 URL: ${ApiEndpoints.registerCustomer}');
      print('🔵 Dados: name=$name, email=$email, cpf=$cleanCpf, phone=$cleanPhone, state=$state, city=$city');
      
      final response = await _httpService.post(
        ApiEndpoints.registerCustomer,
        body: {
          'name': name,
          'email': email,
          'password': password,
          'cpf': cleanCpf,  // ✅ CPF limpo
          'tel': cleanPhone,  // ✅ Telefone limpo
          'state': state,    // ✅ Valor do formulário
          'city': city,      // ✅ Valor do formulário
          if (gender != null) 'gender': gender,
          if (birthDate != null) 'birth_date': birthDate,
          if (height != null) 'height': height,
          if (weight != null) 'weight': weight,
          if (fitnessGoals != null) 'fitness_goals': fitnessGoals,
          if (fitnessLevel != null) 'fitness_level': fitnessLevel,
        },
        requiresAuth: false,
      );

      print('🔵 RegistrationService: Resposta da API: $response');

      if (response['status'] == true && response['data'] != null) {
        print('✅ RegistrationService: Sucesso! Criando UserModel...');
        final user = UserModel.fromJson(response['data']);
        
        // Save token if present
        if (user.token != null) {
          print('🔵 RegistrationService: Salvando token...');
          await _httpService.saveToken(user.token!);
        }
        
        // Save user data
        print('🔵 RegistrationService: Salvando dados do usuário...');
        await user.persistUserData();
        
        return user;
      } else {
        print('❌ RegistrationService: Resposta de erro da API');
        throw HttpException(
          message: response['message'] ?? 'Erro no cadastro',
          statusCode: 400,
        );
      }
    } catch (e) {
      print('❌ RegistrationService: Exception capturada: $e');
      throw e;
    }
  }

  // Register Personal Trainer
  Future<UserModel> registerPersonalTrainer({
    required String name,
    required String email,
    required String password,
    required String cpf,
    required String phone,
    required String specialties, // ✅ Agora obrigatório
    required String gender,      // ✅ Agora obrigatório
    required double hourlyRate,  // ✅ Agora obrigatório
    String? birthDate,
    double? height,
    double? weight,
    String? cref,
    String? experience,
    String? serviceArea,
    String? bio,
  }) async {
    try {
      // ✅ Limpar formatação do CPF (manter só números)
      final cleanCpf = cpf.replaceAll(RegExp(r'[^0-9]'), '');
      final cleanPhone = phone.replaceAll(RegExp(r'[^0-9]'), '');
      
      print('🔵 RegistrationService: Iniciando registro de Personal Trainer...');
      print('🔵 URL: ${ApiEndpoints.registerPersonal}');
      print('🔵 Dados: name=$name, email=$email, cpf=$cleanCpf, phone=$cleanPhone, specialty=$specialties, gender=$gender, rate=$hourlyRate');
      
      final response = await _httpService.post(
        ApiEndpoints.registerPersonal,
        body: {
          'name': name,
          'email': email,
          'password': password,
          'cpf': cleanCpf,  // ✅ CPF limpo (11 dígitos)
          'tel': cleanPhone,  // ✅ Telefone limpo
          'speciality': specialties,  // ✅ Valor do formulário
          'gender': gender,           // ✅ Valor do formulário
          'rate': hourlyRate,         // ✅ Valor do formulário
          if (birthDate != null) 'birth_date': birthDate,
          if (height != null) 'height': height,
          if (weight != null) 'weight': weight,
          if (cref != null) 'cref': cref,
          if (experience != null) 'experience': experience,
          if (serviceArea != null) 'service_area': serviceArea,
          if (bio != null) 'bio': bio,
        },
        requiresAuth: false,
      );

      print('🔵 RegistrationService: Resposta da API: $response');

      if (response['status'] == true && response['data'] != null) {
        print('✅ RegistrationService: Sucesso! Criando UserModel...');
        final user = UserModel.fromJson(response['data']);
        
        // Save token if present
        if (user.token != null) {
          print('🔵 RegistrationService: Salvando token...');
          await _httpService.saveToken(user.token!);
        }
        
        // Save user data
        print('🔵 RegistrationService: Salvando dados do usuário...');
        await user.persistUserData();
        
        return user;
      } else {
        print('❌ RegistrationService: Resposta de erro da API');
        throw HttpException(
          message: response['message'] ?? 'Erro no cadastro',
          statusCode: 400,
        );
      }
    } catch (e) {
      print('❌ RegistrationService: Exception capturada: $e');
      throw e;
    }
  }

  // Validate form data
  static String? validateName(String? value) {
    if (value == null || value.isEmpty) {
      return 'Nome é obrigatório';
    }
    if (value.length < 2) {
      return 'Nome deve ter pelo menos 2 caracteres';
    }
    return null;
  }

  static String? validateEmail(String? value) {
    if (value == null || value.isEmpty) {
      return 'Email é obrigatório';
    }
    if (!RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$').hasMatch(value)) {
      return 'Email inválido';
    }
    return null;
  }

  static String? validatePassword(String? value) {
    if (value == null || value.isEmpty) {
      return 'Senha é obrigatória';
    }
    if (value.length < 6) {
      return 'Senha deve ter pelo menos 6 caracteres';
    }
    return null;
  }

  static String? validateCPF(String? value) {
    if (value == null || value.isEmpty) {
      return 'CPF é obrigatório';
    }
    // Remove formatação
    value = value.replaceAll(RegExp(r'[^0-9]'), '');
    if (value.length != 11) {
      return 'CPF deve ter 11 dígitos';
    }
    return null;
  }

  static String? validatePhone(String? value) {
    if (value == null || value.isEmpty) {
      return 'Telefone é obrigatório';
    }
    // Remove formatação
    value = value.replaceAll(RegExp(r'[^0-9]'), '');
    if (value.length < 10) {
      return 'Telefone inválido';
    }
    return null;
  }

  static String? validateConfirmPassword(String? password, String? confirmPassword) {
    if (confirmPassword == null || confirmPassword.isEmpty) {
      return 'Confirmação de senha é obrigatória';
    }
    if (password != confirmPassword) {
      return 'Senhas não coincidem';
    }
    return null;
  }
  
  // 🧪 MÉTODO PARA TESTAR CONEXÃO COM A API
  Future<void> testConnection() async {
    print('🧪 RegistrationService: Testando conexão...');
    try {
      // Vamos fazer uma requisição simples para o base URL
      final response = await _httpService.get(
        'https://srv846765.hstgr.cloud/api/user',  // Endpoint que exige auth
        requiresAuth: false,  // Sem auth para teste
      );
      print('🧪 Resposta do teste: $response');
    } catch (e) {
      print('🧪 Erro esperado (401): $e');
      // Um erro 401 é esperado, significa que a API está respondendo
      if (e.toString().contains('401')) {
        print('✅ API está online (erro 401 esperado)');
        return; // Sucesso! API está respondendo
      }
      throw e; // Re-throw se for outro tipo de erro
    }
  }
} 