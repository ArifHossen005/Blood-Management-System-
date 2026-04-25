# 🩸 Blood Management System

A comprehensive blood donation management platform built with Laravel, designed to streamline donor registration, blood request tracking, and inventory management with modern features like multi-language support and role-based access control.

![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=flat&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green.svg)

---

## ✨ Features

### 🔐 Authentication & Authorization
- Role-based access control (Admin, Sub-Admin, Donor)
- Granular permission system for sub-admins
- Secure password reset and profile management

### 👥 Donor Management
- Donor registration with blood type, location, and availability
- Profile management (update info, change password)
- Donation history tracking
- Approve/reject donor applications

### 🩸 Blood Request System
- Create and manage blood requests
- Search donors by blood type and location
- Request status tracking (pending, approved, completed)
- Notification system for matching donors

### 📊 Admin Dashboard
- Real-time statistics and analytics
- Donor approval workflow
- Sub-admin management with custom permissions
- Activity logs and reporting

### 🌐 Multi-Language Support
- **বাংলা (Bangla)** and **English** interface
- User-specific language preferences
- Complete translation coverage (controllers, models, middleware, views)

### 🎨 Theme Switching
- Light and Dark mode
- User-specific theme preferences
- Persistent across sessions

### 🔧 Technical Features
- RESTful API architecture
- MySQL for relational data
- MongoDB for NoSQL operations
- Responsive design
- Middleware-based locale and theme management

---

## 🛠️ Tech Stack

**Backend:**
- PHP 8.1+
- Laravel 10.x Framework
- MySQL 8.0
- MongoDB

**Frontend:**
- React (handled by frontend team)
- React Native (mobile app)

**API:**
- RESTful API design
- JSON response format

---

## 📋 Prerequisites

Before installation, ensure you have:

- PHP >= 8.1
- Composer
- MySQL >= 8.0
- MongoDB (optional, for NoSQL features)
- Node.js & npm (for asset compilation)

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/ArifHossen005/Blood-Management-System.git
cd Blood-Management-System
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and configure your database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blood_management
DB_USERNAME=root
DB_PASSWORD=your_password

# MongoDB (if using)
MONGO_DB_HOST=127.0.0.1
MONGO_DB_PORT=27017
MONGO_DB_DATABASE=blood_management
```

### 4. Database Setup

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE blood_management;"

# Run migrations
php artisan migrate

# Seed sample data (optional)
php artisan db:seed
```

### 5. Storage Link

```bash
php artisan storage:link
```

### 6. Compile Assets

```bash
npm run dev
# or for production
npm run build
```

### 7. Start Development Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## 🔑 Default Credentials

**Admin:**
- Email: `admin@bloodbank.com`
- Password: `admin123`

**Donor:**
- Email: `donor@example.com`
- Password: `donor123`

---

## 📂 Project Structure
blood-management-system/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Admin panel controllers
│   │   │   ├── Donor/         # Donor controllers
│   │   │   ├── LocaleController.php
│   │   │   └── ThemeController.php
│   │   ├── Middleware/
│   │   │   ├── SetLocale.php
│   │   │   ├── RoleMiddleware.php
│   │   │   └── CheckPermission.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Donor.php
│   │   ├── BloodRequest.php
│   │   └── Donation.php
├── config/
│   ├── locale.php             # Language & theme config
│   └── app.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── lang/
│   │   ├── bn/                # Bangla translations
│   │   │   └── ui.php
│   │   └── en/                # English translations
│   │       └── ui.php
│   └── views/
├── routes/
│   ├── web.php
│   └── api.php
└── public/

---

## 🌍 Language & Theme Switching

### API Endpoints

**Switch Language:**
```http
POST /locale/switch
Content-Type: application/json

{
  "locale": "bn"  // or "en"
}
```

**Switch Theme:**
```http
POST /theme/switch
Content-Type: application/json

{
  "theme": "dark"  // or "light"
}
```

### Blade Usage

```blade
<!-- Get current locale -->
{{ app()->getLocale() }}

<!-- Translation -->
{{ __('ui.messages.welcome') }}

<!-- Theme attribute -->
<html data-theme="{{ session('theme', 'light') }}">
```

---

## 🔐 Permission System

Available permissions for sub-admins:

- `approve_donors` — Approve/reject donor applications
- `edit_donors` — Edit donor information
- `delete_donors` — Delete donor records
- `manage_blood_requests` — Manage blood requests
- `view_reports` — Access analytics and reports

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter DonorControllerTest

# Quick locale test in Tinker
php artisan tinker
>>> app()->setLocale('bn');
>>> __('ui.messages.profile_updated');
```

---

## 📸 Screenshots

_(Add screenshots here)_

- Dashboard
- Donor Management
- Blood Request Form
- Language Switching
- Theme Switching

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 🐛 Known Issues

- Validation messages default to English (Laravel's `validation.php` not translated yet)
- Blade view caching may require `php artisan view:clear` after language changes

---

## 📝 TODO

- [ ] Add email notification system
- [ ] Implement SMS alerts for urgent requests
- [ ] Generate PDF reports
- [ ] Add blood inventory management
- [ ] Mobile app integration (React Native)

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

**Arif Hossen**

- GitHub: [@ArifHossen005](https://github.com/ArifHossen005)
- Email: arif.hossen@example.com
- LinkedIn: [Arif Hossen](https://linkedin.com/in/arifhossen)

---

## 🙏 Acknowledgments

- Laravel Framework
- Bangladesh Blood Donors Community
- Open Source Contributors

---

## 📞 Support

For support, email arif.hossen@example.com or open an issue in the repository.

---

**Made with ❤️ for humanity**
