# Attendance Management System

A comprehensive attendance management system built with Laravel, featuring biometric device integration, employee management, and attendance tracking.

## Features

- Employee Management
- Biometric Device Integration
- Attendance Tracking
- Leave Management
- Overtime Tracking
- Schedule Management
- Role-based Access Control
- Reports Generation

## Recent Fixes (v2.1.1)

### Critical Bug Fixes

1. **Fixed User Role Authorization Bug**
   - **Issue**: `hasRole()` method was declared as static but called as instance method
   - **Fix**: Changed `hasRole()` to instance method and improved role checking logic
   - **Impact**: Resolves "Attempt to read property 'slug' on null" errors

2. **Database Integrity Improvements**
   - **Issue**: Duplicate email constraint violations in employees table
   - **Fix**: Created `CleanDuplicateEmails` Artisan command to safely remove duplicates
   - **Impact**: Prevents database constraint violations during employee creation/updates

### New Commands

- `php artisan app:clean-duplicate-emails --dry-run` - Preview duplicate cleanup
- `php artisan app:clean-duplicate-emails` - Execute duplicate cleanup

### Files Modified

- `app/Models/User.php` - Fixed role authorization methods
- `app/Console/Commands/CleanDuplicateEmails.php` - New cleanup command
- `README.md` - Updated installation instructions

## Requirements

- PHP >= 8.1
- MySQL >= 5.7
- Composer
- Node.js & NPM
- Redis Server
- ZKTeco Biometric Device (optional)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/attendance-management-system.git
cd attendance-management-system
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install NPM dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ams
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run migrations:
```bash
php artisan migrate
```

8. **Clean up duplicate emails (if any):**
```bash
# First, do a dry run to see what would be deleted
php artisan app:clean-duplicate-emails --dry-run

# Then run the actual cleanup
php artisan app:clean-duplicate-emails
```

9. Seed the database (optional):
```bash
php artisan db:seed
```

10. Start the development server:
```bash
php artisan serve
```

11. In a separate terminal, compile assets:
```bash
npm run dev
```

## Configuration

### Attendance Settings

The system's attendance settings can be configured in the `.env` file:

```
ATTENDANCE_CLEARANCE_TIME=23:50
ATTENDANCE_LATE_THRESHOLD=15
ATTENDANCE_OVERTIME_THRESHOLD=30
```

Additional settings can be found in `config/attendance.php`.

### Biometric Device Setup

1. Connect your ZKTeco device to the network
2. Configure the device IP in the admin panel
3. Add employees to the device
4. Configure attendance collection settings

## Usage

### Admin Panel

1. Access the admin panel at `/admin`
2. Manage employees, schedules, and attendance
3. Generate reports and monitor attendance

### Employee Portal

1. Employees can view their attendance records
2. Request leaves and view schedules
3. Check their attendance status

## Security

- All routes are protected by authentication
- Role-based access control is implemented
- Sensitive data is encrypted
- API endpoints are protected by Sanctum

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support, please open an issue in the GitHub repository or contact the maintainers.

## Project Structure

```
├── app/                 # Application core code
├── config/             # Configuration files
├── database/           # Database migrations and seeders
├── public/             # Publicly accessible files
├── resources/          # Views, raw assets, and language files
├── routes/             # Application routes
├── storage/            # Application storage
└── tests/              # Test files
```

## Author

👤 **Venay pratap singh**

## Acknowledgments

- Laravel Framework
- Bootstrap
- All contributors and supporters of the project

## Support

If you find this project helpful, please give it a ⭐️ on GitHub!

## Screenshots
![1](https://user-images.githubusercontent.com/74867463/144262662-b7fbe66e-5c4c-46fb-8bab-9cf3121c2032.png)
![2](https://user-images.githubusercontent.com/74867463/144262668-545c4d8d-8570-4e38-a769-4c26520e366d.png)
![3](https://user-images.githubusercontent.com/74867463/144262431-32223a06-8c25-49fd-b969-56a4bab697f2.png)
![4](https://user-images.githubusercontent.com/74867463/144262645-29d4bfa4-c737-4123-8c22-c8c1fd49477e.png)


### Prerequisites
- PHP installed
- Composer installed
- IDE to edit and run the code (We use Visual Studio Code 🔥).
- Git to versionning your work.

### Author
👤 **Venay Pratap SIngh**

## 🤝 Contributing
Contributions, issues, and feature requests are welcome!

## Show your support
Give a ⭐️ if you like this project!

## Acknowledgments
- Hat tip to anyone whose code was used
- Inspiration
- etc

## License
This project is licensed under the MIT License.

# SimarGmbH
