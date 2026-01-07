# 🚀 PHP Native REST API

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](CONTRIBUTING.md)

> **REST API sederhana menggunakan PHP Native** tanpa framework untuk tujuan edukatif. Cocok untuk memahami cara kerja REST API di level paling dasar sebelum menggunakan framework seperti Laravel.

---

## 📚 Apa yang Dipelajari?

- ✅ **Routing Manual** - Parsing `REQUEST_METHOD` dan `REQUEST_URI` tanpa library
- ✅ **Request/Response Cycle** - Cara kerja HTTP di PHP level rendah
- ✅ **JSON Handling** - Parsing body JSON dengan `php://input`
- ✅ **PDO Database** - Query database dengan prepared statements
- ✅ **MVC Pattern** - Separation of concerns secara manual
- ✅ **Error Handling** - Global exception handler
- ✅ **CORS Handling** - Cross-origin resource sharing
- ✅ **Security** - SQL injection prevention, validation

---

## 🛠️ Tech Stack

| Component | Technology |
|-----------|------------|
| **Language** | PHP 8.0+ |
| **Database** | SQLite / MySQL |
| **Web Server** | PHP Built-in Server / Apache |
| **Architecture** | MVC Pattern |

---

## 📁 Struktur Project

```
php-native-rest-api/
├── index.php              # 🚪 Entry point (Front Controller)
├── routes.php             # 🗺️ Routing manual
├── .gitignore            # 🚫 Git ignore rules
│
├── config/
│   └── database.php      # 🔌 Database connection (PDO)
│
├── controllers/
│   └── UserController.php # 🎮 User controller (handle requests)
│
├── models/
│   └── User.php          # 📊 User model (database queries)
│
├── helpers/
│   ├── Response.php      # 📤 JSON response helper
│   ├── Request.php       # 📥 Request parser helper
│   ├── Validator.php     # ✅ Validation helper
│   └── Logger.php        # 📝 Logging system
│
├── middleware/
│   ├── JsonMiddleware.php # 🔧 JSON header middleware
│   ├── AuthMiddleware.php # 🔐 Authentication middleware
│   └── RateLimiter.php   # ⏱️ Rate limiting middleware
│
├── logs/                 # 📋 Log files (auto-generated)
│   └── YYYY-MM-DD.log
│
└── database.sqlite       # 💾 SQLite database (auto-created)
```

---

## 🚀 Quick Start

### 1️⃣ Clone Repository

```bash
git clone https://github.com/username/php-native-rest-api.git
cd php-native-rest-api
```

### 2️⃣ Setup Database

**Option A: SQLite (Default - No Setup Required)**

Database akan otomatis terbuat saat pertama kali dijalankan.

**Option B: MySQL**

```bash
# 1. Buat database
mysql -u root -p

CREATE DATABASE php_rest_api CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;

# 2. Edit config/database.php (uncomment MySQL, comment SQLite)
```

### 3️⃣ Jalankan Server

```bash
php -S localhost:8000
```

Server berjalan di: **http://localhost:8000**

### 4️⃣ Test API

```bash
# Test dengan curl
curl http://localhost:8000/users

# Atau buka di browser
open http://localhost:8000/users
```

---

## 📡 API Endpoints

### 🔓 Public Endpoints (No Auth Required)

| Method | Endpoint | Description |
|--------|----------|-------------|
| **GET** | `/users` | Get all users (with pagination) |
| **GET** | `/users/{id}` | Get user by ID |
| **GET** | `/users/search?q=keyword` | Search users |
| **POST** | `/login` | Login & get token |

### 🔐 Protected Endpoints (Auth Required)

| Method | Endpoint | Description |
|--------|----------|-------------|
| **POST** | `/users` | Create new user |
| **PUT** | `/users/{id}` | Update user |
| **DELETE** | `/users/{id}` | Delete user |

### 📊 Utility Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| **GET** | `/logs` | View application logs |
| **GET** | `/logs?date=YYYY-MM-DD` | View logs by date |

---

## 📝 Usage Examples

### 1. Get All Users (with Pagination)

