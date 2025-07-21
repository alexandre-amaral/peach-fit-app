import 'package:flutter/material.dart';
import 'package:peach_fit_app/components/button_component.dart';
import 'package:peach_fit_app/components/outside/outside_frame_screen.dart';
import 'package:peach_fit_app/components/outside/textfield_component.dart';
import 'package:peach_fit_app/util/app_routes.dart';
import 'package:peach_fit_app/services/auth_service.dart';
import 'package:quickalert/quickalert.dart';

class LoginScreen extends StatefulWidget {
  LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  // CONTROLADORES E SERVIÇOS
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final AuthService _authService = AuthService();
  final _formKey = GlobalKey<FormState>();
  
  bool _isLoading = false;
  bool _useDirectLogin = true; // Toggle entre login direto e 2FA

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  // LOGIN DIRETO (SEM 2FA)
  Future<void> _handleDirectLogin() async {
    if (!_formKey.currentState!.validate()) {
      return;
    }

    setState(() {
      _isLoading = true;
    });

    try {
      final user = await _authService.login(
        _emailController.text.trim(),
        _passwordController.text,
      );

      if (mounted) {
        // NAVEGAÇÃO CONDICIONAL POR TIPO DE USUÁRIO
        if (user.isPersonalTrainer) {
          Navigator.of(context).pushNamedAndRemoveUntil(
            AppRoutes.homeTrainer,
            (route) => false,
          );
        } else if (user.isCustomer) {
          Navigator.of(context).pushNamedAndRemoveUntil(
            AppRoutes.homeClient,
            (route) => false,
          );
        }
      }
    } catch (e) {
      if (mounted) {
        await QuickAlert.show(
          context: context,
          type: QuickAlertType.error,
          title: 'Erro no Login',
          text: e.toString().replaceFirst('Exception: ', ''),
          confirmBtnText: 'OK',
        );
      }
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  // ENVIAR CÓDIGO 2FA
  Future<void> _handleSendCode() async {
    if (_emailController.text.trim().isEmpty) {
      await QuickAlert.show(
        context: context,
        type: QuickAlertType.warning,
        title: 'Email Obrigatório',
        text: 'Digite seu email para receber o código de verificação.',
        confirmBtnText: 'OK',
      );
      return;
    }

    setState(() {
      _isLoading = true;
    });

    try {
      await _authService.sendCode(_emailController.text.trim());
      
      if (mounted) {
        await QuickAlert.show(
          context: context,
          type: QuickAlertType.success,
          title: 'Código Enviado',
          text: 'Verifique seu email e digite o código na próxima tela.',
          confirmBtnText: 'OK',
        );
        
        // Navegar para tela de 2FA passando o email
        Navigator.pushNamed(
          context, 
          AppRoutes.twofa,
          arguments: _emailController.text.trim(),
        );
      }
    } catch (e) {
      if (mounted) {
        await QuickAlert.show(
          context: context,
          type: QuickAlertType.error,
          title: 'Erro ao Enviar Código',
          text: e.toString().replaceFirst('Exception: ', ''),
          confirmBtnText: 'OK',
        );
      }
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  String? _validateEmail(String? value) {
    if (value == null || value.isEmpty) {
      return 'Email é obrigatório';
    }
    if (!RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$').hasMatch(value)) {
      return 'Email inválido';
    }
    return null;
  }

  String? _validatePassword(String? value) {
    if (!_useDirectLogin) return null; // Não validar senha no modo 2FA
    if (value == null || value.isEmpty) {
      return 'Senha é obrigatória';
    }
    return null;
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
                SizedBox(
                  child: TextButton(
                    onPressed: () => Navigator.pushNamed(context, AppRoutes.login),
                    child: Column(
                      children: [
                        const Text(
                          'Acessar',
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
                TextButton(
                  onPressed: () => Navigator.pushNamed(context, AppRoutes.register),
                  child: const Text(
                    'Cadastrar',
                    style: TextStyle(
                      color: Color(0xFFC8C7CC),
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ],
            ),
            const Divider(color: Color(0xFFC8C7CC)),
            
            // TOGGLE TIPO DE LOGIN
            Container(
              margin: const EdgeInsets.symmetric(vertical: 16),
              width: double.infinity,
              decoration: BoxDecoration(
                color: Colors.grey[100],
                borderRadius: BorderRadius.circular(8),
              ),
              child: Row(
                children: [
                  Expanded(
                    child: GestureDetector(
                      onTap: () => setState(() => _useDirectLogin = true),
                      child: Container(
                        padding: const EdgeInsets.symmetric(vertical: 12),
                        decoration: BoxDecoration(
                          color: _useDirectLogin ? Theme.of(context).colorScheme.primary : Colors.transparent,
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Text(
                          'Login Direto',
                          textAlign: TextAlign.center,
                          style: TextStyle(
                            color: _useDirectLogin ? Colors.white : Colors.grey[600],
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ),
                  ),
                  Expanded(
                    child: GestureDetector(
                      onTap: () => setState(() => _useDirectLogin = false),
                      child: Container(
                        padding: const EdgeInsets.symmetric(vertical: 12),
                        decoration: BoxDecoration(
                          color: !_useDirectLogin ? Theme.of(context).colorScheme.primary : Colors.transparent,
                          borderRadius: BorderRadius.circular(8),
                        ),
                        child: Text(
                          'Código por Email',
                          textAlign: TextAlign.center,
                          style: TextStyle(
                            color: !_useDirectLogin ? Colors.white : Colors.grey[600],
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
            
            Padding(
              padding: const EdgeInsets.all(10.0),
              child: Column(
                children: [
                  const SizedBox(height: 30),
                  
                  // CAMPO EMAIL COM VALIDAÇÃO
                  TextFormField(
                    controller: _emailController,
                    decoration: const InputDecoration(
                      labelText: 'Email',
                      prefixIcon: Icon(Icons.email_outlined),
                      border: OutlineInputBorder(),
                    ),
                    keyboardType: TextInputType.emailAddress,
                    validator: _validateEmail,
                  ),
                  
                  const SizedBox(height: 16),
                  
                  // CAMPO SENHA (CONDICIONAL)
                  if (_useDirectLogin) ...[
                    TextFormField(
                      controller: _passwordController,
                      decoration: const InputDecoration(
                        labelText: 'Senha',
                        prefixIcon: Icon(Icons.lock_outlined),
                        border: OutlineInputBorder(),
                      ),
                      obscureText: true,
                      validator: _validatePassword,
                    ),
                    const SizedBox(height: 20),
                  ] else ...[
                    const Text(
                      'Um código será enviado para seu email',
                      style: TextStyle(
                        color: Colors.grey,
                        fontSize: 14,
                      ),
                      textAlign: TextAlign.center,
                    ),
                    const SizedBox(height: 20),
                  ],
                  
                  // BOTÃO FUNCIONAL
                  SizedBox(
                    width: double.infinity,
                    height: 50,
                    child: ElevatedButton(
                      onPressed: _isLoading 
                          ? null 
                          : (_useDirectLogin ? _handleDirectLogin : _handleSendCode),
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
                              _useDirectLogin ? 'Acessar' : 'Enviar Código',
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
          ],
        ),
      ),
      footer: Container(
        margin: const EdgeInsets.only(top: 20),
        child: Padding(
          padding: const EdgeInsets.all(10.0),
          child: Column(
            children: [
              // BOTÃO FACEBOOK PLACEHOLDER
              InkWell(
                onTap: () {
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Login com Facebook em desenvolvimento')),
                  );
                },
                borderRadius: BorderRadius.circular(10),
                child: Ink(
                  height: 60,
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
                          'Entrar com Facebook',
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
              RichText(
                textAlign: TextAlign.center,
                text: const TextSpan(
                  style: TextStyle(fontSize: 14, color: Colors.black),
                  children: [
                    TextSpan(
                      text: 'Ao clicar em iniciar, você concorda com nossos ',
                    ),
                    TextSpan(
                      text: 'Termos',
                      style: TextStyle(fontWeight: FontWeight.bold),
                    ),
                    TextSpan(text: '.'),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
