import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:peach_fit_app/pages/client/client_dashboard_screen.dart';
import 'package:peach_fit_app/pages/client/client_history_screen.dart';
import 'package:peach_fit_app/pages/client/client_notifications_screen.dart';
import 'package:peach_fit_app/pages/client/client_onboard_end_screen.dart';
import 'package:peach_fit_app/pages/client/client_onboard_screen.dart';
import 'package:peach_fit_app/pages/client/client_settings_screen.dart';
import 'package:peach_fit_app/pages/client/settings/client_edit_account_screen.dart';
import 'package:peach_fit_app/pages/general/login_screen.dart';
import 'package:peach_fit_app/pages/general/register_screen.dart';
import 'package:peach_fit_app/pages/general/twofa_screen.dart';
import 'package:peach_fit_app/pages/trainer/home_trainer_screen.dart';
import 'package:peach_fit_app/util/app_routes.dart';
import 'package:peach_fit_app/util/api_endpoints.dart'; // ✅ Importado para debug
import 'package:peach_fit_app/services/auth_service.dart';

void main() {
  runApp(const PeachApp());
}

class PeachApp extends StatelessWidget {
  const PeachApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Peach Fit Delivery',
      theme: ThemeData(
        fontFamily: GoogleFonts.getFont('Poppins').fontFamily,
        useMaterial3: false,
        colorScheme: ColorScheme.fromSwatch().copyWith(
          primary: const Color(0xFFFF5E63),
          secondary: const Color(0xFFFE9F69),
        ),
      ),
      initialRoute: AppRoutes.splash,  // ✅ Rota inicial
      routes: {
        AppRoutes.splash: (context) => const SplashScreen(),  // ✅ Adicionado
        AppRoutes.login: (context) => LoginScreen(),
        AppRoutes.register: (context) => const RegisterScreen(),
        AppRoutes.twofa: (context) => TwofaScreen(),

        //Rotas de Personal Trainer
        AppRoutes.homeTrainer: (context) => const HomeTrainerScreen(),

        //Rotas de cliente
        AppRoutes.homeClient: (context) => ClientDashboardScreen(),
        AppRoutes.onboardClient: (context) => const ClientOnboardScreen(),
        AppRoutes.endOnboardClient: (context) => const ClientOnboardEndScreen(),

        //Rotas Comum
        AppRoutes.history: (context) => ClientHistoryScreen(),
        AppRoutes.notifications: (context) => ClientNotificationsScreen(),
        AppRoutes.settings: (context) => const ClientSettingsScreen(),
        AppRoutes.editAccount: (context) => ClientEditAccountScreen(),
      },
    );
  }
}

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  final AuthService _authService = AuthService();

  @override
  void initState() {
    super.initState();
    _checkAuthStatus();
  }

  Future<void> _checkAuthStatus() async {
    try {
      // Simular carregamento por pelo menos 2 segundos
      await Future.delayed(const Duration(seconds: 2));
      
      // Tentar auto-login - verificar se há usuário logado
      final isLoggedIn = await _authService.autoLogin();
      
      if (mounted) {
        if (isLoggedIn) {
          final user = await _authService.getCurrentUser();
          if (user != null) {
            // NAVEGAÇÃO CONDICIONAL POR TIPO DE USUÁRIO
            if (user.isPersonalTrainer) {
              Navigator.of(context).pushReplacementNamed(AppRoutes.homeTrainer);
            } else if (user.isCustomer) {
              Navigator.of(context).pushReplacementNamed(AppRoutes.homeClient);
            } else {
              // Tipo desconhecido, ir para login
              Navigator.of(context).pushReplacementNamed(AppRoutes.login);
            }
          } else {
            // Falha ao obter dados do usuário, ir para login
            Navigator.of(context).pushReplacementNamed(AppRoutes.login);
          }
        } else {
          // Não logado, ir para login
          Navigator.of(context).pushReplacementNamed(AppRoutes.login);
        }
      }
    } catch (e) {
      // Erro no auto-login, ir para tela de login
      if (mounted) {
        Navigator.of(context).pushReplacementNamed(AppRoutes.login);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Theme.of(context).colorScheme.primary,
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            // Logo ou ícone do app
            Container(
              width: 120,
              height: 120,
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(20),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withOpacity(0.1),
                    blurRadius: 10,
                    spreadRadius: 2,
                  ),
                ],
              ),
              child: const Icon(
                Icons.fitness_center,
                size: 60,
                color: Color(0xFFFF5E63),
              ),
            ),
            
            const SizedBox(height: 24),
            
            // Nome do app
            const Text(
              'Peach Fit',
              style: TextStyle(
                fontSize: 32,
                fontWeight: FontWeight.bold,
                color: Colors.white,
              ),
            ),
            
            const SizedBox(height: 8),
            
            const Text(
              'Personal Trainer Delivery',
              style: TextStyle(
                fontSize: 16,
                color: Colors.white70,
              ),
            ),
            
            const SizedBox(height: 48),
            
            // Loading indicator
            const CircularProgressIndicator(
              valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
            ),
            
            const SizedBox(height: 16),
            
            const Text(
              'Carregando...',
              style: TextStyle(
                fontSize: 14,
                color: Colors.white70,
              ),
            ),
          ],
        ),
      ),
    );
  }
}
