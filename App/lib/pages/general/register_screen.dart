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
  
  // Controllers para os campos
  final _nameController = TextEditingController();
  final _cpfController = TextEditingController();
  final _phoneController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();
  
  bool _isLoading = false;
  bool _isPersonalTrainer = false;  // Toggle entre Cliente e Personal Trainer

  @override
  void dispose() {
    // LIMPEZA DOS CONTROLLERS
    _nameController.dispose();
    _cpfController.dispose();
    _phoneController.dispose();
    _emailController.dispose();
    _passwordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  // ✅ MÉTODO PARA FAZER O REGISTRO
  Future<void> _handleRegister() async {
    print('🔥 DEBUG: Botão cadastrar clicado!');
    
    // Mostrar SnackBar para confirmar que o botão foi clicado
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(
        content: Text('🔥 Botão funcionando! Validando...'),
        backgroundColor: Colors.blue,
      ),
    );
    
    // Primeiro, verificar se o form é válido
    if (_formKey.currentState == null) {
      print('❌ DEBUG: FormKey é null!');
      return;
    }
    
    print('🔥 DEBUG: Validando formulário...');
    if (!_formKey.currentState!.validate()) {
      print('❌ DEBUG: Validação do formulário falhou!');
      // Mostrar quais campos estão inválidos
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('Por favor, corrija os campos em vermelho'),
          backgroundColor: Colors.red,
        ),
      );
      return;
    }

    print('✅ DEBUG: Formulário válido! Iniciando cadastro...');
    print('📝 DEBUG: Nome: ${_nameController.text}');
    print('📝 DEBUG: Email: ${_emailController.text}');
    print('📝 DEBUG: CPF: ${_cpfController.text}');
    print('📝 DEBUG: Telefone: ${_phoneController.text}');
    print('📝 DEBUG: Tipo: ${_isPersonalTrainer ? 'Personal Trainer' : 'Cliente'}');

    setState(() {
      _isLoading = true;
    });

    try {
      print('🚀 DEBUG: Simulando cadastro...');
      
      // Simulação de delay da API
      await Future.delayed(const Duration(seconds: 2));
      
      print('✅ DEBUG: Cadastro simulado com sucesso!');

      // Sucesso - mostrar mensagem
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
        
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('✅ Cadastro simulado com sucesso! (Ainda não conectou com API)'),
            backgroundColor: Colors.green,
          ),
        );
      }
    } catch (e) {
      print('❌ DEBUG: Erro no cadastro: $e');
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
        
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('❌ Erro: $e'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  // 🧪 MÉTODO PARA TESTAR A CONEXÃO HTTP
  Future<void> _testConnection() async {
    print('🔗 DEBUG: Testando conexão HTTP...');
    try {
      // Teste simples
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('🧪 Testando conexão...'),
          backgroundColor: Colors.blue,
        ),
      );
      
      // Aguardar um pouco para simular
      await Future.delayed(const Duration(seconds: 1));
      
      print('✅ DEBUG: Teste de conexão simulado!');
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('✅ Teste concluído! Verifique os logs no console.'),
            backgroundColor: Colors.green,
          ),
        );
      }
    } catch (e) {
      print('❌ DEBUG: Erro no teste: $e');
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('❌ Erro: $e'),
            backgroundColor: Colors.red,
          ),
        );
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
            const SizedBox(height: 16),
            
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
            
            const SizedBox(height: 10),
            
            // 🧪 BOTÃO DE TESTE TEMPORÁRIO
            SizedBox(
              width: double.infinity,
              height: 40,
              child: OutlinedButton(
                onPressed: _testConnection,
                style: OutlinedButton.styleFrom(
                  foregroundColor: Colors.orange,
                  side: const BorderSide(color: Colors.orange),
                ),
                child: const Text(
                  '🧪 TESTE CONEXÃO API',
                  style: TextStyle(fontSize: 12, fontWeight: FontWeight.bold),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
