import 'package:flutter/material.dart';
import 'package:peach_fit_app/util/app_routes.dart';

class ClientOnboardEndScreen extends StatelessWidget {
  const ClientOnboardEndScreen({super.key});

  @override
  Widget build(BuildContext context) {
    MediaQueryData media = MediaQuery.of(context);
    return Scaffold(
      body: Column(
        children: [
          SizedBox(height: media.size.height * 0.08),
          Image.asset('assets/images/onboard/04.png'),
          const SizedBox(height: 30),
          const Text(
            'Olá, seja bem vindo(a)!',
            style: TextStyle(fontSize: 28, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 10),
          const Text(
            'Escolha sua localização para \n começar a encontrar treinadores \n perto de você',
            style: TextStyle(color: Colors.grey, fontSize: 18),
            textAlign: TextAlign.center,
          ),
          SizedBox(height: media.size.height * 0.07),
          SizedBox(
            width: 250,
            child: OutlinedButton(
              style: OutlinedButton.styleFrom(
                foregroundColor:
                    Theme.of(
                      context,
                    ).colorScheme.primary, // cor do texto e do ícone
                side: BorderSide(
                  color: Theme.of(context).colorScheme.primary,
                ), // cor da borda
              ),
              onPressed:
                  () => {Navigator.pushNamed(context, AppRoutes.homeClient)},
              child: const Padding(
                padding: EdgeInsets.all(10.0),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.location_on),
                    SizedBox(width: 10),
                    Text('Usar localização atual'),
                  ],
                ),
              ),
            ),
          ),
          SizedBox(height: media.size.height * 0.07),
          TextButton(
            style: TextButton.styleFrom(
              textStyle: const TextStyle(
                decoration: TextDecoration.underline,
                fontSize: 18,
              ),
              foregroundColor: Colors.black,
            ),
            onPressed:
                () => {Navigator.pushNamed(context, AppRoutes.homeClient)},
            child: const Text('Selecionar Manualmente'),
          ),
        ],
      ),
    );
  }
}
