import 'package:flutter/material.dart';
import 'package:peach_fit_app/components/inside/client_drawer_component.dart';
import 'package:peach_fit_app/util/app_routes.dart';

class ClientEditAccountScreen extends StatelessWidget {
  const ClientEditAccountScreen({super.key});

  @override
  Widget build(BuildContext context) {
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
        title: const Text('Minha conta'),
        actions: const [
          Padding(
            padding: EdgeInsets.only(right: 10),
            child: CircleAvatar(
              radius: 25,
              backgroundImage: AssetImage('assets/images/user.png'),
            ),
          ),
        ],
      ),
      body: Container(
        color: Colors.grey[100],
        child: Column(
          children: [
            const SizedBox(height: 30),
            Container(
              color: Colors.white,
              child: Column(
                children: [
                  ListTile(
                    title: const Text('Nome'),
                    subtitle: const Text('Marcos Antônio de Oliveira Santos'),
                    trailing: const Icon(Icons.keyboard_arrow_right_outlined),
                    onTap:
                        () => {
                          Navigator.pushNamed(context, AppRoutes.notifications),
                        },
                  ),
                  ListTile(
                    title: const Text('Email'),
                    subtitle: const Text('teste@site.com'),
                    trailing: const Icon(Icons.keyboard_arrow_right_outlined),
                    onTap: () => {},
                  ),
                  ListTile(
                    title: const Text('Gênero'),
                    subtitle: const Text('Homem'),
                    trailing: const Icon(Icons.keyboard_arrow_right_outlined),
                    onTap: () => {},
                  ),
                  ListTile(
                    title: const Text('Data de Nascimento'),
                    subtitle: const Text('06/07/1993'),
                    trailing: const Icon(Icons.keyboard_arrow_right_outlined),
                    onTap: () => {},
                  ),
                  ListTile(
                    title: const Text('Telefone'),
                    subtitle: const Text('(11) 1 1111-1111'),
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
  }
}
