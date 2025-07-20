import 'package:flutter/material.dart';
import 'package:peach_fit_app/controllers/controller.dart';
import 'package:peach_fit_app/services/user/get_user_type_service.dart';

class GetUserTypeController extends Controller {
  Future<int> getUserType(BuildContext context) async {
    try {
      return await GetUserTypeService().getUserType();
    } catch (e) {
      handleErrorMessage(context, e);
      return 0;
    }
  }
}
