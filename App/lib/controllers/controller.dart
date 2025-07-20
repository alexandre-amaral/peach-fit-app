import 'package:flutter/material.dart';
import 'package:toastification/toastification.dart';

class Controller {
  void handleErrorMessage(BuildContext context, dynamic e) {
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
  }
}
