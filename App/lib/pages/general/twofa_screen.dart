import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:peach_fit_app/components/button_component.dart';
import 'package:peach_fit_app/components/outside/outside_frame_screen.dart';
import 'package:peach_fit_app/util/app_routes.dart';
import 'package:peach_fit_app/services/auth_service.dart';

class TwofaScreen extends StatefulWidget {
  TwofaScreen({super.key});

  @override
  State<TwofaScreen> createState() => _TwofaScreenState();
}

class _TwofaScreenState extends State<TwofaScreen> {
  final TextEditingController _pinController = TextEditingController();
  AuthService? _authService;
  String? _email;
  bool _isLoading = false;


  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    final args = ModalRoute.of(context)?.settings.arguments;
    if (args != null && args is Map<String, dynamic>) {
      _email = args['email'] as String?;
      _authService = args['authService'] as AuthService?;
    }
    _authService ??= AuthService();
  }

  @override
  void dispose() {
    _pinController.dispose();
    super.dispose();
  }

  // ✅ VERIFICAR CÓDIGO 2FA (simplificado - apenas botão manual)
  Future<void> _verifyCode() async {
    if (_isLoading) return; // 🛡️ Evita múltiplos cliques
    
    final code = _pinController.text.trim();
    
    if (code.isEmpty) {
      _showMessage('Por favor, insira o código', Colors.orange);
      return;
    }

    if (code.length != 6) {
      _showMessage('O código deve ter exatamente 6 dígitos', Colors.orange);
      return;
    }

    if (!RegExp(r'^\d{6}$').hasMatch(code)) {
      _showMessage('O código deve conter apenas números', Colors.orange);
      return;
    }

    setState(() {
      _isLoading = true;
    });

    try {
      await _authService!.verifyCode(_email!, code);
      
      if (mounted) {
        // 🎉 Sucesso - navegar imediatamente
        Navigator.pushNamedAndRemoveUntil(
          context,
          AppRoutes.clientDashboard,
          (route) => false,
        );
      }
    } catch (e) {
      if (mounted) {
        _showMessage('Código inválido ou expirado. Solicite um novo código.', Colors.red);
        _pinController.clear();
      }
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  // ✅ REENVIAR CÓDIGO
  Future<void> _handleResendCode() async {
    if (_email == null || _email!.isEmpty) {
      _showMessage('Email não encontrado. Volte para a tela de login.', Colors.red);
      return;
    }

    setState(() {
      _isLoading = true;
    });

    try {
      await _authService!.sendCode(_email!);
      
      if (mounted) {
        _showMessage('Novo código enviado para seu email', Colors.green);
        _pinController.clear();
      }
    } catch (e) {
      if (mounted) {
        _showMessage('Erro ao reenviar código. Tente novamente.', Colors.red);
      }
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  void _showMessage(String message, Color color) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: color,
        duration: const Duration(seconds: 3),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return OutsideFrameScreen(
      content: SizedBox(
        width: double.infinity,
        child: Column(
          children: [
            // ✅ TÍTULO
            const Text(
              'Verificação de Email',
              style: TextStyle(
                fontSize: 28,
                fontWeight: FontWeight.bold,
                color: Colors.black87,
              ),
              textAlign: TextAlign.center,
            ),
            
            const SizedBox(height: 15),
            
            // ✅ DESCRIÇÃO
            Text(
              _email != null 
                  ? 'Digite o código de 6 dígitos enviado para:\n$_email' 
                  : 'Digite o código de 6 dígitos enviado para seu email',
              style: const TextStyle(
                fontSize: 16,
                color: Colors.grey,
                height: 1.4,
              ),
              textAlign: TextAlign.center,
            ),
            
            const SizedBox(height: 40),
            
            // ✅ CAMPO DE CÓDIGO MELHORADO
            Container(
              margin: const EdgeInsets.symmetric(horizontal: 20),
              child: Column(
                children: [
                  // Campo para inserir código
                  Container(
                    decoration: BoxDecoration(
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(
                        color: Colors.grey.shade300,
                        width: 2,
                      ),
                      color: Colors.white,
                    ),
                    child: TextFormField(
                      controller: _pinController,
                      decoration: const InputDecoration(
                        labelText: 'Código de 6 dígitos',
                        hintText: '000000',
                        border: InputBorder.none,
                        contentPadding: EdgeInsets.all(20),
                        counterText: '',
                      ),
                      textAlign: TextAlign.center,
                      keyboardType: TextInputType.number,
                      maxLength: 6,
                      style: const TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        letterSpacing: 8,
                        color: Colors.black87,
                      ),
                      inputFormatters: [
                        FilteringTextInputFormatter.digitsOnly,
                      ],
                      onChanged: (value) {
                        // 🔧 APENAS remove foco quando completo - SEM verificação automática
                        if (value.length == 6) {
                          FocusScope.of(context).unfocus();
                        }
                      },
                    ),
                  ),
                  
                  const SizedBox(height: 25),
                  
                  // Botão verificar
                  SizedBox(
                    width: double.infinity,
                    height: 55,
                    child: ElevatedButton(
                      onPressed: _isLoading ? null : _verifyCode,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFF4CAF50),
                        foregroundColor: Colors.white,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(12),
                        ),
                        elevation: 2,
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
                          : const Text(
                              'Verificar Código',
                              style: TextStyle(
                                fontSize: 16, 
                                fontWeight: FontWeight.w600
                              ),
                            ),
                    ),
                  ),
                  
                  const SizedBox(height: 20),
                  
                  // Botão reenviar código  
                  TextButton(
                    onPressed: _isLoading ? null : _handleResendCode,
                    child: const Text(
                      'Não recebeu o código? Reenviar',
                      style: TextStyle(
                        color: Colors.grey, 
                        fontSize: 14,
                        decoration: TextDecoration.underline,
                      ),
                    ),
                  ),
                ],
              ),
            ),
            
            const SizedBox(height: 30),
            
            // ✅ BOTÃO VOLTAR
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text(
                'Voltar para Login',
                style: TextStyle(
                  color: Colors.grey,
                  fontSize: 14,
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
