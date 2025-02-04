# **Custom MVC Framework**

## **📌 Overview**

The **Custom MVC Framework** is a **lightweight and minimalistic** PHP-based **Model-View-Controller (MVC)** architecture designed for efficient web application development. This framework provides a structured way to build applications while keeping the code **clean, modular, and scalable**.

Built with **pure PHP**, this framework follows the **SOLID principles**, ensuring maintainability and flexibility. It includes essential features like **routing, request handling, controller management, database interaction, middleware support, CSRF protection, and session handling**.

---

## **🚀 Key Features**

✔ **Lightweight & Fast** – Designed for high performance with minimal overhead.  
✔ **Custom Routing System** – Supports dynamic route handling with middleware.  
✔ **Controller-Based Architecture** – Keeps logic and presentation separate.  
✔ **Model & Database Handling** – Built-in database class for easy interaction.  
✔ **Template Engine Support** – Clean and structured view management.  
✔ **Middleware System** – Allows request filtering and security enhancements.  
✔ **CSRF Protection** – Ensures secure form submissions.  
✔ **Session & Cookie Handling** – Easy session management.  
✔ **Error Handling & Logging** – Debugging and error tracking support.  
✔ **REST API Support** – Can be extended for API-based applications.  
✔ **Custom Configuration Files** – Easy environment and settings management.  
✔ **Security Best Practices** – Prevents SQL Injection, XSS, and CSRF attacks.

---

## **🛠 System Design & Architecture**

### **1️⃣ MVC Pattern**

The framework follows a standard **Model-View-Controller (MVC)** pattern:

- **Model (M)** – Manages database interactions and business logic.
- **View (V)** – Handles UI rendering and user interface components.
- **Controller (C)** – Processes user requests and coordinates between models and views.

### **2️⃣ Core Components**

- **Router** – Manages URL mapping and request forwarding.
- **Request & Response Handling** – Parses input and generates output.
- **Middleware** – Enables request filtering (e.g., authentication, CSRF checks).
- **Database Class** – Supports prepared statements for secure queries.
- **View Engine** – Loads and renders templates dynamically.
- **Session Manager** – Provides secure session handling.

### **3️⃣ Execution Flow**

1. **User Request** → Routed to appropriate **Controller**.
2. **Controller Processes Request** → Uses **Model** for data handling.
3. **Model Fetches Data** → Sends response back to **Controller**.
4. **Controller Sends Data to View** → View renders final output.

---

## **📂 Folder Structure**

```
/php-mvc
│── /app
│   ├── /contracts                  # Interface definitions for core components
│   │── /core
│   │   ├── App.php                 # Bind and resolves containers
│   │   ├── Auth.php                # Manages user authentication logic
│   │   ├── Authenticator.php       # Handles user authentication processes
│   │   ├── Config.php              # Retrieves application configuration settings
│   │   ├── Container.php           # Implements dependency injection container
│   │   ├── Controller.php          # Base class for all controllers
│   │   ├── CSRF.php                # Handles CSRF token validation and protection
│   │   ├── DB.php                  # Database connection and query builder
│   │   ├── Env.php                 # Loads and manages environment variables
│   │   ├── functions.php           # Collection of global helper functions
│   │   ├── Request.php             # Handles incoming HTTP requests
│   │   ├── Response.php            # Manages HTTP responses
│   │   ├── Router.php              # Defines and manages application routes
│   │   ├── Sanitizer.php           # Cleans and sanitizes user input
│   │   ├── Session.php             # Manages session handling
│   │   ├── Validator.php           # Validates incoming request data
│   │   ├── View.php                # Renders views/templates with data
│   │── /Http
│   │   │── /Controllers            # Application controllers (Handles business logic)
│   │   │── /Middlewares            # Middleware handlers for request filtering
│   ├── /Models                     # Database models (Handles data interactions)
│   ├── /Utils                      # Utility classes and helper functions
│── /config
│   ├── config.php                  # Main application configuration file
│── /public
│   ├── .htaccess                   # Apache rewrite rules for pretty URLs
│   ├── index.php                   # Application entry point (Initializes the framework)
│   ├── robots.txt                  # Controls search engine indexing
│── /routes                         # Defines routes
│── /vendor                         # Composer dependencies and autoloaded files
│── /views                          # Stores UI templates and frontend views
│── .env.example                    # Example environment variables file
│── .gitignore                      # Git ignore rules (Excludes unnecessary files)
│── bootstrap.php                   # Initializes application bindings and services
│── composer.json                   # Composer package dependencies
│── README.md                       # Documentation file
```

---

## **📥 Installation & Setup**

### **1️⃣ Clone the Repository**

```sh
git clone https://github.com/naymur92/php-mvc.git
cd php-mvc
```

### **2️⃣ Configure Environment**

Rename `.env.example` to `.env` and set up your database credentials:

```sh
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=php_mvc
DB_USERNAME=root
DB_PASSWORD=your_password
```

### **3️⃣ Update Autoloads**

```sh
composer dump-autoload
```

### **4️⃣ Start the Development Server**

```sh
php -S localhost:8000 -t public
```

Access the application at **http://localhost:8000**.

---

## **📖 Basic Usage Guide**

### **📌 Defining Routes**

Define application routes inside **`/routes/web.php`**:

```php
$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthenticationController::class, 'index']);
```

### **📌 Creating Controllers**

Create a new controller inside `/app/Http/Controllers/`:

```php
class HomeController extends Controller {
    public function index() {
        view('pages.homepage', array('title' => "Home"));
    }
}
```

### **📌 Defining Models**

Define database models inside `/app/Models/`:

```php
class User extends BaseModel {
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected array $protected = ['password'];
    protected array $fillable = ['user_id', 'name', 'email', 'created_at', 'updated_at'];
}
```

### **📌 Creating Views**

Store views inside `/views/`:

```html
<h1>Welcome to Custom MVC Framework</h1>
<p>
  Hello,
  <?= $title ?>!
</p>
```

---

## **🔐 Security Features**

- **CSRF Protection** – Built-in CSRF token validation for forms.
- **Prepared Statements** – Prevents SQL injection in database queries.
- **Session Security** – Secure session management with regeneration.
- **Middleware Support** – Adds layers of security before processing requests.

---

## **⚡ Performance Optimizations**

- **Autoloading** – Uses Composer for efficient class loading.
- **Minimal Core** – Lightweight structure ensures fast execution.

---

## **📜 License**

This project is **open-source and free** under the **MIT License**. You are free to modify, distribute, and use it for both personal and commercial projects.

---

## **👤 Author**

**Name:** Naymur Rahman  
**GitHub:** [naymur92](https://github.com/naymur92)  
**Email:** [naymur92@gmail.com](mailto:naymur92@gmail.com)

---

## **📌 Final Thoughts**

The **Custom MVC Framework** is designed for developers who want a **simple, scalable, and easy-to-use** MVC structure in PHP.

Feel free to **contribute, report issues, or suggest improvements** to make this framework even better! 🚀
