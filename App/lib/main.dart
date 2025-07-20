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
import 'package:peach_fit_app/util/app_routes.dart';

void main() {
  runApp(const PeachApp());
}

class PeachApp extends StatelessWidget {
  const PeachApp({super.key});

  // This widget is the root of your application.
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
      routes: {
        AppRoutes.login: (context) => LoginScreen(),
        AppRoutes.register: (context) => const RegisterScreen(),
        AppRoutes.twofa: (context) => TwofaScreen(),

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
