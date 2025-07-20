# 🍑 Peach Fit - Personal Trainer Platform

Sistema completo para conectar personal trainers e clientes com agendamento, pagamentos e geolocalização.

## 📁 Estrutura do Projeto

```
peach-fit-app/
├── Backend/     # Laravel 12.12 API + Admin Panel
└── App/         # Flutter Mobile Application
```

## 🌐 Deploy

- **Production:** https://srv846765.hstgr.cloud
- **Admin Panel:** https://srv846765.hstgr.cloud/login
- **API Base:** https://srv846765.hstgr.cloud/api

### Credentials
- **Admin:** betofreitas16@gmail.com / asx123

## 🔧 Setup Local

### Backend
```bash
cd Backend/
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### Flutter
```bash
cd App/
flutter pub get
flutter run
```

## 📱 Features Implementadas

✅ Sistema de autenticação 2FA  
✅ CRUD Personal Trainers/Clientes  
✅ Agendamento de treinos  
✅ Pagamentos PayPal  
✅ Notificações em tempo real  
✅ Geolocalização  
✅ Sistema de avaliações  
✅ Painel administrativo completo  

## �� Deploy Instructions

```bash
git clone https://github.com/alexandre-amaral/peach-fit-app.git
cd peach-fit-app/Backend
composer install --no-dev
cp .env.example .env
php artisan migrate --force
```

## 📞 Support

Email: dev@peachfit.com
