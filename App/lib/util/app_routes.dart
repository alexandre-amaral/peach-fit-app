class AppRoutes {
  static const splash = '/';  // ✅ SplashScreen como rota inicial
  static const login = '/login';  // ✅ Mudado de '/' para '/login'
  static const register = '/register';
  static const twofa = '/2fa';

  //Rotas do Personal Trainer
  static const homeTrainer = '/homeTrainer';
  //Rotas do Cliente (Comum)
  static const homeClient = '/homeClient';
  static const onboardClient = '/onboardClient';
  static const endOnboardClient = '/endOnboardClient';

  //Rotas Comum Logado
  static const history = '/history';
  static const notifications = '/notifications';
  static const settings = '/settings';
  static const editAccount = '/editAccount';
}
