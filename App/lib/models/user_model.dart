import 'package:peach_fit_app/models/notification_model.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class UserModel {
  final int id;
  final String name;
  final String email;
  final String? phone;
  final String? avatar;
  final String? cpf;
  final String? gender;
  final String? birthDate;
  final double? height;
  final double? weight;
  final String? localization;
  final int type; // 1 = Cliente, 2 = Personal Trainer
  final String? token;
  final DateTime? emailVerifiedAt;
  final DateTime createdAt;
  final DateTime updatedAt;
  final List<NotificationModel>? notifications;

  static const _storage = FlutterSecureStorage();

  UserModel({
    required this.id,
    required this.name,
    required this.email,
    this.phone,
    this.avatar,
    this.cpf,
    this.gender,
    this.birthDate,
    this.height,
    this.weight,
    this.localization,
    required this.type,
    this.token,
    this.emailVerifiedAt,
    required this.createdAt,
    required this.updatedAt,
    this.notifications,
  });

  factory UserModel.fromJson(Map<String, dynamic> json) {
    return UserModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      email: json['email'] ?? '',
      phone: json['phone'],
      avatar: json['avatar'],
      cpf: json['cpf'],
      gender: json['gender'],
      birthDate: json['birth_date'],
      height: json['height']?.toDouble(),
      weight: json['weight']?.toDouble(),
      localization: json['localization'],
      type: json['type'] ?? 1,
      token: json['token'],
      emailVerifiedAt: json['email_verified_at'] != null 
          ? DateTime.parse(json['email_verified_at']) 
          : null,
      createdAt: DateTime.parse(json['created_at'] ?? DateTime.now().toIso8601String()),
      updatedAt: DateTime.parse(json['updated_at'] ?? DateTime.now().toIso8601String()),
      notifications: json['notifications'] != null
          ? (json['notifications'] as List)
              .map((n) => NotificationModel.fromJson(n))
              .toList()
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'phone': phone,
      'avatar': avatar,
      'cpf': cpf,
      'gender': gender,
      'birth_date': birthDate,
      'height': height,
      'weight': weight,
      'localization': localization,
      'type': type,
      'token': token,
      'email_verified_at': emailVerifiedAt?.toIso8601String(),
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
      'notifications': notifications?.map((n) => n.toJson()).toList(),
    };
  }

  bool get isPersonalTrainer => type == 2;
  bool get isCustomer => type == 1;

  UserModel copyWith({
    int? id,
    String? name,
    String? email,
    String? phone,
    String? avatar,
    String? cpf,
    String? gender,
    String? birthDate,
    double? height,
    double? weight,
    String? localization,
    int? type,
    String? token,
    DateTime? emailVerifiedAt,
    DateTime? createdAt,
    DateTime? updatedAt,
    List<NotificationModel>? notifications,
  }) {
    return UserModel(
      id: id ?? this.id,
      name: name ?? this.name,
      email: email ?? this.email,
      phone: phone ?? this.phone,
      avatar: avatar ?? this.avatar,
      cpf: cpf ?? this.cpf,
      gender: gender ?? this.gender,
      birthDate: birthDate ?? this.birthDate,
      height: height ?? this.height,
      weight: weight ?? this.weight,
      localization: localization ?? this.localization,
      type: type ?? this.type,
      token: token ?? this.token,
      emailVerifiedAt: emailVerifiedAt ?? this.emailVerifiedAt,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
      notifications: notifications ?? this.notifications,
    );
  }

  // Persistence methods using secure storage
  Future<bool> persistUserData() async {
    try {
      await _storage.write(key: 'user_data', value: toString());
      if (token != null) {
        await _storage.write(key: 'auth_token', value: token!);
      }
      return true;
    } catch (e) {
      return false;
    }
  }

  static Future<UserModel?> getUserFromStorage() async {
    try {
      final userData = await _storage.read(key: 'user_data');
      if (userData != null) {
        // Implementar parsing se necessário
        return null; // Placeholder para implementação
      }
      return null;
    } catch (e) {
      return null;
    }
  }

  static Future<void> clearUserData() async {
    try {
      await _storage.delete(key: 'user_data');
      await _storage.delete(key: 'auth_token');
    } catch (e) {
      // Handle error
    }
  }

  static Future<int> getUserType() async {
    try {
      final userData = await getUserFromStorage();
      return userData?.type ?? 1;
    } catch (e) {
      return 1;
    }
  }

  @override
  String toString() {
    return 'UserModel(id: $id, name: $name, email: $email, type: $type)';
  }
}
