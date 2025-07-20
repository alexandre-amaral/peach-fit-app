import 'package:flutter/material.dart';
import 'package:peach_fit_app/services/user/get_user_service.dart';
import 'package:toastification/toastification.dart';

class GetUserLoginController {
  bool loginUser(BuildContext context, String email, String password) {
    try {
      if (email == '' || password == '') {
        throw Exception("Preencha todos os campos");
      }

      GetUserService().putEmailPass(email, password);

      return true;
    } catch (e) {
      toastification.show(
        icon: const Icon(Icons.error),
        context: context,
        title: const Text('Erro!'),
        description: Text(
          e is Exception
              ? e.toString().replaceFirst('Exception: ', '')
              : e.toString(),
        ),
        type: ToastificationType.error,
        style: ToastificationStyle.fillColored,
        alignment: Alignment.topRight,
        autoCloseDuration: const Duration(seconds: 5),
      );

      return false;
    }
  }
}
