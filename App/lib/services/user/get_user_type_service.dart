import 'package:peach_fit_app/models/user_model.dart';

class GetUserTypeService {
  Future<int> getUserType() async {
    UserModel user = UserModel();

    return await user.getUserType();
  }
}
