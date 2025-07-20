import 'package:flutter/material.dart';
import 'package:peach_fit_app/components/button_component.dart';
import 'package:peach_fit_app/components/outside/outside_frame_screen.dart';
import 'package:peach_fit_app/components/outside/pinput_component.dart';
import 'package:peach_fit_app/controllers/user/email_code_controller.dart';
import 'package:peach_fit_app/controllers/user/get_user_type_controller.dart';
import 'package:peach_fit_app/util/app_routes.dart';

// ignore: must_be_immutable
class TwofaScreen extends StatefulWidget {
  TextEditingController pinController = TextEditingController();

  TwofaScreen({super.key});

  @override
  State<TwofaScreen> createState() => _TwofaScreenState();
}

class _TwofaScreenState extends State<TwofaScreen> {
  Future<void> handleGetUserInfo() async {}

  @override
  Widget build(BuildContext context) {
    return OutsideFrameScreen(
      content: SizedBox(
        width: double.infinity,
        child: Column(
          children: [
            const SizedBox(
              width: double.infinity,
              child: Text(
                "Verificação de Email",
                style: TextStyle(fontSize: 25, fontWeight: FontWeight.bold),
              ),
            ),
            Container(
              margin: const EdgeInsets.only(bottom: 20),
              width: double.infinity,
              child: const Text(
                'Digite o código que foi enviado para o seu email',
              ),
            ),
            Container(
              margin: const EdgeInsets.symmetric(vertical: 50),
              child: Column(
                children: [
                  PinputComponent(pinController: widget.pinController),
                  TextButton(
                    onPressed: () => {},
                    child: const Text('Reenviar Email'),
                  ),
                ],
              ),
            ),
            ButtonComponent(
              label: 'Verificar',
              onPressed: () async {
                bool success = await EmailCodeController().emailCode(
                  context,
                  widget.pinController.text,
                );
                if (success) {
                  int userType = await GetUserTypeController().getUserType(
                    context,
                  );

                  Navigator.popAndPushNamed(
                    context,
                    userType == 2
                        ? AppRoutes.onboardClient
                        : AppRoutes.homeTrainer,
                  );
                }
              },
            ),
          ],
        ),
      ),
    );
  }
}
