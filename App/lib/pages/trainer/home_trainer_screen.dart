import 'package:flutter/material.dart';
import 'package:peach_fit_app/components/inside/inside_frame_component.dart';
import 'package:peach_fit_app/services/auth_service.dart';
import 'package:peach_fit_app/models/user_model.dart';
import 'package:peach_fit_app/util/app_routes.dart';

class HomeTrainerScreen extends StatefulWidget {
  const HomeTrainerScreen({super.key});

  @override
  State<HomeTrainerScreen> createState() => _HomeTrainerScreenState();
}

class _HomeTrainerScreenState extends State<HomeTrainerScreen> {
  final AuthService _authService = AuthService();
  UserModel? _currentUser;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadUserData();
  }

  Future<void> _loadUserData() async {
    try {
      final user = await _authService.getCurrentUser();
      setState(() {
        _currentUser = user;
        _isLoading = false;
      });
    } catch (e) {
      setState(() {
        _isLoading = false;
      });
    }
  }

  Future<void> _handleLogout() async {
    try {
      await _authService.logout();
      if (mounted) {
        Navigator.of(context).pushNamedAndRemoveUntil(
          AppRoutes.login,
          (route) => false,
        );
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Erro ao fazer logout: $e')),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const Scaffold(
        body: Center(
          child: CircularProgressIndicator(),
        ),
      );
    }

    return InsideFrameComponent(
      title: 'Personal Trainer',
      content: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Header com informações do usuário
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        CircleAvatar(
                          radius: 30,
                          backgroundColor: Theme.of(context).colorScheme.primary,
                          child: _currentUser?.avatar != null
                              ? ClipRRect(
                                  borderRadius: BorderRadius.circular(30),
                                  child: Image.network(
                                    _currentUser!.avatar!,
                                    width: 60,
                                    height: 60,
                                    fit: BoxFit.cover,
                                    errorBuilder: (context, error, stackTrace) =>
                                        const Icon(Icons.person, color: Colors.white, size: 30),
                                  ),
                                )
                              : const Icon(Icons.person, color: Colors.white, size: 30),
                        ),
                        const SizedBox(width: 16),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                _currentUser?.name ?? 'Personal Trainer',
                                style: const TextStyle(
                                  fontSize: 18,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                              Text(
                                _currentUser?.email ?? '',
                                style: TextStyle(
                                  fontSize: 14,
                                  color: Colors.grey[600],
                                ),
                              ),
                            ],
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
            
            const SizedBox(height: 24),
            
            // Menu de opções
            const Text(
              'Menu Principal',
              style: TextStyle(
                fontSize: 20,
                fontWeight: FontWeight.bold,
              ),
            ),
            
            const SizedBox(height: 16),
            
            // Grid de opções
            Expanded(
              child: GridView.count(
                crossAxisCount: 2,
                crossAxisSpacing: 16,
                mainAxisSpacing: 16,
                children: [
                  _buildMenuCard(
                    icon: Icons.calendar_today,
                    title: 'Agenda',
                    subtitle: 'Gerenciar horários',
                    onTap: () {
                      // TODO: Implementar navegação para agenda
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text('Agenda em desenvolvimento')),
                      );
                    },
                  ),
                  _buildMenuCard(
                    icon: Icons.group,
                    title: 'Clientes',
                    subtitle: 'Ver meus clientes',
                    onTap: () {
                      // TODO: Implementar navegação para clientes
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text('Clientes em desenvolvimento')),
                      );
                    },
                  ),
                  _buildMenuCard(
                    icon: Icons.fitness_center,
                    title: 'Treinos',
                    subtitle: 'Sessões agendadas',
                    onTap: () {
                      // TODO: Implementar navegação para treinos
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text('Treinos em desenvolvimento')),
                      );
                    },
                  ),
                  _buildMenuCard(
                    icon: Icons.attach_money,
                    title: 'Financeiro',
                    subtitle: 'Ganhos e pagamentos',
                    onTap: () {
                      // TODO: Implementar navegação para financeiro
                      ScaffoldMessenger.of(context).showSnackBar(
                        const SnackBar(content: Text('Financeiro em desenvolvimento')),
                      );
                    },
                  ),
                  _buildMenuCard(
                    icon: Icons.notifications,
                    title: 'Notificações',
                    subtitle: 'Mensagens e avisos',
                    onTap: () => Navigator.pushNamed(context, AppRoutes.notifications),
                  ),
                  _buildMenuCard(
                    icon: Icons.settings,
                    title: 'Configurações',
                    subtitle: 'Perfil e preferências',
                    onTap: () => Navigator.pushNamed(context, AppRoutes.settings),
                  ),
                ],
              ),
            ),
            
            // Botão de logout
            Container(
              width: double.infinity,
              margin: const EdgeInsets.only(top: 16),
              child: ElevatedButton.icon(
                onPressed: _handleLogout,
                icon: const Icon(Icons.logout),
                label: const Text('Sair'),
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.red,
                  foregroundColor: Colors.white,
                  padding: const EdgeInsets.symmetric(vertical: 12),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildMenuCard({
    required IconData icon,
    required String title,
    required String subtitle,
    required VoidCallback onTap,
  }) {
    return Card(
      elevation: 2,
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(8),
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(
                icon,
                size: 32,
                color: Theme.of(context).colorScheme.primary,
              ),
              const SizedBox(height: 8),
              Text(
                title,
                style: const TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.bold,
                ),
                textAlign: TextAlign.center,
              ),
              const SizedBox(height: 4),
              Text(
                subtitle,
                style: TextStyle(
                  fontSize: 12,
                  color: Colors.grey[600],
                ),
                textAlign: TextAlign.center,
              ),
            ],
          ),
        ),
      ),
    );
  }
} 