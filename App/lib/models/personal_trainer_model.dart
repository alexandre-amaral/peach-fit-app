class PersonalTrainerModel {
  final int id;
  final int userId;
  final String? cref;
  final String? specialties;
  final String? description;
  final double? hourlyRate;
  final int? maxDistance;
  final bool isActive;
  final double? rating;
  final int totalReviews;
  final DateTime createdAt;
  final DateTime updatedAt;

  PersonalTrainerModel({
    required this.id,
    required this.userId,
    this.cref,
    this.specialties,
    this.description,
    this.hourlyRate,
    this.maxDistance,
    required this.isActive,
    this.rating,
    required this.totalReviews,
    required this.createdAt,
    required this.updatedAt,
  });

  factory PersonalTrainerModel.fromJson(Map<String, dynamic> json) {
    return PersonalTrainerModel(
      id: json['id'] ?? 0,
      userId: json['user_id'] ?? 0,
      cref: json['cref'],
      specialties: json['specialties'],
      description: json['description'],
      hourlyRate: json['hourly_rate']?.toDouble(),
      maxDistance: json['max_distance'],
      isActive: json['is_active'] ?? true,
      rating: json['rating']?.toDouble(),
      totalReviews: json['total_reviews'] ?? 0,
      createdAt: DateTime.parse(json['created_at'] ?? DateTime.now().toIso8601String()),
      updatedAt: DateTime.parse(json['updated_at'] ?? DateTime.now().toIso8601String()),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'cref': cref,
      'specialties': specialties,
      'description': description,
      'hourly_rate': hourlyRate,
      'max_distance': maxDistance,
      'is_active': isActive,
      'rating': rating,
      'total_reviews': totalReviews,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
    };
  }

  PersonalTrainerModel copyWith({
    int? id,
    int? userId,
    String? cref,
    String? specialties,
    String? description,
    double? hourlyRate,
    int? maxDistance,
    bool? isActive,
    double? rating,
    int? totalReviews,
    DateTime? createdAt,
    DateTime? updatedAt,
  }) {
    return PersonalTrainerModel(
      id: id ?? this.id,
      userId: userId ?? this.userId,
      cref: cref ?? this.cref,
      specialties: specialties ?? this.specialties,
      description: description ?? this.description,
      hourlyRate: hourlyRate ?? this.hourlyRate,
      maxDistance: maxDistance ?? this.maxDistance,
      isActive: isActive ?? this.isActive,
      rating: rating ?? this.rating,
      totalReviews: totalReviews ?? this.totalReviews,
      createdAt: createdAt ?? this.createdAt,
      updatedAt: updatedAt ?? this.updatedAt,
    );
  }

  @override
  String toString() {
    return 'PersonalTrainerModel(id: $id, userId: $userId, specialties: $specialties, rating: $rating)';
  }
} 