**Request:**
```bash
curl "http://localhost:8000/users?page=1&limit=10"
```

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [
      {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-01-07 10:30:00"
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 10,
      "total": 25,
      "total_pages": 3,
      "has_next": true,
      "has_prev": false
    }
  },
  "timestamp": "2025-01-07 10:30:00"
}
```

### 2. Search Users

**Request:**
```bash
curl "http://localhost:8000/users/search?q=john&page=1&limit=5"
```

**Response:**
```json
{
  "success": true,
  "data": {
    "data": [...],
    "pagination": {...},
    "search": {
      "keyword": "john",
      "found": 3
    }
  }
}
```

### 3. Login & Get Token

**Request:**
```bash
curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}'
```

**Response:**
```json
{
  "success": true,
  "data": {
    "message": "Login successful",
    "token": "token_user_123",
    "user": {
      "id": 1,
      "name": "Admin"
    }
  }
}
```

### 4. Create User (Protected)

**Request:**
```bash
curl -X POST http://localhost:8000/users \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer token_user_123" \
  -d '{
    "name": "Jane Doe",
    "email": "jane@example.com"
  }'
```

**Response:**
```json
{
  "success": true,
  "data": {
    "message": "User created successfully",
    "user": {
      "id": 2,
      "name": "Jane Doe",
      "email": "jane@example.com",
      "created_at": "2025-01-07 10:35:00"
    }
  }
}
```

### 5. Update User (Protected)

**Request:**
```bash
curl -X PUT http://localhost:8000/users/1 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer token_user_123" \
  -d '{
    "name": "John Doe Updated",
    "email": "john.updated@example.com"
  }'
```

### 6. Delete User (Protected)

**Request:**
```bash
curl -X DELETE http://localhost:8000/users/1 \
  -H "Authorization: Bearer token_user_123"
```

---

## 🔐 Authentication

API menggunakan **Bearer Token Authentication**.

### Cara Mendapatkan Token:

1. Login dengan credentials yang valid
2. Server akan return token
3. Gunakan token di header untuk protected endpoints

### Format Header:

```
Authorization: Bearer your_token_here
```

### Default Credentials (Demo):

```
Email: admin@example.com
Password: password
Token: token_user_123
```

> ⚠️ **Production Warning:** Jangan gunakan hardcoded credentials! Implement proper authentication dengan password hashing (bcrypt/argon2) dan database storage.

---

## 🛡️ Security Features

- ✅ **SQL Injection Prevention** - Prepared statements (PDO)
- ✅ **Input Validation** - Custom validator helper
- ✅ **Rate Limiting** - 60 requests per minute per IP
- ✅ **CORS Headers** - Configured for cross-origin requests
- ✅ **Bearer Token Auth** - Protected endpoints
- ✅ **Error Handling** - Global exception handler

---

## 📦 Features

### Core Features (Main)
- ✅ CRUD Operations (Create, Read, Update, Delete)
- ✅ JSON Request/Response
- ✅ Manual Routing (Regex-based)
- ✅ MVC Architecture
- ✅ PDO Database (SQLite/MySQL)

### Advanced Features (Contributions)
- ✅ **Pagination** - `?page=1&limit=10`
- ✅ **Search & Filter** - `?q=keyword`
- ✅ **Validation** - Rule-based validation
- ✅ **Rate Limiting** - 60 req/min per IP
- ✅ **Authentication** - Bearer token
- ✅ **Logging System** - Daily log rotation

---

## 🎓 Konsep Backend yang Dipelajari

### 1. Single Entry Point Pattern
Semua request masuk lewat `index.php` (Front Controller).

```
Request → index.php → routes.php → Controller → Model → Response
```

### 2. Routing Manual
Parsing `REQUEST_METHOD` dan `REQUEST_URI` dengan regex:

```php
// GET /users/{id}
if ($method === 'GET' && preg_match('/^\/users\/(\d+)$/', $uri, $matches)) {
    $id = $matches[1]; // Extract ID dari URI
    $userController->show($id);
}
```

### 3. php://input vs $_POST

| Aspect | $_POST | php://input |
|--------|--------|-------------|
| **Format** | form-data | JSON, XML, raw |
| **Auto Parse** | ✅ Yes | ❌ No (manual) |
| **REST API** | ❌ Not suitable | ✅ Required |

```php
// ❌ SALAH untuk JSON
$data = $_POST;

// ✅ BENAR untuk JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);
```

### 4. Prepared Statements (SQL Injection Prevention)

```php
// ❌ VULNERABLE
$sql = "SELECT * FROM users WHERE id = $id";

