import 'package:flutter/material.dart';
import 'package:peach_fit_app/util/app_routes.dart';

class ClientDrawerComponent extends StatelessWidget {
  const ClientDrawerComponent({super.key});

  @override
  Widget build(BuildContext context) {
    List<Map<String, dynamic>> options = [
      {
        'icon': Icons.home_max_outlined,
        'name': 'Inicio',
        'onTap':
            () => {Navigator.popAndPushNamed(context, AppRoutes.homeClient)},
      },
      {
        'icon': Icons.history_outlined,
        'name': 'Histórico',
        'onTap': () => {Navigator.popAndPushNamed(context, AppRoutes.history)},
      },
      {
        'icon': Icons.notifications_outlined,
        'name': 'Notificações',
        'onTap':
            () => {Navigator.popAndPushNamed(context, AppRoutes.notifications)},
      },
      {
        'icon': Icons.settings_outlined,
        'name': 'Configurações',
        'onTap': () => {Navigator.popAndPushNamed(context, AppRoutes.settings)},
      },
      {
        'icon': Icons.logout_outlined,
        'name': 'Sair',
        'onTap':
            () => {
              Navigator.pushNamedAndRemoveUntil(
                context,
                AppRoutes.login,
                (Route<dynamic> route) => false,
              ),
            },
      },
    ];
    return Column(
      children: [
        Container(
          decoration: BoxDecoration(
            gradient: LinearGradient(
              colors: [
                Theme.of(context).colorScheme.primary,
                Theme.of(context).colorScheme.secondary,
              ],
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
            ),
          ),
          width: MediaQuery.of(context).size.width,
          child: const Padding(
            padding: EdgeInsets.only(left: 20.0, top: 60),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.start,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                CircleAvatar(
                  foregroundColor: Colors.white,
                  radius: 40,
                  backgroundImage: AssetImage('assets/images/user.png'),
                ),
                SizedBox(height: 20),
                Text(
                  'Marcos Antônio de Oliveira Santos',
                  style: TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                SizedBox(height: 20),
              ],
            ),
          ),
        ),
        SizedBox(
          height: MediaQuery.of(context).size.height * 0.6,
          child: ListView.builder(
            itemCount: options.length,
            itemBuilder: (context, index) {
              return ListTile(
                leading: Icon(options[index]['icon']),
                title: Text(options[index]['name']),
                onTap: options[index]['onTap'],
              );
            },
          ),
        ),
      ],
    );
  }
}
