import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class HttpService {
  static const _storage = FlutterSecureStorage();
  static const String _tokenKey = 'auth_token';

  // Singleton pattern
  static final HttpService _instance = HttpService._internal();
  factory HttpService() => _instance;
  HttpService._internal();

  // Headers base
  Map<String, String> get _baseHeaders => {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  };

  // Headers com token
  Future<Map<String, String>> get _headersWithAuth async {
    final token = await getToken();
    final headers = Map<String, String>.from(_baseHeaders);
    if (token != null) {
      headers['Authorization'] = 'Bearer $token';
    }
    return headers;
  }

  // GET request
  Future<Map<String, dynamic>> get(String url, {bool requiresAuth = true}) async {
    try {
      final headers = requiresAuth ? await _headersWithAuth : _baseHeaders;
      final response = await http.get(Uri.parse(url), headers: headers);
      return _handleResponse(response);
    } catch (e) {
      throw _handleError(e);
    }
  }

  // POST request
  Future<Map<String, dynamic>> post(String url, {Map<String, dynamic>? body, bool requiresAuth = true}) async {
    try {
      final headers = requiresAuth ? await _headersWithAuth : _baseHeaders;
      final response = await http.post(
        Uri.parse(url),
        headers: headers,
        body: body != null ? jsonEncode(body) : null,
      );
      return _handleResponse(response);
    } catch (e) {
      throw _handleError(e);
    }
  }

  // PUT request
  Future<Map<String, dynamic>> put(String url, {Map<String, dynamic>? body, bool requiresAuth = true}) async {
    try {
      final headers = requiresAuth ? await _headersWithAuth : _baseHeaders;
      final response = await http.put(
        Uri.parse(url),
        headers: headers,
        body: body != null ? jsonEncode(body) : null,
      );
      return _handleResponse(response);
    } catch (e) {
      throw _handleError(e);
    }
  }

  // DELETE request
  Future<Map<String, dynamic>> delete(String url, {bool requiresAuth = true}) async {
    try {
      final headers = requiresAuth ? await _headersWithAuth : _baseHeaders;
      final response = await http.delete(Uri.parse(url), headers: headers);
      return _handleResponse(response);
    } catch (e) {
      throw _handleError(e);
    }
  }

  // Handle response
  Map<String, dynamic> _handleResponse(http.Response response) {
    final body = response.body.isNotEmpty ? jsonDecode(response.body) : {};
    
    if (response.statusCode >= 200 && response.statusCode < 300) {
      return body;
    } else {
      throw HttpException(
        message: body['message'] ?? 'Erro desconhecido',
        statusCode: response.statusCode,
        data: body,
      );
    }
  }

  // Handle errors
  Exception _handleError(dynamic error) {
    if (error is HttpException) {
      return error;
    }
    return HttpException(message: 'Erro de conexão: $error', statusCode: 0);
  }

  // Token management
  Future<void> saveToken(String token) async {
    await _storage.write(key: _tokenKey, value: token);
  }

  Future<String?> getToken() async {
    return await _storage.read(key: _tokenKey);
  }

  Future<void> clearToken() async {
    await _storage.delete(key: _tokenKey);
  }

  Future<bool> hasToken() async {
    final token = await getToken();
    return token != null && token.isNotEmpty;
  }
  
  // ✅ Método que estava faltando
  Future<bool> hasValidToken() async {
    return await hasToken();
  }
}

// Custom Exception
class HttpException implements Exception {
  final String message;
  final int statusCode;
  final Map<String, dynamic>? data;

  HttpException({
    required this.message,
    required this.statusCode,
    this.data,
  });

  @override
  String toString() => 'HttpException: $message (Status: $statusCode)';
} 