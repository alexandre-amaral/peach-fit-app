class GetUserApiService {
  Future<Map<String, dynamic>> getUser(
    String email,
    String password,
    String code,
  ) async {
    // Simulando um atraso de 1 segundo
    await Future.delayed(const Duration(seconds: 1));

    Map<String, dynamic> mockUser = {};

    if (code == '2222') {
      // Dados mockados
      mockUser = {
        'status': true,
        'data': {
          'id': 1,
          'type': 2,
          'name': 'Egberto Carvalho',
          'email': 'betofreitas16@gmail.com',
          'phone': '(84) 9 9118-7124',
          'avatar': '',
          'token': 'HUEHUEUHUE BR',
          'cpf': '098.749.734-04',
          'gender': 'Masculino',
          'birth_date': '2000-01-01',
          'height': 1.68,
          'weight': 120.0,
          'localization': 'Mossoró - RN',
          'notifications': [
            {
              'id': 1,
              'readed': true,
              'title': 'Notificação 1',
              'text': 'Texto da notificação 1',
              'date': '2023-09-01',
            },
            {
              'id': 2,
              'readed': false,
              'title': 'Notificação 2',
              'text': 'Texto da notificação 2',
              'date': '2023-09-02',
            },
            {
              'id': 3,
              'readed': true,
              'title': 'Notificação 3',
              'text': 'Texto da notificação 3',
              'date': '2023-09-03',
            },
          ],
        },
      };
    }

    return mockUser;
  }
}
