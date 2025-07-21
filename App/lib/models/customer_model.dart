class CustomerModel {
  final int id;
  final int userId;
  final String? fitnessGoals;
  final String? fitnessLevel;
  final String? medicalConditions;
  final String? preferredWorkoutTime;
  final bool isActive;
  final DateTime createdAt;
  final DateTime updatedAt;

  CustomerModel({
    required this.id,
    required this.userId,
    this.fitnessGoals,
    this.fitnessLevel,
    this.medicalConditions,
    this.preferredWorkoutTime,
    required this.isActive,
    required this.createdAt,
    required this.updatedAt,
  });

  factory CustomerModel.fromJson(Map<String, dynamic> json) {
    return CustomerModel(
      id: json['id'] ?? 0,
      userId: json['user_id'] ?? 0,
      fitnessGoals: json['fitness_goals'],
      fitnessLevel: json['fitness_level'],
      medicalConditions: json['medical_conditions'],
      preferredWorkoutTime: json['preferred_workout_time'],
      isActive: json['is_active'] ?? true,
      createdAt: DateTime.parse(json['created_at'] ?? DateTime.now().toIso8601String()),
      updatedAt: DateTime.parse(json['updated_at'] ?? DateTime.now().toIso8601String()),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'fitness_goals': fitnessGoals,
      'fitness_level': fitnessLevel,
      'medical_conditions': medicalConditions,
      'preferred_workout_time': preferredWorkoutTime,
      'is_active': isActive,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
    };
  }

  CustomerModel copyWith({
    int? id,
    int? userId,
    String? fitnessGoals,
    String? fitnessLevel,
    String? medicalConditions,
    String? preferredWorkoutTime,
    bool? isActive,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) {
    return CustomerModel(
      id: id ?? this.id,
      userId: userId ?? this.userId,
      fitnessGoals: fitnessGoals ?? this.fitnessGoals,
      fitnessLevel: fitnessLevel ?? this.fitnessLevel,
      medicalConditions: medicalConditions ?? this.medicalConditions,
      preferredWorkoutTime: preferredWorkoutTime ?? this.preferredWorkoutTime,
      isActive: isActive ?? this.isActive,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }

  @override
  String toString() {
    return 'CustomerModel(id: $id, userId: $userId, fitnessLevel: $fitnessLevel)';
  }
} 