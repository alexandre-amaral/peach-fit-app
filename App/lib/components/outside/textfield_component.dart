import 'package:flutter/material.dart';

class TextfieldComponent extends StatefulWidget {
  final String label;
  final IconData? icon;
  final TextEditingController? controller;
  final bool? password;

  const TextfieldComponent({
    super.key,
    this.label = 'Label',
    this.icon,
    this.controller,
    this.password = false, // Por padrão, password é false
  });

  @override
  State<TextfieldComponent> createState() => _TextfieldComponentState();
}

class _TextfieldComponentState extends State<TextfieldComponent> {
  bool _obscureText = true; // Inicialmente a senha estará oculta

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.symmetric(vertical: 10),
      child: TextField(
        controller: widget.controller,
        obscureText:
            widget.password == true
                ? _obscureText
                : false, // Mostra ou oculta a senha
        decoration: InputDecoration(
          prefixIcon: Icon(widget.icon),
          border: OutlineInputBorder(
            borderRadius: BorderRadius.circular(10),
            borderSide: const BorderSide(
              color: Colors.red,
              width: 2.0,
            ), // Corrigido a largura do borderSide
          ),
          labelText: widget.label,
          suffixIcon:
              widget.password == true
                  ? IconButton(
                    icon: Icon(
                      _obscureText ? Icons.visibility_off : Icons.visibility,
                    ),
                    onPressed: () {
                      setState(() {
                        _obscureText = !_obscureText; // Alterna a visibilidade
                      });
                    },
                  )
                  : null, // Se não for senha, não exibe o ícone
        ),
      ),
    );
  }
}
