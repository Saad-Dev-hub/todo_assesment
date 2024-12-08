# Laravel Todo List Application

A powerful and modern task management application built with Laravel, featuring real-time updates, dynamic filtering, and a beautiful UI.

## Features

- 🔐 User Authentication with Laravel Breeze
- ✨ Real-time task updates with AJAX
- 📱 Responsive design using Bootstrap and Tailwind CSS
- 🔍 Dynamic search and filtering
- 📂 Category management
- ✅ Task status toggling
- 🗑️ Soft deletes for tasks
- 🎨 Beautiful UI with Bootstrap 5
- ⚡ Fast and smooth interactions

## Requirements

- PHP >= 8.1
- Composer
- Node.js >= 18
- MySQL >= 8.0
- Git

## Installation Steps

1. **Clone the Repository**
   ```bash
   git clone <your-repo-url>
   cd todo-list
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Node Dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure Database**
   - Open `.env` file and update database credentials:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=todo_list
     DB_USERNAME=your_username
     DB_PASSWORD=your_password
     ```

6. **Run Migrations and Seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Build Assets**
   ```bash
   npm run build
   ```

8. **Start the Development Server**
   ```bash
   php artisan serve
   ```

## Usage

1. **Access the Application**
   - Open your browser and visit: `http://localhost:8000`
   - Register a new account or login

2. **Managing Tasks**
   - Click "New Task" to create a task
   - Use the filters sidebar to search and filter tasks
   - Click the checkbox to toggle task status
   - Use the category dropdown to organize tasks
   - Click the delete button to remove tasks

## Project Structure

```
todo-list/
├── app/
│   ├── Http/Controllers/    # Controllers
│   ├── Models/             # Eloquent Models
│   └── Providers/          # Service Providers
├── database/
│   ├── migrations/         # Database Migrations
│   └── seeders/           # Database Seeders
├── resources/
│   ├── views/             # Blade Templates
│   ├── css/              # CSS Assets
│   └── js/               # JavaScript Assets
└── routes/
    └── web.php           # Web Routes
```

## Security

- CSRF protection enabled
- Form validation
- Secure password hashing
- User authentication
- Input sanitization

## Development Notes

- Uses Laravel Breeze for authentication
- Implements Repository pattern
- AJAX-powered interactions
- Bootstrap 5 for UI components
- Tailwind CSS for utilities
- Soft deletes implemented

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- Laravel Team
- Bootstrap Team
- All contributors
