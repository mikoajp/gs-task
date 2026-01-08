# Service Manager - Recruitment Task
<img width="1862" height="952" alt="image" src="https://github.com/user-attachments/assets/3c74bd9c-3d65-48be-8f12-6127e3db0403" />
<img width="1858" height="950" alt="image" src="https://github.com/user-attachments/assets/a92188a3-02d2-4223-9fe5-4c8853efe9b4" />


A robust message processing system built with **Laravel 11** and **PHP 8.4**, designed to classify and manage service inspections and failure reports.

## 🚀 Features

- **Advanced Processing Logic**: Automatically classifies messages into "Inspections" or "Failure Reports" based on content analysis.
- **Smart Deduplication**: High-performance (O(1)) deduplication based on message descriptions.
- **CLI Interface**: Process JSON files directly from the terminal with detailed summary tables.
- **Web Dashboard**: Modern, responsive UI built with Blade and Tailwind CSS for file uploads and data visualization.
- **Clean Architecture**: Utilizes DTOs, Enums, Service Objects, and Interfaces for maintainability and scalability.
- **Docker Support**: Ready to run in containers (Nginx + PHP 8.4 FPM).
- **CI/CD Ready**: Integrated GitHub Actions for automated testing and code style enforcement (Pint).

## 🛠 Requirements

- **PHP 8.4+**
- **Composer**
- **Docker** (optional, for containerized environment)

## 📥 Installation

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd gs-task
   ```

2. **Install dependencies**:
   ```bash
   composer install
   ```

3. **Environment Setup**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Initialize Database** (SQLite):
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

## 💻 Usage

### Command Line Interface (CLI)
Process a source file and generate JSON reports:
```bash
php artisan app:process-messages docs/recruitment-task-source.json
```
This will create `inspections.json` and `failure_reports.json` in the root directory.

### Web Interface
Start the local development server:
```bash
php artisan serve
```
Visit `http://127.0.0.1:8000` to access the Dashboard. Here you can:
- Upload new source files.
- View categorized tables with status badges and priority highlights.
- Monitor system statistics.

### Docker
Run the application using Docker Compose:
```bash
docker-compose up -d
```
The app will be available at `http://localhost:8000`.

## 🧪 Testing & Quality

Run the test suite:
```bash
php artisan test
```

Check code style:
```bash
vendor/bin/pint --test
```

## 📂 Project Structure

- `app/Services`: Core business logic for message processing and classification.
- `app/DTOs`: Data transfer objects for structured output.
- `app/Contracts`: Interfaces for decoupling logic.
- `app/Console/Commands`: CLI implementation.
- `resources/views`: Blade templates for the Web UI.
- `.github/workflows`: CI/CD configuration.

## 📝 License
MIT
