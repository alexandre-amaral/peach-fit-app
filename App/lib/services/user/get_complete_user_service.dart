import 'package:peach_fit_app/models/user_model.dart';

class GetCompleteUserService {
  UserModel user = UserModel();

  UserModel getUser() {
    user.getUserData();
    return user;
  }
}
