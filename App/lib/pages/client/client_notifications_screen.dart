import 'package:flutter/material.dart';
import 'package:peach_fit_app/components/inside/client_drawer_component.dart';

class ClientNotificationsScreen extends StatelessWidget {
  ClientNotificationsScreen({super.key});

  final List<Map<String, dynamic>> history = [
    {
      'icon': {'data': Icons.local_offer, 'color': Colors.orange},
      'title': 'Promoção',
      'subtitle': 'Obrigado! Recebemos seu deposito.',
      'date': '25/06/2023',
    },
    {
      'icon': {'data': Icons.check_circle, 'color': Colors.blue},
      'title': 'Aula concluida',
      'subtitle': 'Musculação',
      'date': '25/06/2023',
    },
    {
      'icon': {'data': Icons.check_circle, 'color': Colors.blue},
      'title': 'Aula concluida',
      'subtitle': 'Pilates',
      'date': '25/06/2023',
    },
    {
      'icon': {'data': Icons.check_circle, 'color': Colors.blue},
      'title': 'Aula concluida',
      'subtitle': 'Musculação',
      'date': '25/06/2023',
    },
    {
      'icon': {'data': Icons.check_circle, 'color': Colors.blue},
      'title': 'Aula concluida',
      'subtitle': 'Pilates',
      'date': '25/06/2023',
    },
    {
      'icon': {'data': Icons.check_circle, 'color': Colors.blue},
      'title': 'Aula concluida',
      'subtitle': 'Musculação',
      'date': '25/06/2023',
    },
    {
      'icon': {'data': Icons.check_circle, 'color': Colors.blue},
      'title': 'Aula concluida',
      'subtitle': 'Pilates',
      'date': '25/06/2023',
    },
  ];

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
        title: const Text('Notificações'),
      ),
      body: ListView.separated(
        itemCount: history.length,
        itemBuilder: (context, index) {
          return ListTile(
            leading: Icon(
              history[index]['icon']['data'],
              color: history[index]['icon']['color'],
            ),
            title: Text(history[index]['title']),
            subtitle: Text(history[index]['subtitle']),
            trailing: Text(history[index]['date']),
          );
        },
        separatorBuilder:
            (context, index) => Divider(
              color: Colors.grey.shade300,
              thickness: 1,
              indent: 16,
              endIndent: 16,
            ),
      ),

      drawer: const Drawer(
        backgroundColor: Colors.white,
        child: ClientDrawerComponent(),
      ),
    );
  }
}
