import 'package:peach_fit_app/models/user_model.dart';

class GetCompleteUserService {
  UserModel? user; // Nullable para evitar problemas

  Future<UserModel?> getUser() async {
    // TODO: Implementar busca de dados do usuário
    // Por enquanto, retorna null ou busca do storage
    return await UserModel.getUserFromStorage();
  }
  
  // Método para criar usuário com dados mínimos (para teste)
  UserModel createTestUser() {
    return UserModel(
      id: 1,
      name: 'Usuário Teste',
      email: 'teste@email.com',
      type: 1, // Cliente
      createdAt: DateTime.now(),
      updatedAt: DateTime.now(),
    );
  }
}
