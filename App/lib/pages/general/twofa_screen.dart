import 'package:flutter/material.dart';
import 'package:peach_fit_app/components/button_component.dart';
import 'package:peach_fit_app/components/outside/outside_frame_screen.dart';
import 'package:peach_fit_app/components/outside/pinput_component.dart';
import 'package:peach_fit_app/util/app_routes.dart';
import 'package:peach_fit_app/services/auth_service.dart';
import 'package:quickalert/quickalert.dart';

class TwofaScreen extends StatefulWidget {
  TwofaScreen({super.key});

  @override
  State<TwofaScreen> createState() => _TwofaScreenState();
}

class _TwofaScreenState extends State<TwofaScreen> {
  // ✅ CONTROLADORES E SERVIÇOS
  final TextEditingController _pinController = TextEditingController();
  final AuthService _authService = AuthService();
  
  bool _isLoading = false;
  bool _isResending = false;
  String? _email;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    // ✅ RECEBER EMAIL COMO ARGUMENTO DA NAVEGAÇÃO
    final args = ModalRoute.of(context)?.settings.arguments;
    if (args != null && args is String) {
      _email = args;
    }
  }

  @override
  void dispose() {
    _pinController.dispose();
    super.dispose();
  }

  // ✅ VERIFICAR CÓDIGO 2FA
  Future<void> _handleVerifyCode() async {
    if (_pinController.text.trim().isEmpty) {
      await QuickAlert.show(
        context: context,
        type: QuickAlertType.warning,
        title: 'Código Obrigatório',
        text: 'Digite o código que foi enviado para seu email.',
        confirmBtnText: 'OK',
      );
      return;
    }

    if (_email == null || _email!.isEmpty) {
      await QuickAlert.show(
        context: context,
        type: QuickAlertType.error,
        title: 'Erro',
        text: 'Email não encontrado. Volte para a tela de login.',
        confirmBtnText: 'OK',
      );
      return;
    }

    setState(() {
      _isLoading = true;
    });

    try {
      final user = await _authService.verifyCode(
        _email!,
        _pinController.text.trim(),
      );

      if (mounted) {
        await QuickAlert.show(
          context: context,
          type: QuickAlertType.success,
          title: 'Login Realizado',
          text: 'Bem-vindo(a), ${user.name}!',
          confirmBtnText: 'Continuar',
        );

        // ✅ NAVEGAÇÃO CONDICIONAL POR TIPO DE USUÁRIO
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
        } else {
          // Tipo desconhecido - ir para onboard ou default
          Navigator.of(context).pushNamedAndRemoveUntil(
            AppRoutes.onboardClient,
            (route) => false,
          );
        }
      }
    } catch (e) {
      if (mounted) {
        await QuickAlert.show(
          context: context,
          type: QuickAlertType.error,
          title: 'Código Inválido',
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

  // ✅ REENVIAR CÓDIGO
  Future<void> _handleResendCode() async {
    if (_email == null || _email!.isEmpty) {
      await QuickAlert.show(
        context: context,
        type: QuickAlertType.error,
        title: 'Erro',
        text: 'Email não encontrado. Volte para a tela de login.',
        confirmBtnText: 'OK',
      );
      return;
    }

    setState(() {
      _isResending = true;
    });

    try {
      await _authService.sendCode(_email!);
      
      if (mounted) {
        await QuickAlert.show(
          context: context,
          type: QuickAlertType.success,
          title: 'Código Reenviado',
          text: 'Um novo código foi enviado para seu email.',
          confirmBtnText: 'OK',
        );
        
        // Limpar o campo do PIN
        _pinController.clear();
      }
    } catch (e) {
      if (mounted) {
        await QuickAlert.show(
          context: context,
          type: QuickAlertType.error,
          title: 'Erro ao Reenviar',
          text: e.toString().replaceFirst('Exception: ', ''),
          confirmBtnText: 'OK',
        );
      }
    } finally {
      if (mounted) {
        setState(() {
          _isResending = false;
        });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return OutsideFrameScreen(
      content: SizedBox(
        width: double.infinity,
        child: Column(
          children: [
            const SizedBox(
              width: double.infinity,
              child: Text(
                "Verificação de Email",
                style: TextStyle(fontSize: 25, fontWeight: FontWeight.bold),
              ),
            ),
            Container(
              margin: const EdgeInsets.only(bottom: 20, top: 10),
              width: double.infinity,
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Digite o código que foi enviado para:',
                    style: TextStyle(fontSize: 14),
                  ),
                  const SizedBox(height: 4),
                  Text(
                    _email ?? 'seu email',
                    style: const TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                      color: Colors.blue,
                    ),
                  ),
                  const SizedBox(height: 8),
                  GestureDetector(
                    onTap: () => Navigator.pop(context),
                    child: const Text(
                      'Alterar email',
                      style: TextStyle(
                        fontSize: 12,
                        color: Colors.grey,
                        decoration: TextDecoration.underline,
                      ),
                    ),
                  ),
                ],
              ),
            ),
            Container(
              margin: const EdgeInsets.symmetric(vertical: 30),
              child: Column(
                children: [
                  // ✅ CAMPO PIN COM CONTROLLER
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 20),
                    child: TextFormField(
                      controller: _pinController,
                      decoration: const InputDecoration(
                        labelText: 'Código de 6 dígitos',
                        border: OutlineInputBorder(),
                        counterText: '',
                      ),
                      textAlign: TextAlign.center,
                      keyboardType: TextInputType.number,
                      maxLength: 6,
                      style: const TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        letterSpacing: 8,
                      ),
                    ),
                  ),
                  
                  const SizedBox(height: 16),
                  
                  // ✅ BOTÃO REENVIAR FUNCIONAL
                  TextButton(
                    onPressed: _isResending ? null : _handleResendCode,
                    child: _isResending
                        ? const Row(
                            mainAxisSize: MainAxisSize.min,
                            children: [
                              SizedBox(
                                width: 16,
                                height: 16,
                                child: CircularProgressIndicator(strokeWidth: 2),
                              ),
                              SizedBox(width: 8),
                              Text('Reenviando...'),
                            ],
                          )
                        : const Text(
                            'Reenviar Email',
                            style: TextStyle(
                              fontSize: 16,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                  ),
                ],
              ),
            ),
            
            // ✅ BOTÃO VERIFICAR FUNCIONAL
            SizedBox(
              width: double.infinity,
              height: 50,
              child: ElevatedButton(
                onPressed: _isLoading ? null : _handleVerifyCode,
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
                    : const Text(
                        'Verificar',
                        style: TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
              ),
            ),
            
            const SizedBox(height: 20),
            
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
