import 'dart:async';
import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:location/location.dart';

class GoogleMapsComponent extends StatefulWidget {
  const GoogleMapsComponent({super.key});

  @override
  State<GoogleMapsComponent> createState() => GoogleMapsComponentState();
}

class GoogleMapsComponentState extends State<GoogleMapsComponent> {
  final Completer<GoogleMapController> _controller = Completer();
  final Location location = Location();
  final Set<Marker> _markers = {};

  StreamSubscription<LocationData>? _locationSubscription;
  LatLng? _currentPosition;

  @override
  void initState() {
    super.initState();
    _initLocationTracking();
  }

  Future<void> _initLocationTracking() async {
    bool serviceEnabled = await location.serviceEnabled();
    if (!serviceEnabled) {
      serviceEnabled = await location.requestService();
      if (!serviceEnabled) return;
    }

    PermissionStatus permissionGranted = await location.hasPermission();
    if (permissionGranted == PermissionStatus.denied) {
      permissionGranted = await location.requestPermission();
      if (permissionGranted != PermissionStatus.granted) return;
    }

    location.changeSettings(
      interval: 1000,
      distanceFilter: 5,
    ); // atualiza a cada 1s ou 5m

    _locationSubscription = location.onLocationChanged.listen((
      LocationData userLocation,
    ) {
      final LatLng newPosition = LatLng(
        userLocation.latitude!,
        userLocation.longitude!,
      );

      setState(() {
        _currentPosition = newPosition;
        _markers
          ..clear()
          ..add(
            Marker(
              markerId: const MarkerId('current_location'),
              position: newPosition,
              infoWindow: const InfoWindow(title: 'Você está aqui'),
              icon: BitmapDescriptor.defaultMarkerWithHue(
                BitmapDescriptor.hueOrange,
              ),
            ),
          );
      });

      _moveCamera(newPosition);
    });
  }

  Future<void> _moveCamera(LatLng position) async {
    final controller = await _controller.future;
    controller.animateCamera(CameraUpdate.newLatLng(position));
  }

  @override
  void dispose() {
    _locationSubscription?.cancel();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body:
          _currentPosition == null
              ? const Center(child: CircularProgressIndicator())
              : GoogleMap(
                mapType: MapType.normal,
                myLocationEnabled: true,
                zoomControlsEnabled: false,
                myLocationButtonEnabled: true,
                initialCameraPosition: CameraPosition(
                  target: _currentPosition!,
                  zoom: 16,
                ),
                markers: _markers,
                onMapCreated: (controller) => _controller.complete(controller),
              ),
    );
  }
}
