import 'package:peach_fit_app/models/user_model.dart';

class GetUserTypeService {
  Future<int> getUserType() async {
    // Usar método estático do UserModel
    return await UserModel.getUserType();
  }
  
  // Método alternativo para obter tipo do usuário logado
  Future<int> getCurrentUserType() async {
    try {
      final userData = await UserModel.getUserFromStorage();
      return userData?.type ?? 1; // Default: Cliente
    } catch (e) {
      return 1; // Default: Cliente em caso de erro
    }
  }
}
