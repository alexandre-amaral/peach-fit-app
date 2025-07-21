import 'package:flutter/material.dart';
import 'package:peach_fit_app/components/inside/client_drawer_component.dart';
import 'package:peach_fit_app/controllers/user/get_user_controller.dart';
import 'package:peach_fit_app/models/user_model.dart';
import 'package:peach_fit_app/util/app_routes.dart';

class ClientSettingsScreen extends StatelessWidget {
  const ClientSettingsScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<UserModel?>(
      future: GetUserController().getUser(),
      builder: (context, snapshot) {
        // Loading state
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Scaffold(
            body: Center(
              child: CircularProgressIndicator(),
            ),
          );
        }
        
        // Error state
        if (snapshot.hasError) {
          return Scaffold(
            body: Center(
              child: Text('Erro: ${snapshot.error}'),
            ),
          );
        }
        
        // No user data
        if (!snapshot.hasData || snapshot.data == null) {
          return const Scaffold(
            body: Center(
              child: Text('Usuário não encontrado'),
            ),
          );
        }
        
        final UserModel user = snapshot.data!;
        print(user);
        
        return Scaffold(
      appBar: AppBar(
        elevation: 0,
        flexibleSpace: Container(
          decoration: BoxDecoration(
            gradient: LinearGradient(
              colors: [
                Theme.of(context).colorScheme.primary,
                Theme.of(context).colorScheme.secondary,
              ], // Gradiente do botão
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
            ),
          ),
        ),
        title: const Text('Configurações'),
      ),
      body: Container(
        color: Colors.grey[100],
        child: Column(
          children: [
            const SizedBox(height: 20),
            Container(
              color: Colors.white,
              child: ListTile(
                leading: const CircleAvatar(
                  radius: 30,
                  backgroundImage: AssetImage('assets/images/user.png'),
                ),
                title: Text(user.name ?? 'Marco Antonio de Oliveira'),
                subtitle: const Text('Cliente'),
                trailing: const Icon(Icons.keyboard_arrow_right_outlined),
                onTap:
                    () => {Navigator.pushNamed(context, AppRoutes.editAccount)},
              ),
            ),
            const SizedBox(height: 40),
            Container(
              color: Colors.white,
              child: Column(
                children: [
                  ListTile(
                    title: const Text('Notificações'),
                    trailing: const Icon(Icons.keyboard_arrow_right_outlined),
                    onTap:
                        () => {
                          Navigator.pushNamed(context, AppRoutes.notifications),
                        },
                  ),
                  ListTile(
                    title: const Text('Pagamentos'),
                    trailing: const Icon(Icons.keyboard_arrow_right_outlined),
                    onTap: () => {},
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),
            Container(
              color: Colors.white,
              child: Column(
                children: [
                  ListTile(
                    title: const Text('Termos e Condições'),
                    trailing: const Icon(Icons.keyboard_arrow_right_outlined),
                    onTap: () => {},
                  ),
                  ListTile(
                    title: const Text('Suporte 24hrs'),
                    trailing: const Icon(Icons.keyboard_arrow_right_outlined),
                    onTap: () => {},
                  ),
                  ListTile(
                    title: const Text('Sobre'),
                    trailing: const Icon(Icons.keyboard_arrow_right_outlined),
                    onTap: () => {},
                  ),
                ],
              ),
            ),
          ],
        ),
      ),

      drawer: const Drawer(
        backgroundColor: Colors.white,
        child: ClientDrawerComponent(),
      ),
    );
      },
    );
  }
}
