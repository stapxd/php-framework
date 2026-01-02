# My PHP Framework

A lightweight PHP framework inspired by Laravel, designed for learning, experimentation, and building structured PHP applications using modern practices.

---

<!-- TABLE OF CONTENTS -->
<details>
  <summary>Table of Contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
      <ul>
        <li><a href="#basic-information">Basic Information</a></li>
      </ul>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#requirements">Requirements</a></li>
        <li><a href="#recomended-environment">Recommended Environment</a></li>
      </ul>
    </li>
    <li><a href="#installation">Installation</a></li>
    <li>
      <a href="#documentation">Documentation</a>
      <ul>
        <li><a href="#migrations">Migrations</a></li>
        <li><a href="#routing">Routing</a></li>
        <li><a href="#middlewares">Middlewares</a></li>
        <li><a href="#service-providers">Service Providers</a></li>
        <li><a href="#session-and-cookies">Session and Cookies</a></li>
        <li><a href="#facades">Facades</a></li>
        <li><a href="#auth">Auth</a></li>
        <li>
          <a href="#database">Database</a>
          <li><a href="#db-facade">DB Facade</a></li>
          <li><a href="#query-builder">Query Builder</a></li>
        </li>
        <li><a href="#validation">Validation</a></li>
      </ul>
    </li>
    <li><a href="#license">License</a></li>
  </ol>
</details>

## About the Project

This framework is a custom PHP project created as a Laravel-like architecture.

### Basic Information

- Written in **pure PHP**
- Uses a **Dependency Injection Container**
- Supports **Service Providers**
- Routing with controllers and middleware
- MVC-inspired application structure
- Built-in **database access layer** (currently supports **MySQL**)
- Built-in **authentication system**
- Designed for educational purposes and small-to-medium projects


---

## Getting Started

Follow these steps to run the project locally.

### Requirements

- **PHP 8.1 or higher**
- **Composer (latest version)**  
  https://getcomposer.org/

### Recommended Environment

You can run the project using any Apache-based server, but it is recommended to use one of the following:

- **Open Server Panel**  
  https://ospanel.io/

- **XAMPP**  
  https://www.apachefriends.org/

> Alternatively, you can manually configure and start an Apache server.

---

## Installation

### 1. Clone the repository

```
git clone https://github.com/stapxd/php-framework.git
```

### 2. Environment Configuration

Create a .env file in the project root:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=
```

> You must create database before you start accessing Database via framework!

### 3. Web Server Configuration

- Document root must point to the public/ directory
- index.php is the application entry point
- Apache mod_rewrite must be enabled

### 4. Composer Configuration

```
cd php-framework
composer install
composer dump-autoload
```

### 5. Running Migrations

```
composer migrate
```

> Make sure Database is created

## Documentation

---

### Migrations

Migrations help manage your database schema in a structured and versioned way.

- **File Naming:** `xxx_name_migration.php`  
  - `xxx` is a sequence number (e.g., `001_user_migration.php`, `002_product_migration.php`).  
- **Class Naming:** The migration class should match the file name **without** the sequence number (e.g., `user_migration`, `product_migration`).

#### Migrations Schema

Every migration class implements `MigrationBase` class and rewrites its methods `up` and `down`

```php
namespace Migration;

use Vendor\Facades\Schema;

class product_migration {
    public function up() {
        Schema::create('products', function($table) {
            $table->id();
            $table->int('quantity', false, false, true);
            $table->int('code', false, true);
            $table->string('title', 100, false);
            $table->text('description');
            $table->double('price', 10, 2, false);
            $table->int('created_by', false, false, true);

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');
        });
    }

    public function down() {
        Schema::dropIfExists('products');
    }
}
```

#### Commands

Run all pending migrations:

```
composer migrate
```

Rollback the last batch of migrations:

```
composer rollback
```

---

### Routing

The routing system in this framework allows you to define application endpoints and attach them to controllers or closures. Routes can handle HTTP methods such as GET, POST, and can be grouped with middleware.

#### Examples

```php
// routes.php

