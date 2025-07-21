import 'package:flutter/material.dart';
import 'package:peach_fit_app/components/button_component.dart';
import 'package:peach_fit_app/components/outside/outside_frame_screen.dart';
import 'package:peach_fit_app/components/outside/textfield_component.dart';
import 'package:peach_fit_app/util/app_routes.dart';
import 'package:peach_fit_app/services/registration_service.dart';
import 'package:quickalert/quickalert.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  // FORM KEY E CONTROLLERS ADICIONADOS
  final _formKey = GlobalKey<FormState>();
  final RegistrationService _registrationService = RegistrationService();
  
  // Controllers para os campos básicos
  final _nameController = TextEditingController();
  final _cpfController = TextEditingController();
  final _phoneController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();
  
  // Controllers para campos específicos
  final _specialtyController = TextEditingController();
  final _rateController = TextEditingController();
  
  bool _isLoading = false;
  bool _isPersonalTrainer = false;  // Toggle entre Cliente e Personal Trainer
  
  // Dropdown values
  String _selectedGender = 'other';
  int _selectedState = 25; // São Paulo (padrão)
  int _selectedCity = 300; // São Paulo (capital - padrão)
  
  // ✅ ESTADOS E CIDADES COMPLETOS DO BRASIL
  final List<Map<String, dynamic>> _states = [
    {'id': 1, 'name': 'Acre', 'uf': 'AC'},
    {'id': 2, 'name': 'Alagoas', 'uf': 'AL'},
    {'id': 3, 'name': 'Amapá', 'uf': 'AP'},
    {'id': 4, 'name': 'Amazonas', 'uf': 'AM'},
    {'id': 5, 'name': 'Bahia', 'uf': 'BA'},
    {'id': 6, 'name': 'Ceará', 'uf': 'CE'},
    {'id': 7, 'name': 'Distrito Federal', 'uf': 'DF'},
    {'id': 8, 'name': 'Espírito Santo', 'uf': 'ES'},
    {'id': 9, 'name': 'Goiás', 'uf': 'GO'},
    {'id': 10, 'name': 'Maranhão', 'uf': 'MA'},
    {'id': 11, 'name': 'Mato Grosso', 'uf': 'MT'},
    {'id': 12, 'name': 'Mato Grosso do Sul', 'uf': 'MS'},
    {'id': 13, 'name': 'Minas Gerais', 'uf': 'MG'},
    {'id': 14, 'name': 'Pará', 'uf': 'PA'},
    {'id': 15, 'name': 'Paraíba', 'uf': 'PB'},
    {'id': 16, 'name': 'Paraná', 'uf': 'PR'},
    {'id': 17, 'name': 'Pernambuco', 'uf': 'PE'},
    {'id': 18, 'name': 'Piauí', 'uf': 'PI'},
    {'id': 19, 'name': 'Rio de Janeiro', 'uf': 'RJ'},
    {'id': 20, 'name': 'Rio Grande do Norte', 'uf': 'RN'},
    {'id': 21, 'name': 'Rio Grande do Sul', 'uf': 'RS'},
    {'id': 22, 'name': 'Rondônia', 'uf': 'RO'},
    {'id': 23, 'name': 'Roraima', 'uf': 'RR'},
    {'id': 24, 'name': 'Santa Catarina', 'uf': 'SC'},
    {'id': 25, 'name': 'São Paulo', 'uf': 'SP'},
    {'id': 26, 'name': 'Sergipe', 'uf': 'SE'},
    {'id': 27, 'name': 'Tocantins', 'uf': 'TO'},
  ];
  
  final Map<int, List<Map<String, dynamic>>> _citiesByState = {
    1: [ // Acre
      {'id': 1, 'name': 'Rio Branco'},
      {'id': 2, 'name': 'Cruzeiro do Sul'},
      {'id': 3, 'name': 'Sena Madureira'},
      {'id': 4, 'name': 'Tarauacá'},
      {'id': 5, 'name': 'Feijó'},
    ],
    2: [ // Alagoas
      {'id': 6, 'name': 'Maceió'},
      {'id': 7, 'name': 'Arapiraca'},
      {'id': 8, 'name': 'Palmeira dos Índios'},
      {'id': 9, 'name': 'Rio Largo'},
      {'id': 10, 'name': 'Penedo'},
    ],
    3: [ // Amapá
      {'id': 11, 'name': 'Macapá'},
      {'id': 12, 'name': 'Santana'},
      {'id': 13, 'name': 'Laranjal do Jari'},
      {'id': 14, 'name': 'Oiapoque'},
    ],
    4: [ // Amazonas
      {'id': 15, 'name': 'Manaus'},
      {'id': 16, 'name': 'Parintins'},
      {'id': 17, 'name': 'Itacoatiara'},
      {'id': 18, 'name': 'Manacapuru'},
      {'id': 19, 'name': 'Coari'},
    ],
    5: [ // Bahia
      {'id': 20, 'name': 'Salvador'},
      {'id': 21, 'name': 'Feira de Santana'},
      {'id': 22, 'name': 'Vitória da Conquista'},
      {'id': 23, 'name': 'Camaçari'},
      {'id': 24, 'name': 'Juazeiro'},
      {'id': 25, 'name': 'Ilhéus'},
      {'id': 26, 'name': 'Lauro de Freitas'},
      {'id': 27, 'name': 'Itabuna'},
    ],
    6: [ // Ceará
      {'id': 28, 'name': 'Fortaleza'},
      {'id': 29, 'name': 'Caucaia'},
      {'id': 30, 'name': 'Juazeiro do Norte'},
      {'id': 31, 'name': 'Maracanaú'},
      {'id': 32, 'name': 'Sobral'},
      {'id': 33, 'name': 'Crato'},
    ],
    7: [ // Distrito Federal
      {'id': 400, 'name': 'Brasília'},
    ],
    8: [ // Espírito Santo
      {'id': 34, 'name': 'Vitória'},
      {'id': 35, 'name': 'Vila Velha'},
      {'id': 36, 'name': 'Cariacica'},
      {'id': 37, 'name': 'Serra'},
      {'id': 38, 'name': 'Guarapari'},
    ],
    9: [ // Goiás
      {'id': 39, 'name': 'Goiânia'},
      {'id': 40, 'name': 'Aparecida de Goiânia'},
      {'id': 41, 'name': 'Anápolis'},
      {'id': 42, 'name': 'Rio Verde'},
      {'id': 43, 'name': 'Luziânia'},
    ],
    10: [ // Maranhão
      {'id': 44, 'name': 'São Luís'},
      {'id': 45, 'name': 'Imperatriz'},
      {'id': 46, 'name': 'São José de Ribamar'},
      {'id': 47, 'name': 'Timon'},
      {'id': 48, 'name': 'Caxias'},
    ],
    11: [ // Mato Grosso
      {'id': 49, 'name': 'Cuiabá'},
      {'id': 50, 'name': 'Várzea Grande'},
      {'id': 51, 'name': 'Rondonópolis'},
      {'id': 52, 'name': 'Sinop'},
      {'id': 53, 'name': 'Tangará da Serra'},
    ],
    12: [ // Mato Grosso do Sul
      {'id': 54, 'name': 'Campo Grande'},
      {'id': 55, 'name': 'Dourados'},
      {'id': 56, 'name': 'Três Lagoas'},
      {'id': 57, 'name': 'Corumbá'},
      {'id': 58, 'name': 'Ponta Porã'},
    ],
    13: [ // Minas Gerais
      {'id': 100, 'name': 'Belo Horizonte'},
      {'id': 101, 'name': 'Uberlândia'},
      {'id': 102, 'name': 'Contagem'},
      {'id': 103, 'name': 'Juiz de Fora'},
      {'id': 104, 'name': 'Betim'},
      {'id': 105, 'name': 'Montes Claros'},
      {'id': 106, 'name': 'Ribeirão das Neves'},
      {'id': 107, 'name': 'Uberaba'},
      {'id': 108, 'name': 'Governador Valadares'},
      {'id': 109, 'name': 'Ipatinga'},
    ],
    14: [ // Pará
      {'id': 59, 'name': 'Belém'},
      {'id': 60, 'name': 'Ananindeua'},
      {'id': 61, 'name': 'Santarém'},
      {'id': 62, 'name': 'Marabá'},
      {'id': 63, 'name': 'Parauapebas'},
    ],
    15: [ // Paraíba
      {'id': 64, 'name': 'João Pessoa'},
      {'id': 65, 'name': 'Campina Grande'},
      {'id': 66, 'name': 'Santa Rita'},
      {'id': 67, 'name': 'Patos'},
      {'id': 68, 'name': 'Bayeux'},
    ],
    16: [ // Paraná
      {'id': 69, 'name': 'Curitiba'},
      {'id': 70, 'name': 'Londrina'},
      {'id': 71, 'name': 'Maringá'},
      {'id': 72, 'name': 'Ponta Grossa'},
      {'id': 73, 'name': 'Cascavel'},
      {'id': 74, 'name': 'São José dos Pinhais'},
      {'id': 75, 'name': 'Foz do Iguaçu'},
    ],
    17: [ // Pernambuco
      {'id': 76, 'name': 'Recife'},
      {'id': 77, 'name': 'Jaboatão dos Guararapes'},
      {'id': 78, 'name': 'Olinda'},
      {'id': 79, 'name': 'Caruaru'},
      {'id': 80, 'name': 'Petrolina'},
      {'id': 81, 'name': 'Paulista'},
    ],
    18: [ // Piauí
      {'id': 82, 'name': 'Teresina'},
      {'id': 83, 'name': 'Parnaíba'},
      {'id': 84, 'name': 'Picos'},
      {'id': 85, 'name': 'Piripiri'},
      {'id': 86, 'name': 'Floriano'},
    ],
    19: [ // Rio de Janeiro
      {'id': 200, 'name': 'Rio de Janeiro'},
      {'id': 201, 'name': 'São Gonçalo'},
      {'id': 202, 'name': 'Duque de Caxias'},
      {'id': 203, 'name': 'Nova Iguaçu'},
      {'id': 204, 'name': 'Niterói'},
      {'id': 205, 'name': 'Campos dos Goytacazes'},
      {'id': 206, 'name': 'Belford Roxo'},
      {'id': 207, 'name': 'São João de Meriti'},
      {'id': 208, 'name': 'Petrópolis'},
      {'id': 209, 'name': 'Volta Redonda'},
    ],
    20: [ // Rio Grande do Norte
      {'id': 87, 'name': 'Natal'},
      {'id': 88, 'name': 'Mossoró'},
      {'id': 89, 'name': 'Parnamirim'},
      {'id': 90, 'name': 'São Gonçalo do Amarante'},
      {'id': 91, 'name': 'Macaíba'},
    ],
    21: [ // Rio Grande do Sul
      {'id': 92, 'name': 'Porto Alegre'},
      {'id': 93, 'name': 'Caxias do Sul'},
      {'id': 94, 'name': 'Pelotas'},
      {'id': 95, 'name': 'Canoas'},
      {'id': 96, 'name': 'Santa Maria'},
      {'id': 97, 'name': 'Gravataí'},
      {'id': 98, 'name': 'Viamão'},
    ],
    22: [ // Rondônia
      {'id': 99, 'name': 'Porto Velho'},
      {'id': 110, 'name': 'Ji-Paraná'},
      {'id': 111, 'name': 'Ariquemes'},
      {'id': 112, 'name': 'Vilhena'},
      {'id': 113, 'name': 'Cacoal'},
    ],
    23: [ // Roraima
      {'id': 114, 'name': 'Boa Vista'},
      {'id': 115, 'name': 'Rorainópolis'},
      {'id': 116, 'name': 'Caracaraí'},
      {'id': 117, 'name': 'Alto Alegre'},
    ],
    24: [ // Santa Catarina
      {'id': 118, 'name': 'Florianópolis'},
      {'id': 119, 'name': 'Joinville'},
      {'id': 120, 'name': 'Blumenau'},
      {'id': 121, 'name': 'São José'},
      {'id': 122, 'name': 'Criciúma'},
      {'id': 123, 'name': 'Chapecó'},
    ],
    25: [ // São Paulo
      {'id': 300, 'name': 'São Paulo'},
      {'id': 301, 'name': 'Guarulhos'},
      {'id': 302, 'name': 'Campinas'},
      {'id': 303, 'name': 'São Bernardo do Campo'},
      {'id': 304, 'name': 'Santo André'},
      {'id': 305, 'name': 'Osasco'},
      {'id': 306, 'name': 'Ribeirão Preto'},
      {'id': 307, 'name': 'Sorocaba'},
      {'id': 308, 'name': 'Mauá'},
      {'id': 309, 'name': 'São José dos Campos'},
    ],
    26: [ // Sergipe
      {'id': 124, 'name': 'Aracaju'},
      {'id': 125, 'name': 'Nossa Senhora do Socorro'},
      {'id': 126, 'name': 'Lagarto'},
      {'id': 127, 'name': 'Itabaiana'},
      {'id': 128, 'name': 'São Cristóvão'},
    ],
    27: [ // Tocantins
      {'id': 129, 'name': 'Palmas'},
      {'id': 130, 'name': 'Araguaína'},
      {'id': 131, 'name': 'Gurupi'},
      {'id': 132, 'name': 'Porto Nacional'},
      {'id': 133, 'name': 'Paraíso do Tocantins'},
    ],
  };
  
  // ✅ Lista dinâmica de cidades baseada no estado selecionado
  List<Map<String, dynamic>> get _availableCities {
    return _citiesByState[_selectedState] ?? [
      {'id': 1, 'name': 'Capital do Estado'}
    ];
  }
  
  final List<Map<String, String>> _genders = [
    {'value': 'male', 'name': 'Masculino'},
    {'value': 'female', 'name': 'Feminino'},
    {'value': 'other', 'name': 'Outro'},
  ];

  @override
  void dispose() {
    // LIMPEZA DOS CONTROLLERS
    _nameController.dispose();
    _cpfController.dispose();
    _phoneController.dispose();
    _emailController.dispose();
    _passwordController.dispose();
    _confirmPasswordController.dispose();
    _specialtyController.dispose();
    _rateController.dispose();
    super.dispose();
  }

  // 🚀 MÉTODO DE CADASTRO FUNCIONAL
  Future<void> _handleRegister() async {
    print('🔥 Iniciando processo de cadastro...');
    
    if (!_formKey.currentState!.validate()) {
      print('❌ Formulário inválido');
      return;
    }

    print('✅ Formulário válido! Iniciando cadastro...');
    print('📝 Nome: ${_nameController.text}');
    print('📝 Email: ${_emailController.text}');
    print('📝 CPF: ${_cpfController.text}');
    print('📝 Telefone: ${_phoneController.text}');
    print('📝 Tipo: ${_isPersonalTrainer ? 'Personal Trainer' : 'Cliente'}');

    setState(() {
      _isLoading = true;
    });

    try {
      final registrationService = RegistrationService();
      
      if (_isPersonalTrainer) {
        print('🚀 Registrando Personal Trainer...');
        await registrationService.registerPersonalTrainer(
          name: _nameController.text.trim(),
          email: _emailController.text.trim(),
          cpf: _cpfController.text.trim(),
          phone: _phoneController.text.trim(),
          password: _passwordController.text,
          specialties: _specialtyController.text.trim(),
          gender: _selectedGender,
          hourlyRate: double.tryParse(_rateController.text) ?? 50.0,
        );
      } else {
        print('🚀 Registrando Cliente...');
        await registrationService.registerCustomer(
          name: _nameController.text.trim(),
          email: _emailController.text.trim(),
          cpf: _cpfController.text.trim(),
          phone: _phoneController.text.trim(),
          password: _passwordController.text,
          state: _selectedState,
          city: _selectedCity,
        );
      }
      
      print('✅ Cadastro realizado com sucesso!');

      if (mounted) {
        setState(() {
          _isLoading = false;
        });
        
        // Redirecionar para login
        Navigator.pushReplacementNamed(context, AppRoutes.login);
      }
    } catch (e) {
      print('❌ Erro no cadastro: $e');
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
        
        // Mostrar erro apenas no terminal, sem SnackBar para produção
        print('❌ Cadastro falhou: $e');
      }
    }
  }



  @override
  Widget build(BuildContext context) {
    return OutsideFrameScreen(
      content: Form(
        key: _formKey,
        child: Column(
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceEvenly,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                TextButton(
                  onPressed: () => Navigator.pushNamed(context, AppRoutes.login),
                  child: const Text(
                    'Acessar',
                    style: TextStyle(
                      color: Color(0xFFC8C7CC),
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
                SizedBox(
                  child: TextButton(
                    onPressed: () => Navigator.pushNamed(context, AppRoutes.register),
                    child: Column(
                      children: [
                        const Text(
                          'Cadastrar',
                          style: TextStyle(
                            color: Colors.black,
                            fontSize: 20,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        Container(
                          margin: const EdgeInsets.only(top: 10),
                          width: 45,
                          height: 5,
                          decoration: BoxDecoration(
                            borderRadius: BorderRadius.circular(100),
                            gradient: LinearGradient(
                              colors: [
                                Theme.of(context).colorScheme.primary,
                                Theme.of(context).colorScheme.secondary,
                              ],
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
            const Divider(color: Color(0xFFC8C7CC)),
            const SizedBox(height: 20),
            
            // TOGGLE TIPO DE USUÁRIO
            Container(
              width: double.infinity,
              decoration: BoxDecoration(
                color: Colors.grey[100],
                borderRadius: BorderRadius.circular(8),
              ),
              child: Row(
                children: [
                  Expanded(
                    child: GestureDetector(
                      onTap: () => setState(() => _isPersonalTrainer = false),
                      child: Container(
                        padding: const EdgeInsets.symmetric(vertical: 12),
                        decoration: BoxDecoration(
                          color: !_isPersonalTrainer ? Theme.of(context).colorScheme.primary : Colors.transparent,
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Text(
                          'Cliente',
                          textAlign: TextAlign.center,
                          style: TextStyle(
                            color: !_isPersonalTrainer ? Colors.white : Colors.grey[600],
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ),
                  ),
                  Expanded(
                    child: GestureDetector(
                      onTap: () => setState(() => _isPersonalTrainer = true),
                      child: Container(
                        padding: const EdgeInsets.symmetric(vertical: 12),
                        decoration: BoxDecoration(
                          color: _isPersonalTrainer ? Theme.of(context).colorScheme.primary : Colors.transparent,
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Text(
                          'Personal Trainer',
                          textAlign: TextAlign.center,
                          style: TextStyle(
                            color: _isPersonalTrainer ? Colors.white : Colors.grey[600],
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
            
            const SizedBox(height: 20),
            
            // BOTÃO FACEBOOK MANTIDO (PLACEHOLDER)
            InkWell(
              onTap: () {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Login com Facebook em desenvolvimento')),
                );
              },
              borderRadius: BorderRadius.circular(10),
              child: Ink(
                height: 50,
                decoration: BoxDecoration(
                  color: const Color(0xFF2672CB),
                  borderRadius: BorderRadius.circular(8),
                ),
                padding: const EdgeInsets.symmetric(vertical: 8),
                width: double.infinity,
                child: const Center(
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(Icons.facebook_outlined, color: Colors.white),
                      SizedBox(width: 10),
                      Text(
                        'Cadastrar com Facebook',
                        style: TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                          fontSize: 17,
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
            const SizedBox(height: 20),
            const SizedBox(
              width: double.infinity,
              child: Text(
                'Informações Pessoais',
                textAlign: TextAlign.start,
                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
              ),
            ),
            const Divider(color: Color(0xFFC8C7CC)),
            
            // ✅ CAMPOS COM CONTROLLERS E VALIDAÇÃO
            TextFormField(
              controller: _nameController,
              decoration: const InputDecoration(
                labelText: 'Nome Completo',
                prefixIcon: Icon(Icons.person_2_outlined),
                border: OutlineInputBorder(),
              ),
              validator: (value) {
                if (value == null || value.isEmpty) {
                  return 'Nome é obrigatório';
                }
                if (value.length < 2) {
                  return 'Nome deve ter pelo menos 2 caracteres';
                }
                return null;
              },
            ),
            const SizedBox(height: 16),
            
            TextFormField(
              controller: _cpfController,
              decoration: const InputDecoration(
                labelText: 'CPF',
                prefixIcon: Icon(Icons.card_membership_outlined),
                border: OutlineInputBorder(),
              ),
              keyboardType: TextInputType.number,
              validator: (value) {
                if (value == null || value.isEmpty) {
                  return 'CPF é obrigatório';
                }
                // Remove formatação
                final cleanValue = value.replaceAll(RegExp(r'[^0-9]'), '');
                if (cleanValue.length != 11) {
                  return 'CPF deve ter 11 dígitos';
                }
                return null;
              },
            ),
            const SizedBox(height: 16),
            
            TextFormField(
              controller: _phoneController,
              decoration: const InputDecoration(
                labelText: 'Telefone',
                prefixIcon: Icon(Icons.phone_outlined),
                border: OutlineInputBorder(),
              ),
              keyboardType: TextInputType.phone,
              validator: (value) {
                if (value == null || value.isEmpty) {
                  return 'Telefone é obrigatório';
                }
                // Remove formatação
                final cleanValue = value.replaceAll(RegExp(r'[^0-9]'), '');
                if (cleanValue.length < 10) {
                  return 'Telefone inválido';
                }
                return null;
              },
            ),
            const SizedBox(height: 20),
            
            const SizedBox(
              width: double.infinity,
              child: Text(
                'Informações de Acesso',
                textAlign: TextAlign.start,
                style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
              ),
            ),
            const Divider(color: Color(0xFFC8C7CC)),
            
            TextFormField(
              controller: _emailController,
              decoration: const InputDecoration(
                labelText: 'E-mail',
                prefixIcon: Icon(Icons.email_outlined),
                border: OutlineInputBorder(),
              ),
              keyboardType: TextInputType.emailAddress,
              validator: (value) {
                if (value == null || value.isEmpty) {
                  return 'Email é obrigatório';
                }
                if (!RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$').hasMatch(value)) {
                  return 'Email inválido';
                }
                return null;
              },
            ),
            const SizedBox(height: 16),
            
            TextFormField(
              controller: _passwordController,
              decoration: const InputDecoration(
                labelText: 'Senha',
                prefixIcon: Icon(Icons.lock_outlined),
                border: OutlineInputBorder(),
              ),
              obscureText: true,
              validator: (value) {
                if (value == null || value.isEmpty) {
                  return 'Senha é obrigatória';
                }
                if (value.length < 6) {
                  return 'Senha deve ter pelo menos 6 caracteres';
                }
                return null;
              },
            ),
            const SizedBox(height: 16),
            
            TextFormField(
              controller: _confirmPasswordController,
              decoration: const InputDecoration(
                labelText: 'Confirmar Senha',
                prefixIcon: Icon(Icons.lock_outlined),
                border: OutlineInputBorder(),
              ),
              obscureText: true,
              validator: (value) {
                if (value == null || value.isEmpty) {
                  return 'Confirmação de senha é obrigatória';
                }
                if (_passwordController.text != value) {
                  return 'Senhas não coincidem';
                }
                return null;
              },
            ),
            const SizedBox(height: 20),
            
            // ✅ CAMPOS ESPECÍFICOS CONDICIONAIS
            if (_isPersonalTrainer) ...[
              const SizedBox(
                width: double.infinity,
                child: Text(
                  'Informações Profissionais',
                  textAlign: TextAlign.start,
                  style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                ),
              ),
              const Divider(color: Color(0xFFC8C7CC)),
              
              // ESPECIALIDADE
              TextFormField(
                controller: _specialtyController,
                decoration: const InputDecoration(
                  labelText: 'Especialidade',
                  prefixIcon: Icon(Icons.fitness_center_outlined),
                  border: OutlineInputBorder(),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Especialidade é obrigatória';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              
              // GÊNERO
              DropdownButtonFormField<String>(
                value: _selectedGender,
                decoration: const InputDecoration(
                  labelText: 'Gênero',
                  prefixIcon: Icon(Icons.person_outline),
                  border: OutlineInputBorder(),
                ),
                items: _genders.map((gender) {
                  return DropdownMenuItem<String>(
                    value: gender['value'],
                    child: Text(gender['name']!),
                  );
                }).toList(),
                onChanged: (value) {
                  setState(() {
                    _selectedGender = value!;
                  });
                },
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Gênero é obrigatório';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              
              // TAXA POR HORA
              TextFormField(
                controller: _rateController,
                decoration: const InputDecoration(
                  labelText: 'Taxa por Hora (R\$)',
                  prefixIcon: Icon(Icons.attach_money_outlined),
                  border: OutlineInputBorder(),
                ),
                keyboardType: TextInputType.number,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Taxa por hora é obrigatória';
                  }
                  final rate = double.tryParse(value);
                  if (rate == null || rate <= 0) {
                    return 'Digite uma taxa válida';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
            ] else ...[
              const SizedBox(
                width: double.infinity,
                child: Text(
                  'Localização',
                  textAlign: TextAlign.start,
                  style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                ),
              ),
              const Divider(color: Color(0xFFC8C7CC)),
              
              // ESTADO
              DropdownButtonFormField<int>(
                value: _selectedState,
                decoration: const InputDecoration(
                  labelText: 'Estado',
                  prefixIcon: Icon(Icons.location_on_outlined),
                  border: OutlineInputBorder(),
                ),
                items: _states.map((state) {
                  return DropdownMenuItem<int>(
                    value: state['id'],
                    child: Text('${state['name']} (${state['uf']})'),
                  );
                }).toList(),
                onChanged: (value) {
                  setState(() {
                    _selectedState = value!;
                    // ✅ Resetar cidade para a primeira disponível do novo estado
                    final firstCity = _availableCities.isNotEmpty ? _availableCities.first['id'] : 1;
                    _selectedCity = firstCity;
                  });
                },
                validator: (value) {
                  if (value == null) {
                    return 'Estado é obrigatório';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              
              // CIDADE
              DropdownButtonFormField<int>(
                key: ValueKey(_selectedState), // ✅ Força rebuild quando estado muda
                value: _selectedCity,
                decoration: const InputDecoration(
                  labelText: 'Cidade',
                  prefixIcon: Icon(Icons.location_city_outlined),
                  border: OutlineInputBorder(),
                ),
                items: _availableCities.map((city) {
                  return DropdownMenuItem<int>(
                    value: city['id'],
                    child: Text(city['name']),
                  );
                }).toList(),
                onChanged: (value) {
                  setState(() {
                    _selectedCity = value!;
                  });
                },
                validator: (value) {
                  if (value == null) {
                    return 'Cidade é obrigatória';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
            ],
            
            RichText(
              textAlign: TextAlign.center,
              text: const TextSpan(
                style: TextStyle(fontSize: 14, color: Colors.black),
                children: [
                  TextSpan(
                    text: 'Ao clicar em cadastrar, você concorda com nossos ',
                  ),
                  TextSpan(
                    text: 'Termos',
                    style: TextStyle(fontWeight: FontWeight.bold),
                  ),
                  TextSpan(text: '.'),
                ],
              ),
            ),
            const SizedBox(height: 20),
            
            // ✅ BOTÃO FUNCIONAL COM LOADING
            SizedBox(
              width: double.infinity,
              height: 50,
              child: ElevatedButton(
                onPressed: _isLoading ? null : _handleRegister,
                style: ElevatedButton.styleFrom(
                  backgroundColor: Theme.of(context).colorScheme.primary,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(8),
                  ),
                ),
                child: _isLoading
                    ? const SizedBox(
                        width: 20,
                        height: 20,
                        child: CircularProgressIndicator(
                          color: Colors.white,
                          strokeWidth: 2,
                        ),
                      )
                    : Text(
                        'Cadastrar como ${_isPersonalTrainer ? 'Personal Trainer' : 'Cliente'}',
                        style: const TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
              ),
            ),
            

          ],
        ),
      ),
    );
  }
}
