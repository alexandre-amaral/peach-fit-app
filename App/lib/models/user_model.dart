import 'package:peach_fit_app/models/notification_model.dart';
import 'package:shared_preferences/shared_preferences.dart';

class UserModel {
  int? id;
  int? type;
  String? name;
  String? email;
  String? password;
  int? code;
  String? phone;
  String? avatar;
  String? token;
  String? cpf;
  String? gender;
  DateTime? birthDate;
  String? localization;

  double? height;
  double? weight;

  List<NotificationModel>? notifications;

  Future<bool> keepDataOnLogin() async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();

      await prefs.setString('name', name!);
      await prefs.setString('email', email!);

      return true;
    } catch (e) {
      return false;
    }
  }

  Future<bool> persistUserData() async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();

      await prefs.setInt('id', id!);
      await prefs.setInt('type', type!);
      await prefs.setString('name', name!);
      await prefs.setString('email', email!);
      await prefs.setString('phone', phone!);
      await prefs.setString('avatar', avatar ?? '');
      await prefs.setString('token', token!);
      await prefs.setString('cpf', cpf!);
      await prefs.setString('gender', gender!);
      await prefs.setString('birth_date', birthDate!.toString());
      await prefs.setString('localization', localization!);
      await prefs.setDouble('height', height!);
      await prefs.setDouble('weight', weight!);
      await prefs.setString('notifications', notifications.toString());

      return true;
    } catch (e) {
      return false;
    }
  }

  Future<int> getUserType() async {
    try {
      SharedPreferences prefs = await SharedPreferences.getInstance();
      type = prefs.getInt('type') ?? 1;
      return type!;
    } catch (e) {
      return 0;
    }
  }

  bool getUserData() {
    try {
      SharedPreferences prefs =
          SharedPreferences.getInstance() as SharedPreferences;
      id = prefs.getInt('id');
      name = prefs.getString('name');
      email = prefs.getString('email');
      phone = prefs.getString('phone');
      avatar = prefs.getString('avatar');
      token = prefs.getString('token');
      cpf = prefs.getString('cpf');
      gender = prefs.getString('gender');
      birthDate = DateTime.parse(prefs.getString('birth_date') ?? '');
      localization = prefs.getString('localization');
      height = prefs.getDouble('height');
      weight = prefs.getDouble('weight');
      return true;
    } catch (e) {
      return false;
    }
  }
}
