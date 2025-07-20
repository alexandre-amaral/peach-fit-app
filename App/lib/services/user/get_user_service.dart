import 'package:peach_fit_app/models/user_model.dart';

class GetUserService {
  putEmailPass(String email, String password) async {
    UserModel user = UserModel();

    user.email = email;
    user.password = password;

    if (!await user.keepDataOnLogin()) {
      throw Exception("Falha ao fazer login.");
    }
  }
}
