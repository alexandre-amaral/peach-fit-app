import 'package:flutter/material.dart';
import 'package:peach_fit_app/components/inside/client_drawer_component.dart';

class ClientHistoryScreen extends StatelessWidget {
  ClientHistoryScreen({super.key});

  final List<Map<String, dynamic>> history = [
    {
      'personal': 'Marcos Antônio',
      'spec': {'title': 'Musculação', 'icon': Icons.sports_gymnastics_outlined},
      'value': 'R\$ 100',
      'date': '10/10/2023',
      'time': '20:00',
      'status': const Text('Aceito', style: TextStyle(color: Colors.green)),
    },
    {
      'personal': 'Antonio Marcos',
      'spec': {'title': 'Pilates', 'icon': Icons.fitness_center_outlined},
      'value': 'R\$ 100',
      'date': '10/10/2023',
      'time': '20:00',
      'status': const Text('Cancelado', style: TextStyle(color: Colors.red)),
    },
    {
      'personal': 'Guilherme Marcos',
      'spec': {'title': 'Pilates', 'icon': Icons.fitness_center_outlined},
      'value': 'R\$ 100',
      'date': '10/10/2023',
      'time': '20:00',
      'status': const Text('Confirmar', style: TextStyle(color: Colors.blue)),
    },
  ];

  @override
  Widget build(BuildContext context) {
    MediaQueryData media = MediaQuery.of(context);
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
        title: const Text('Histórico'),
      ),
      body: Stack(
        children: [
          Container(
            height: 100,
            decoration: BoxDecoration(
              gradient: LinearGradient(
                colors: [
                  Theme.of(context).colorScheme.primary,
                  Theme.of(context).colorScheme.secondary,
                ],
              ),
            ),
          ),
          SizedBox(
            width: media.size.width * 0.95,
            height: media.size.height * 0.9,
            child: ListView.builder(
              itemBuilder: (context, index) {
                return Container(
                  margin: EdgeInsets.only(
                    bottom: 10,
                    left: media.size.width * 0.05,
                  ),
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(12),
                    color: Colors.white,
                    boxShadow: [
                      BoxShadow(
                        color: Colors.black.withValues(alpha: 0.25),
                        spreadRadius: 1,
                        blurRadius:
                            10, // Aumenta o desfoque para mais intensidade
                        offset: const Offset(0, 4),
                      ),
                    ],
                  ),
                  child: Padding(
                    padding: const EdgeInsets.all(8.0),
                    child: Column(
                      children: [
                        Row(
                          children: [
                            const CircleAvatar(
                              radius: 10,
                              backgroundImage: AssetImage(
                                'assets/images/user.png',
                              ),
                            ),
                            const SizedBox(width: 10),
                            Text(
                              history[index]['personal'],
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 20),
                        Row(
                          children: [
                            Icon(history[index]['spec']['icon']),
                            const SizedBox(width: 10),
                            Text(
                              history[index]['spec']['title'],
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                          ],
                        ),
                        const Divider(),
                        Row(
                          children: [
                            const Icon(Icons.attach_money_outlined),
                            Text(
                              history[index]['value'],
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            const Spacer(),
                            history[index]['status'],
                            Icon(
                              Icons.keyboard_arrow_right_outlined,
                              color: Colors.grey[300],
                            ),
                          ],
                        ),
                      ],
                    ),
                  ),
                );
              },
              itemCount: history.length,
            ),
          ),
        ],
      ),
      drawer: const Drawer(
        backgroundColor: Colors.white,
        child: ClientDrawerComponent(),
      ),
    );
  }
}
