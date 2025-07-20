import 'package:peach_fit_app/models/user_model.dart';
import 'package:peach_fit_app/services/user/get_complete_user_service.dart';

class GetUserController {
  UserModel getUser() {
    return GetCompleteUserService().getUser();
  }
}
