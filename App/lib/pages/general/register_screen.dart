import 'package:flutter/material.dart';
import 'package:peach_fit_app/components/button_component.dart';
import 'package:peach_fit_app/components/outside/outside_frame_screen.dart';
import 'package:peach_fit_app/components/outside/textfield_component.dart';
import 'package:peach_fit_app/util/app_routes.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  @override
  Widget build(BuildContext context) {
    return OutsideFrameScreen(
      content: Column(
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              TextButton(
                onPressed:
                    () => {Navigator.pushNamed(context, AppRoutes.login)},
                child: const Text(
                  'Acessar',
                  style: TextStyle(
                    color: Color(0xFFC8C7CC),
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
              SizedBox(
                child: TextButton(
                  onPressed:
                      () => {Navigator.pushNamed(context, AppRoutes.register)},
                  child: Column(
                    children: [
                      const Text(
                        'Cadastrar',
                        style: TextStyle(
                          color: Colors.black,
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      Container(
                        margin: const EdgeInsets.only(top: 10),
                        width: 45,
                        height: 5,
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(100),
                          gradient: LinearGradient(
                            colors: [
                              Theme.of(context).colorScheme.primary,
                              Theme.of(context).colorScheme.secondary,
                            ],
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ],
          ),
          const Divider(color: Color(0xFFC8C7CC)),
          const SizedBox(height: 20),
          InkWell(
            onTap: () => {},
            borderRadius: BorderRadius.circular(10),
            child: Ink(
              height: 50,
              decoration: BoxDecoration(
                color: const Color(0xFF2672CB),
                borderRadius: BorderRadius.circular(8),
              ),
              padding: const EdgeInsets.symmetric(vertical: 8),
              width: double.infinity,
              child: const Center(
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.facebook_outlined, color: Colors.white),
                    SizedBox(width: 10),
                    Text(
                      'Cadastrar com Facebook',
                      style: TextStyle(
                        color: Colors.white,
                        fontWeight: FontWeight.bold,
                        fontSize: 17,
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
          const SizedBox(height: 20),
          const SizedBox(
            width: double.infinity,
            child: Text(
              'Informações Pessoais',
              textAlign: TextAlign.start,
              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
            ),
          ),
          const Divider(color: Color(0xFFC8C7CC)),
          const TextfieldComponent(
            label: 'Nome Completo',
            icon: Icons.person_2_outlined,
          ),
          const TextfieldComponent(
            label: 'CPF',
            icon: Icons.card_membership_outlined,
          ),
          const TextfieldComponent(
            label: 'Telefone',
            icon: Icons.phone_outlined,
          ),
          const SizedBox(height: 20),
          const SizedBox(
            width: double.infinity,
            child: Text(
              'Informações de Acesso',
              textAlign: TextAlign.start,
              style: TextStyle(fontWeight: FontWeight.bold, fontSize: 14),
            ),
          ),
          const Divider(color: Color(0xFFC8C7CC)),
          const TextfieldComponent(label: 'E-mail', icon: Icons.email_outlined),
          const TextfieldComponent(label: 'Senha', icon: Icons.lock_outlined),
          const TextfieldComponent(
            label: 'Confirmar Senha',
            icon: Icons.lock_outlined,
          ),
          RichText(
            textAlign: TextAlign.center,
            text: const TextSpan(
              style: TextStyle(fontSize: 14, color: Colors.black),
              children: [
                TextSpan(
                  text: 'Ao clicar em cadastrar, você concorda com nossos ',
                ),
                TextSpan(
                  text: 'Termos',
                  style: TextStyle(fontWeight: FontWeight.bold),
                ),
                TextSpan(text: '.'),
              ],
            ),
          ),
          const SizedBox(height: 20),
          const ButtonComponent(label: 'Cadastrar'),
        ],
      ),
    );
  }
}
