import 'package:flutter/material.dart';
import 'package:peach_fit_app/components/button_component.dart';
import 'package:peach_fit_app/components/inside/client_drawer_component.dart';
import 'package:peach_fit_app/components/inside/google_maps_component.dart';
import 'package:peach_fit_app/controllers/user/get_user_controller.dart';
import 'package:peach_fit_app/models/user_model.dart';
import 'package:peach_fit_app/util/app_routes.dart';
import 'package:quickalert/quickalert.dart';

class ClientDashboardScreen extends StatelessWidget {
  ClientDashboardScreen({super.key});

  void _showOptions(BuildContext context) {
    List<Map<String, dynamic>> options = [
      {
        'icon': Icons.sports_gymnastics_outlined,
        'title': 'Musculação',
        'distance': 'Próximo à você',
        'price': 'R\$ 80',
        'time': '50 min',
      },
      {
        'icon': Icons.fitness_center_outlined,
        'title': 'Pilates',
        'distance': '10 km',
        'price': 'R\$ 120',
        'time': '50 min',
      },
      {
        'icon': Icons.sports_gymnastics_outlined,
        'title': 'Musculação',
        'distance': 'Próximo à você',
        'price': 'R\$ 80',
        'time': '50 min',
      },
      {
        'icon': Icons.fitness_center_outlined,
        'title': 'Pilates',
        'distance': '10 km',
        'price': 'R\$ 120',
        'time': '50 min',
      },
      {
        'icon': Icons.sports_gymnastics_outlined,
        'title': 'Musculação',
        'distance': 'Próximo à você',
        'price': 'R\$ 80',
        'time': '50 min',
      },
      {
        'icon': Icons.fitness_center_outlined,
        'title': 'Pilates',
        'distance': '10 km',
        'price': 'R\$ 120',
        'time': '50 min',
      },
      {
        'icon': Icons.sports_gymnastics_outlined,
        'title': 'Musculação',
        'distance': 'Próximo à você',
        'price': 'R\$ 80',
        'time': '50 min',
      },
      {
        'icon': Icons.fitness_center_outlined,
        'title': 'Pilates',
        'distance': '10 km',
        'price': 'R\$ 120',
        'time': '50 min',
      },
      {
        'icon': Icons.sports_gymnastics_outlined,
        'title': 'Musculação',
        'distance': 'Próximo à você',
        'price': 'R\$ 80',
        'time': '50 min',
      },
      {
        'icon': Icons.fitness_center_outlined,
        'title': 'Pilates',
        'distance': '10 km',
        'price': 'R\$ 120',
        'time': '50 min',
      },
      {
        'icon': Icons.sports_gymnastics_outlined,
        'title': 'Musculação',
        'distance': 'Próximo à você',
        'price': 'R\$ 80',
        'time': '50 min',
      },
      {
        'icon': Icons.fitness_center_outlined,
        'title': 'Pilates',
        'distance': '10 km',
        'price': 'R\$ 120',
        'time': '50 min',
      },
      {
        'icon': Icons.sports_gymnastics_outlined,
        'title': 'Musculação',
        'distance': 'Próximo à você',
        'price': 'R\$ 80',
        'time': '50 min',
      },
      {
        'icon': Icons.fitness_center_outlined,
        'title': 'Pilates',
        'distance': '10 km',
        'price': 'R\$ 120',
        'time': '50 min',
      },
      {
        'icon': Icons.sports_gymnastics_outlined,
        'title': 'Musculação',
        'distance': 'Próximo à você',
        'price': 'R\$ 80',
        'time': '50 min',
      },
      {
        'icon': Icons.fitness_center_outlined,
        'title': 'Pilates',
        'distance': '10 km',
        'price': 'R\$ 120',
        'time': '50 min',
      },
    ];

    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) {
        return Padding(
          padding: const EdgeInsets.all(16),
          child: SizedBox(
            height: 300, // altura máxima do bottom sheet
            child: ListView.builder(
              itemCount: options.length,
              itemBuilder: (context, index) {
                return ListTile(
                  leading: Icon(options[index]['icon']),
                  title: Text(options[index]['title']),
                  subtitle: Text(options[index]['distance']),
                  trailing: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text(
                        options[index]['price'],
                        style: const TextStyle(fontWeight: FontWeight.bold),
                      ),
                      Text(
                        options[index]['time'],
                        style: const TextStyle(fontSize: 10),
                      ),
                    ],
                  ),
                  onTap: () {
                    _showConfirmProfessional(context);
                  },
                );
              },
            ),
          ),
        );
      },
    );
  }

  void _showConfirmProfessional(BuildContext context) {
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) {
        return Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Container(
              decoration: BoxDecoration(color: Colors.grey[100]),
              child: const ListTile(
                leading: CircleAvatar(
                  backgroundImage: AssetImage('assets/images/user.png'),
                ),
                title: Text('Marcos Antônio de Oliveira Santos'),
                subtitle: Row(
                  children: [
                    Icon(Icons.star, color: Colors.amberAccent),
                    Text('4.9'),
                  ],
                ),
                trailing: Icon(Icons.chat_outlined),
              ),
            ),
            const Padding(
              padding: EdgeInsets.only(left: 15),
              child: Row(
                children: [
                  CircleAvatar(radius: 12),
                  SizedBox(width: 5),
                  CircleAvatar(radius: 12),
                  SizedBox(width: 5),
                  CircleAvatar(radius: 12),
                  SizedBox(width: 5),
                  Text('25 recomendam', style: TextStyle(color: Colors.grey)),
                ],
              ),
            ),
            const SizedBox(
              width: double.infinity,
              child: Padding(
                padding: EdgeInsets.only(top: 15.0, left: 15.0),
                child: Text(
                  'Musculação',
                  style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
                  textAlign: TextAlign.start,
                ),
              ),
            ),
            const Divider(),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                SizedBox(
                  width: MediaQuery.of(context).size.width * 0.3,
                  child: const Icon(Icons.sports_gymnastics_outlined, size: 40),
                ),
                SizedBox(
                  width: MediaQuery.of(context).size.width * 0.7,
                  child: const Row(
                    mainAxisAlignment: MainAxisAlignment.spaceAround,
                    children: [
                      Column(
                        children: [
                          Text(
                            'DISTÂNCIA',
                            style: TextStyle(color: Colors.grey, fontSize: 12),
                          ),
                          Text(
                            '10 km',
                            style: TextStyle(fontWeight: FontWeight.bold),
                          ),
                        ],
                      ),
                      Column(
                        children: [
                          Text(
                            'TEMPO AULA',
                            style: TextStyle(color: Colors.grey, fontSize: 12),
                          ),
                          Text(
                            '1h 30 min',
                            style: TextStyle(fontWeight: FontWeight.bold),
                          ),
                        ],
                      ),
                      Column(
                        children: [
                          Text(
                            'VALOR',
                            style: TextStyle(color: Colors.grey, fontSize: 12),
                          ),
                          Text(
                            'R\$ 100',
                            style: TextStyle(fontWeight: FontWeight.bold),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ],
            ),
            const SizedBox(height: 20),
            Padding(
              padding: const EdgeInsets.all(8.0),
              child: ButtonComponent(
                label: 'Confirmar',
                onPressed:
                    () => {
                      QuickAlert.show(
                        context: context,
                        type: QuickAlertType.success,
                        title: 'Treino Agendado!',
                        text:
                            'Seu treino foi confirmado. \n O treinador irá encontrar você na academia em 21 minutos.',
                        confirmBtnText: 'OK',
                        onConfirmBtnTap:
                            () => {
                              Navigator.pushNamedAndRemoveUntil(
                                context,
                                AppRoutes.homeClient,
                                (route) => false,
                              ),
                            },
                      ),
                    },
              ),
            ),
          ],
        );
      },
    );
  }

  final GlobalKey<ScaffoldState> scaffoldKey = GlobalKey<ScaffoldState>();

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
        
        return Scaffold(
          key: scaffoldKey,
      drawer: const Drawer(
        backgroundColor: Colors.white,
        child: ClientDrawerComponent(),
      ),
      body: SizedBox(
        child: Stack(
          children: [
            const GoogleMapsComponent(),
            Positioned(
              top: 50,
              left: 10,
              width: MediaQuery.of(context).size.width * 0.93,
              child: Row(
                children: [
                  const SizedBox(width: 10),
                  InkWell(
                    onTap: () => {scaffoldKey.currentState!.openDrawer()},
                    child: CircleAvatar(
                      radius: 20,
                      backgroundImage:
                          user.avatar == null || user.avatar == ''
                              ? const AssetImage('assets/images/user.png')
                              : NetworkImage(user.avatar ?? ''),
                    ),
                  ),
                  const SizedBox(width: 10),
                  Expanded(
                    child: ElevatedButton(
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.white,
                        foregroundColor: Colors.black,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(40),
                        ),
                      ),
                      onPressed: () => {_showOptions(context)},
                      child: const Text('Procurar Profissional'),
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
      },
    );
  }
}
