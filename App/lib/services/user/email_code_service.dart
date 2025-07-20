import 'package:peach_fit_app/models/notification_model.dart';
import 'package:peach_fit_app/models/user_model.dart';
import 'package:peach_fit_app/services/api/get_user_api_service.dart';

class EmailCodeService {
  Future<void> putEmailCode(String code) async {
    UserModel user = UserModel();

    user.code;
    Map<String, dynamic> response = await GetUserApiService().getUser(
      user.email ?? '',
      user.password ?? '',
      code,
    );

    if (response['status'] == false) {
      throw Exception(
        "Falha ao acessar o aplicativo. \nTente novamente mais tarde.",
      );
    }

    List<NotificationModel> notifications =
        (response['data']['notifications'] as List)
            .map((json) => NotificationModel.fromJson(json))
            .toList();

    user.id = response['data']['id'];
    user.type = response['data']['type'];
    user.name = response['data']['name'];
    user.email = response['data']['email'];
    user.phone = response['data']['phone'];
    user.avatar = response['data']['avatar'];
    user.token = response['data']['token'];
    user.cpf = response['data']['cpf'];
    user.gender = response['data']['gender'];
    user.birthDate = DateTime.parse(response['data']['birth_date']);
    user.height = response['data']['height'];
    user.weight = response['data']['weight'];
    user.localization = response['data']['localization'];
    user.notifications = notifications;

    await user.persistUserData();
  }
}