// Define GET route with Closure
Router::get('/routes', function(){
    Router::routesInfo();
});

// Define GET route with Controller class and function in it
Router::get('/', [HomeController::class, 'index']);


// Define POST route with Controller class and function in it
Router::post('/create', [HomeController::class, 'create']);

// Using group
Router::group('form', function() {
    Router::get('/', [HomeController::class, 'formIndex']);
    Router::post('/submit', [HomeController::class, 'formSubmit']);
});
```

---

### Middlewares

Middlewares allow you to run code **before** or **after** a request reaches your route or controller. They are useful for tasks like authentication, logging, or input validation.

#### Applying Middleware to a Route

```php
// Using Middlewares and Groups
Router::middleware([TestMiddleware2::class])->group('users', function() {
    Router::get('/login', [HomeController::class, 'login']);
    Router::post('/login', [HomeController::class, 'loginPost']);

    // Apply middleware to a single route
    Router::get('/register', [HomeController::class, 'register'])->middleware([TestMiddleware3::class]);
    Router::post('/register', [HomeController::class, 'registerPost']);
});
```

#### Global Middleware

You can also define middleware that runs on all requests by registering it in the framework’s application configuration:

```php
// app.php

return Application::instance()
    ->withDatabase(env('DB_CONNECTION', ''))
    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthProvider::class,
    ])
    ->withMiddlewares([   // <------
        App\Middlewares\TestMiddleware1::class,
    ]);
```

#### Middleware structure:

A middleware class should have a handle method that receives the request and a $next callback:

```php
namespace App\Middlewares;

use Vendor\App\Http\Middleware;
use Vendor\Foundation\Request;

class AuthMiddleware extends Middleware {
    public function handle(Request $request, callable $next) {
        if(Auth::currentUser() == null) {
          return redirect(/login);
        }
        return $next($request);
    }
}
```
> Middlewares help keep your application logic clean, reusable, and secure by separating concerns from controllers and routes

---

### Service Providers

Service Providers are the central place to **register services, bindings, and dependencies** in your application.

#### Creating a Service Provider

A service provider is a class that implement the base `ServiceProvider` and rewrites a `register` method:

```php
namespace App\Providers;

use Vendor\Foundation\Request;

class AppServiceProvider implements ServiceProvider {
    public function register() {
        app()->bind(Request::class, fn() => Request::capture(), true);
    }
}
```

#### Registering Service Providers

Service providers should be registered in your framework’s application configuration

```php
// app.php

return Application::instance()
    ->withDatabase(env('DB_CONNECTION', ''))
    ->withProviders([       // <------
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthProvider::class,
    ])
    ->withMiddlewares([
        App\Middlewares\TestMiddleware1::class,
    ]);
```

#### Accessing Services

Once a service is registered, you can access it from the container anywhere in your application:

```php
$request = app(Vendor\Foundation\Request::class);
$request->getMethod();
```

---

### Session and Cookies

The framework provides simple APIs to **store and retrieve data** across requests using sessions and cookies.

#### Sessions

Sessions allow you to persist data on the server for a user across multiple requests.

##### Setting Session Data

```php
use Vendor\General\Session;

// Set a session value
Session::set('user_id', 42);
Session::set('name', 'John');
```

#### Getting Session Data

```php
// Get a session value
$userId = Session::get('user_id', null); // default null if key doesn't exist

// Check if a session key exists
if (Session::has('role')) {
    echo 'Role is set';
}
```

#### Removing Session Data

```php
Session::unset('user_id');   // remove a single key
Session::flush();             // remove all session data
```

#### Flash data

Flash data is used to store temporary session values that **disappear after they are accessed once**. This is useful for showing messages like success alerts, errors, or notifications.

```php
// Settin flash
$errors[] = ["User with email $email already exists.", "Error"];
Session::flash('errors', $errors);

// Getting flash
$errors = Session::flash('errors'); // after that Session will not contain errors
```

#### Cookies

Cookies allow you to store small pieces of data in the user’s browser.

#### Setting Cookies

```php
use Vendor\General\Cookie;

