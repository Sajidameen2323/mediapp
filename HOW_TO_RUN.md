# MediApp - How to Run Guide

## Project Overview
MediApp is a comprehensive medical appointment management system built with Laravel 12 and modern web technologies. It supports multiple user types including admins, doctors, patients, laboratory staff, and pharmacy staff.

## Requirements

### System Requirements
- **PHP**: ^8.2 (minimum)
- **Node.js**: 18.x or higher
- **Composer**: Latest version
- **MySQL**: 8.0 or higher (recommended)
- **Git**: For version control

### Development Environment
- Windows with PowerShell (as specified in project instructions)
- VS Code (recommended IDE)

## Installation Steps

### 1. Clone the Repository
```powershell
git clone https://github.com/Sajidameen2323/mediapp.git
cd mediapp
```

### 2. Install PHP Dependencies
```powershell
composer install
```

### 3. Install Node Dependencies
```powershell
npm install
```

### 4. Environment Configuration

#### Copy Environment File
```powershell
Copy-Item .env.example .env
```

#### Configure Database Settings
Edit the `.env` file and update the following database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medi_app
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

#### Configure Application Settings
Update these basic settings in your `.env` file:

```env
APP_NAME="MediApp"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Kolkata
```

### 5. Generate Application Key
```powershell
php artisan key:generate
```

### 6. Database Setup

#### Create Database
Create a MySQL database named `medi_app`:
```sql
CREATE DATABASE medi_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Run Migrations
```powershell
php artisan migrate
```

#### Seed Database with Demo Data
```powershell
php artisan db:seed
```

### 7. Storage Setup
```powershell
php artisan storage:link
```

### 8. Cache and Optimization (Optional)
```powershell
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Running the Application

### Development Environment

#### Option 1: Using Laravel Artisan (Recommended for Development)
```powershell
# Terminal 1 - Start Laravel development server
php artisan serve

# Terminal 2 - Start Vite development server for asset compilation
npm run dev

# Terminal 3 - Start queue worker (if using queues)
php artisan queue:work
```

#### Option 2: Using Composer Script (All-in-One)
```powershell
composer run dev
```
This command starts the Laravel server, queue worker, and Vite development server simultaneously.

### Production Environment
```powershell
# Build assets for production
npm run build

# Start Laravel server (use a proper web server like Apache/Nginx in production)
php artisan serve --host=0.0.0.0 --port=8000
```

## Default User Credentials

After running the database seeder, you can log in with the following demo accounts:

### Admin User
- **Email**: `admin@mediapp.com`
- **Password**: `password`
- **Role**: Administrator (full access)

### Doctor Users
- **Email**: `doctor1@mediapp.com` - `doctor4@mediapp.com`
- **Password**: `password`
- **Specializations**:
  - `doctor1@mediapp.com` - General Medicine
  - `doctor2@mediapp.com` - Cardiology
  - `doctor3@mediapp.com` - Pediatrics
  - `doctor4@mediapp.com` - Dermatology

### Patient Users
- **Email**: `patient1@mediapp.com` - `patient4@mediapp.com`
- **Password**: `password`
- **Role**: Patient (can book appointments, view medical records)

### Laboratory Users
- **Email**: `lab1@mediapp.com`, `lab2@mediapp.com`
- **Password**: `password`
- **Role**: Laboratory staff (manage lab tests and reports)

### Pharmacy Users
- **Email**: `pharmacy1@mediapp.com`, `pharmacy2@mediapp.com`
- **Password**: `password`
- **Role**: Pharmacy staff (manage prescriptions and orders)

## Key Features

### For Patients
- Book appointments with doctors
- View appointment history
- Manage health profiles
- Access medical reports
- Order medications from pharmacy

### For Doctors
- Manage appointment schedules
- View patient medical histories
- Create prescriptions
- Generate medical reports

### For Administrators
- Manage all users and roles
- Configure system settings
- Monitor appointments and payments
- Generate system reports

### For Laboratory Staff
- Manage lab test requests
- Upload test results
- Handle lab appointments

### For Pharmacy Staff
- Process prescription orders
- Manage medication inventory
- Handle pharmacy orders

## Important Configuration

### Timezone Settings
The application is configured for `Asia/Kolkata` timezone. Update `APP_TIMEZONE` in `.env` if needed.

### File Storage
- Public files are stored in `storage/app/public`
- Make sure to run `php artisan storage:link` to create the symbolic link

### Mail Configuration (Optional)
Update mail settings in `.env` for email notifications:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS="noreply@mediapp.com"
MAIL_FROM_NAME="MediApp"
```

## Troubleshooting

### Common Issues

#### 1. Database Connection Error
- Verify MySQL is running
- Check database credentials in `.env`
- Ensure database `medi_app` exists

#### 2. Permission Issues
```powershell
# Fix storage permissions
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T
```

#### 3. Asset Compilation Issues
```powershell
# Clear Node modules and reinstall
Remove-Item node_modules -Recurse -Force
Remove-Item package-lock.json -Force
npm install
npm run dev
```

#### 4. Cache Issues
```powershell
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Reset Database (Development Only)
```powershell
php artisan migrate:fresh --seed
```

## Additional Commands

### Testing
```powershell
# Run tests
php artisan test

# Or using Composer
composer run test
```

### Code Quality
```powershell
# Run Laravel Pint (code formatting)
./vendor/bin/pint
```

### Queue Management
```powershell
# Process queue jobs
php artisan queue:work

# Clear failed jobs
php artisan queue:flush
```

## Production Deployment Notes

1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Use a proper web server (Apache/Nginx)
3. Configure SSL certificates
4. Set up proper database backups
5. Configure caching (Redis recommended)
6. Set up monitoring and logging

## Support

For issues and feature requests, please check the project repository:
- **Repository**: https://github.com/Sajidameen2323/mediapp
- **Branch**: master

## Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates with Tailwind CSS
- **Database**: MySQL 8.0
- **Asset Compilation**: Vite
- **Authentication**: Laravel Sanctum
- **Permissions**: Spatie Laravel Permission
- **PDF Generation**: DomPDF
- **Search**: Laravel Scout with Algolia (optional)

---

**Note**: This is a development setup guide. For production deployment, additional security and performance considerations should be implemented.
