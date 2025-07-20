import 'package:flutter/material.dart';
import 'package:peach_fit_app/controllers/controller.dart';
import 'package:peach_fit_app/services/user/email_code_service.dart';

class EmailCodeController extends Controller {
  Future<bool> emailCode(BuildContext context, String code) async {
    try {
      if (code.isEmpty) {
        throw Exception("Você precisa informar o código.");
      }

      await EmailCodeService().putEmailCode(code);

      return true;
    } catch (e) {
      handleErrorMessage(context, e);
      return false;
    }
  }
}
