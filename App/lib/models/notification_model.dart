class NotificationModel {
  final int id;
  final bool readed;
  final String title;
  final String text;
  final DateTime date;

  // Construtor
  NotificationModel({
    required this.id,
    required this.readed,
    required this.title,
    required this.text,
    required this.date,
  });

  // Método para criar a instância a partir de um Map (como o JSON)
  factory NotificationModel.fromJson(Map<String, dynamic> json) {
    return NotificationModel(
      id: json['id'] as int,
      readed: json['readed'] as bool,
      title: json['title'] as String,
      text: json['text'] as String,
      date: DateTime.parse(json['date'] as String),
    );
  }

  // Método para converter a instância para um Map (como JSON)
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'readed': readed,
      'title': title,
      'text': text,
      'date': date.toIso8601String(),
    };
  }
}