Cookie::set('currect_user', json_encode($user), time() + 60*60*24*7);
```

#### Getting Cookies

```php
return Cookie::exists('currect_user') ? json_decode(Cookie::get('currect_user'), true) : null;
```

#### Removing Cookies

```php
Cookie::delete('user');   // remove a single key
Cookie::clear();             // remove all cookie data
```

---

### Facades

Facades provide a **simple, static-like interface** to services or classes registered in the framework’s service container. They allow you to access complex services without manually resolving them from the container every time.

#### Using a Facade

We've already seen some examples of Facade classes. They are distinguished by using static methods, for example, Router is a Facade:

```php
Router::get('/', [HomeController::class, 'index']);
Router::post('/create', [HomeController::class, 'create']);
```

---

### Auth

The framework provides a simple authentication system to **register, log in, and manage users**

#### Users table

To create users table use command:

```
composer init-auth
```

After that you will see that the file `xxx_user_migration.php` was created in `migrations` folder. You should rename class inside to correspond the migrations naming [see Migrations section](#migrations)

#### Using Auth

You can use either default Auth Service (uses email and password) or Create your own based on default.
For that you must create a provider first:

```php
// AuthProvider.php
namespace App\Providers;

use Vendor\Auth\Auth;

class AuthProvider implements ServiceProvider {
    public function register() {
        app()->bind('auth', Auth::class, true);
    }
}

// app.php
use Vendor\App\Application;

return Application::instance()
    ->withDatabase(env('DB_CONNECTION', ''))
    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthProvider::class,        // <------
    ])
    ->withMiddlewares([
        App\Middlewares\TestMiddleware1::class,
    ]);
```

If you want to create your own you may do this by creating new Service:

```php
namespace App\Services;

use Vendor\Auth\Auth;

class MyAuth extends Auth {
    public function __construct() {
        parent::__construct();
        $this->fields = [
            'login' => 'username',
            'password' => 'password'
        ];
    }
}
```

After that you will have access to Auth Facade and use its functionality:

```php
Auth::register([
    'email' => $email,
    'password' => $password
]);

Auth::login([
    'email' => $email,
    'password' => $password
]);

$user = Auth::currentUser();
```

---

### Database

The framework provides a **simple database abstraction layer** through the `DB` facade. It allows you to execute queries, fetch results, and interact with the database without writing raw PDO code each time.

#### DB Facade

The `DB` facade is the main entry point for database operations. It provides access to the query builder and supports raw SQL if needed.

```php
use Vendor\Facades\DB;

if(DB::doesTableExist('users') {
  DB::query(SELECT * FROM users);
}

if(DB::isConnected()) {
  DB::dropIfExists('users');
}
```

#### Query Builder

The **Query Builder** provides a fluent interface for building database queries without writing raw SQL. It is accessible via the `DB` facade.

**Selecting Data**

```php
DB::table('products')->select()
->innerJoin('users', 'products.created_by', 'users.id')
->where([
  'title' => 'Title',
  'username' => 'User'
])
->orderBy('users.id', ASC)
->limit(10)
->find();
```

**Inserting data**

```php
DB::table('posts')->insert([
    'title' => 'Title',
    'text'  => 'Description'
]);
```

**Deleting data**

```php
DB::table('posts')->delete([
    'id' => 1
]);
```

---

### Validation

The framework provides a simple **validation system** to ensure that user input meets your application’s rules before processing. You can validate requests using the `Validator` class.

#### Basic Usage

Validator takes information from Request parameters

```php
use Vendor\General\Validator\Validator;

$validated = Validator::validate($request, [
    'username' => 'required|min_length:3|max_length:10',
    'email' => 'unique:users|regexp:/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/',
    'age' => 'min:18|max:100'
]);
```

Or you can use your data and rules:

```php
// Define data and rules
$data = [
    'name' => 'Artem',
    'email' => 'artem@example.com',
    'age' => 17
];

$rules = [
    'name' => 'required|max:50',
    'email' => 'required',
    'age' => 'required|min:13'
];

// Validate data
$validated = Validator::validate($data, $rules);
```

## License

Educational
