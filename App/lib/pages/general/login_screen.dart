import 'package:flutter/material.dart';
import 'package:peach_fit_app/components/button_component.dart';
import 'package:peach_fit_app/components/outside/outside_frame_screen.dart';
import 'package:peach_fit_app/components/outside/textfield_component.dart';
import 'package:peach_fit_app/controllers/user/get_user_login_controller.dart';
import 'package:peach_fit_app/util/app_routes.dart';

class LoginScreen extends StatefulWidget {
  final TextEditingController emailField = TextEditingController();
  final TextEditingController passwordField = TextEditingController();

  LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  @override
  Widget build(BuildContext context) {
    return OutsideFrameScreen(
      content: Column(
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              SizedBox(
                child: TextButton(
                  onPressed:
                      () => {Navigator.pushNamed(context, AppRoutes.login)},
                  child: Column(
                    children: [
                      const Text(
                        'Acessar',
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
              TextButton(
                onPressed:
                    () => {Navigator.pushNamed(context, AppRoutes.register)},
                child: const Text(
                  'Cadastrar',
                  style: TextStyle(
                    color: Color(0xFFC8C7CC),
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ],
          ),
          const Divider(color: Color(0xFFC8C7CC)),
          Padding(
            padding: const EdgeInsets.all(10.0),
            child: Column(
              children: [
                const SizedBox(height: 30),
                TextfieldComponent(
                  label: 'Email',
                  icon: Icons.email_outlined,
                  controller: widget.emailField,
                ),
                const SizedBox(height: 10),
                TextfieldComponent(
                  label: 'Senha',
                  icon: Icons.lock_outlined,
                  controller: widget.passwordField,
                  password: true,
                ),
                const SizedBox(height: 20),
                ButtonComponent(
                  label: "Acessar",
                  onPressed:
                      () => {
                        if (GetUserLoginController().loginUser(
                          context,
                          widget.emailField.text,
                          widget.passwordField.text,
                        ))
                          {Navigator.pushNamed(context, AppRoutes.twofa)},
                      },
                ),
              ],
            ),
          ),
        ],
      ),
      footer: Container(
        margin: const EdgeInsets.only(top: 20),
        child: Padding(
          padding: const EdgeInsets.all(10.0),
          child: Column(
            children: [
              InkWell(
                onTap: () => {},
                borderRadius: BorderRadius.circular(10),
                child: Ink(
                  height: 60,
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
                          'Entrar com Facebook',
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
              RichText(
                textAlign: TextAlign.center,
                text: const TextSpan(
                  style: TextStyle(fontSize: 14, color: Colors.black),
                  children: [
                    TextSpan(
                      text: 'Ao clicar em iniciar, você concorda com nossos ',
                    ),
                    TextSpan(
                      text: 'Termos',
                      style: TextStyle(fontWeight: FontWeight.bold),
                    ),
                    TextSpan(text: '.'),
                  ],
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
