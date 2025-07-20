import 'package:flutter/material.dart';
import 'package:peach_fit_app/util/app_routes.dart';

class ClientOnboardScreen extends StatefulWidget {
  const ClientOnboardScreen({super.key});

  @override
  State<ClientOnboardScreen> createState() => _ClientOnboardScreenState();
}

class _ClientOnboardScreenState extends State<ClientOnboardScreen> {
  final PageController _pageController = PageController();
  int _currentIndex = 0;

  List<Map<String, String>> onboardContent() {
    return [
      {
        'image': 'assets/images/onboard/01.png',
        'title': 'Solicitar Treino',
        'text':
            'Solicite um treino e seja buscado por \n um personal trainer da comunidade \n próxima',
      },
      {
        'image': 'assets/images/onboard/02.png',
        'title': 'Confirme seu Personal',
        'text':
            'Uma grande rede de treinadores \n ajudam você a encontrar seu treino\n ideal',
      },
      {
        'image': 'assets/images/onboard/03.png',
        'title': 'Rastreie seu Treinador',
        'text':
            'Conheça seu personal trainer com\n antecedência e consiga visualizar a \n localização atual em tempo real no \n mapa',
      },
    ];
  }

  @override
  Widget build(BuildContext context) {
    MediaQueryData media = MediaQuery.of(context);
    var content = onboardContent();

    return Scaffold(
      body: Column(
        children: [
          SizedBox(height: media.size.height * 0.08),
          Expanded(
            child: PageView.builder(
              controller: _pageController,
              itemCount: content.length,
              onPageChanged: (index) => setState(() => _currentIndex = index),
              itemBuilder: (context, index) {
                return Column(
                  children: [
                    Image.asset(content[index]['image']!),
                    const SizedBox(height: 30),
                    Text(
                      content[index]['title']!,
                      style: const TextStyle(
                        fontSize: 28,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    const SizedBox(height: 20),
                    SizedBox(
                      width: media.size.width * 0.85,
                      child: FittedBox(
                        child: Text(
                          content[index]['text']!,
                          textAlign: TextAlign.center,
                          style: const TextStyle(fontSize: 16),
                        ),
                      ),
                    ),
                  ],
                );
              },
            ),
          ),
          if (_currentIndex == content.length - 1)
            Center(
              child: Padding(
                padding: const EdgeInsets.only(bottom: 50),
                child: ElevatedButton(
                  onPressed: () {
                    Navigator.pushNamed(context, AppRoutes.endOnboardClient);
                  },
                  style: ElevatedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 50,
                      vertical: 16,
                    ),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(30),
                    ),
                  ),
                  child: const Text(
                    'COMEÇAR AGORA',
                    style: TextStyle(fontSize: 18),
                  ),
                ),
              ),
            )
          else
            Padding(
              padding: const EdgeInsets.only(bottom: 50),
              child: ElevatedButton(
                onPressed: () {
                  _pageController.nextPage(
                    duration: const Duration(milliseconds: 300),
                    curve: Curves.easeInOut,
                  );
                },
                child: const Text('Próximo'),
              ),
            ),
        ],
      ),
    );
  }
}