// ✅ SECURE
$stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
$stmt->execute(['id' => $id]);
```

### 5. HTTP Status Codes

| Code | Meaning | Usage |
|------|---------|-------|
| **200** | OK | Successful GET, PUT |
| **201** | Created | Successful POST |
| **204** | No Content | Successful DELETE |
| **400** | Bad Request | Invalid input |
| **401** | Unauthorized | Missing/invalid token |
| **404** | Not Found | Resource not found |
| **422** | Unprocessable Entity | Validation failed |
| **429** | Too Many Requests | Rate limit exceeded |
| **500** | Internal Server Error | Server error |

---

## 🆚 PHP Native vs Framework

| Aspect | PHP Native (This Project) | Laravel |
|--------|---------------------------|---------|
| **Routing** | Manual regex parsing | `Route::get('/users', ...)` |
| **Request Body** | `file_get_contents('php://input')` | `$request->input('name')` |
| **JSON Response** | `echo json_encode()` + header | `return response()->json()` |
| **Database** | PDO manual | Eloquent ORM |
| **Validation** | Custom validator | Form Request / Validator facade |
| **Error Handling** | `set_exception_handler()` | Global Exception Handler |
| **Middleware** | Manual function call | Middleware stack |
| **Authentication** | Manual token check | Laravel Passport / Sanctum |

### Kenapa Belajar PHP Native?

1. **Memahami Fundamental** - Tahu apa yang framework lakukan di background
2. **Better Debugging** - Tidak bingung saat ada error di framework
3. **Appreciation** - Lebih menghargai kemudahan framework
4. **Problem Solving** - Bisa bikin custom solution kalau framework tidak cocok

---

## 🐛 Troubleshooting

### Error: "Database connection failed: could not find driver"

**Solusi:**
```bash
# 1. Cari php.ini
php --ini

# 2. Edit php.ini, uncomment:
extension=pdo_sqlite
extension=sqlite3

# 3. Restart server
```

### Error: "mkdir(): Permission denied"

**Solusi (Linux/Mac):**
```bash
chmod 755 .
```

**Solusi (Windows):**
Right-click folder → Properties → Security → Full Control

### Error: "Address already in use"

**Solusi:**
```bash
# Ganti port
php -S localhost:8001
```

### CORS Error di Frontend

Sudah di-handle di `JsonMiddleware.php`. Pastikan middleware dipanggil di `index.php`.

---

## 🤝 Contributing

Contributions are welcome! Silakan buat PR untuk:

- 🐛 Bug fixes
- ✨ New features
- 📝 Documentation improvements
- 🎨 Code refactoring

### Contribution Ideas:
- [ ] JWT Authentication
- [ ] File Upload Handler
- [ ] Email Notifications
- [ ] Soft Deletes
- [ ] API Versioning (v1, v2)
- [ ] WebSocket Support
- [ ] Caching Layer (Redis)
- [ ] Unit Testing (PHPUnit)

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

**Your Name**
- GitHub: [@Yogiexc](https://github.com/Yogiexc)
- Email: yogiexsaputra@gmail.com

---

## 🌟 Show Your Support

Give a ⭐️ if this project helped you!

---

## 📚 Resources

- [PHP Official Documentation](https://www.php.net/manual/en/)
- [PDO Documentation](https://www.php.net/manual/en/book.pdo.php)
- [REST API Best Practices](https://restfulapi.net/)
- [HTTP Status Codes](https://httpstatuses.com/)

---

## 🎯 Next Steps

Setelah menguasai project ini, coba:

1. **Framework PHP** - Laravel, Symfony, Slim
2. **API Documentation** - Swagger/OpenAPI
3. **Testing** - PHPUnit untuk unit testing
4. **Deployment** - Deploy ke VPS/cloud
5. **Microservices** - Pecah jadi multiple services
6. **GraphQL** - Alternative to REST

---

## 💡 Tips

- Gunakan **Postman** atau **Insomnia** untuk testing (lebih mudah dari cURL)
- Enable `error_reporting` saat development
- Gunakan **try-catch** di semua controller methods
- Log semua error ke file untuk debugging
- Validate input sebelum masuk database
- Gunakan **git** untuk version control

---

<p align="center">
  Made with ❤️ for learning purposes
</p>

<p align="center">
  <strong>⚠️ Educational Project - Not for Production Use</strong>
</p>