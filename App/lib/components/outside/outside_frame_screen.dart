import 'package:flutter/material.dart';

class OutsideFrameScreen extends StatelessWidget {
  final Widget? content;
  final Widget? footer;
  const OutsideFrameScreen({super.key, this.content, this.footer});

  @override
  Widget build(BuildContext context) {
    MediaQueryData media = MediaQuery.of(context);
    double screenHeight = media.size.height;
    double screenWidth = media.size.width;

    return Scaffold(
      body: Stack(
        children: [
          // Background
          Container(
            decoration: BoxDecoration(
              image: const DecorationImage(
                image: AssetImage('assets/images/bg_people.png'),
                alignment: Alignment.bottomCenter,
              ),
              gradient: LinearGradient(
                colors: [
                  Theme.of(context).colorScheme.primary,
                  Theme.of(context).colorScheme.secondary,
                ],
              ),
            ),
            width: double.infinity,
            height: screenHeight * 0.5,
          ),
          // Scrollable Content
          SingleChildScrollView(
            child: Padding(
              padding: EdgeInsets.only(top: screenHeight * 0.2),
              child: Column(
                children: [
                  // Logo
                  Image(
                    image: const AssetImage('assets/images/logo_light.png'),
                    height: screenHeight * 0.12,
                  ),
                  SizedBox(height: screenHeight * 0.05),
                  // Card for content
                  Center(
                    child: Card(
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(
                          10,
                        ), // Ajuste o valor conforme necessário
                      ),
                      elevation: 4,
                      child: Padding(
                        padding: const EdgeInsets.all(8.0),
                        child: ConstrainedBox(
                          constraints: BoxConstraints(
                            maxWidth: screenWidth * 0.9,
                            minHeight: 300,
                          ),
                          child: Padding(
                            padding: const EdgeInsets.all(10.0),
                            child: content,
                          ),
                        ),
                      ),
                    ),
                  ),
                  SizedBox(child: footer),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}
