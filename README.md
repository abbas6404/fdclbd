# Role Permission Template

A Laravel-based role and permission management system that provides a robust foundation for implementing user roles and permissions in web applications.

## Features

- User Role Management
- Permission Management
- Role-Permission Assignment
- User-Role Assignment
- Secure Authentication
- Modern UI with Vite
- Database Migrations and Seeders

## Requirements

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/PostgreSQL

## Installation

1. Clone the repository:
```bash
git clone https://github.com/abbas6404/diagnostic.git
cd role_permission_template
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install JavaScript dependencies:
```bash
npm install
```

4. Copy the environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env` file

7. Run migrations and seeders:
```bash
php artisan migrate --seed
```

8. Start the development server:
```bash
php artisan serve
```

9. In a separate terminal, start Vite:
```bash
npm run dev
```

## Usage

1. Access the application at `http://localhost:8000`
2. Login with default credentials:
   - Email: admin@example.com
   - Password: password

## Project Structure

- `app/` - Contains the core code of the application
- `database/` - Contains database migrations and seeders
- `resources/` - Contains frontend assets and views
- `routes/` - Contains all route definitions
- `config/` - Contains configuration files

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please open an issue in the GitHub repository or contact the maintainers.

ok... check