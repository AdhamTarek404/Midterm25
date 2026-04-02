# Online Library Management System - Complete Guide

## Table of Contents

1. [How Laravel Works (Architecture)](#how-laravel-works)
2. [Project File Map](#project-file-map)
3. [Models Explained](#models)
4. [Controllers Explained](#controllers)
5. [Routes Explained](#routes)
6. [Blade Views Explained](#blade-views)
7. [Middleware Explained](#middleware)
8. [Migrations Explained](#migrations)
9. [Security Features](#security-features)
10. [Important Code Blocks](#important-code-blocks)
11. [How Each Feature Works](#how-each-feature-works)

---

## How Laravel Works

Laravel follows the **MVC** pattern: **Model - View - Controller**.

Here is the flow of every request:

```
User clicks a link or submits a form
        ↓
  routes/web.php     ← Decides WHICH controller method to call
        ↓
  Middleware          ← Checks: Is user logged in? Does user have the right role?
        ↓
  Controller          ← The brain. Gets data from Models, passes it to Views
        ↓
  Model               ← Talks to the database. Each model = one table
        ↓
  Blade View          ← The HTML page. Receives data from controller and displays it
        ↓
  User sees the page
```

### Example flow: User opens /books

1. Browser sends GET request to `/books`
2. `routes/web.php` finds: `Route::get('books', [BookController::class, 'list'])` → calls `BookController@list`
3. No middleware on this route → everyone can access it
4. `BookController::list()` runs: `$books = Book::all()` → gets all books from database
5. Controller returns: `return view('books.list', compact('books'))` → sends data to blade
6. `resources/views/books/list.blade.php` renders HTML with the books data
7. User sees the books table in their browser

---

## Project File Map

### Database Layer

| File | Location | What It Does |
|------|----------|--------------|
| `create_users_table.php` | `database/migrations/` | Creates the `users` table with columns: id, name, email, password, **role** (default 'member'), timestamps |
| `create_books_table.php` | `database/migrations/` | Creates the `books` table with columns: id, title, author, isbn, copies, timestamps |
| `create_borrows_table.php` | `database/migrations/` | Creates the `borrows` table with columns: id, user_id (FK), book_id (FK), borrowed_at, timestamps |
| `DatabaseSeeder.php` | `database/seeders/` | Creates the initial admin account (admin@admin.com / admin123) |

### Models (app/Models/)

| File | What It Does |
|------|--------------|
| `User.php` | Represents a user. Has fields: name, email, password, role. Has relationship: `borrows()` → a user has many borrows |
| `Book.php` | Represents a book. Has fields: title, author, isbn, copies. Has relationship: `borrows()` → a book has many borrows |
| `Borrow.php` | Represents a borrow record. Has fields: user_id, book_id, borrowed_at. Has relationships: `user()` and `book()` → belongs to one user and one book |

### Controllers (app/Http/Controllers/Web/)

| File | What It Does |
|------|--------------|
| `UsersController.php` | Handles: register, login, logout, profile, view members, create librarian, view roles |
| `BookController.php` | Handles: list all books, add book, edit book, delete book |
| `BorrowController.php` | Handles: borrow a book, view borrow history |

### Middleware (app/Http/Middleware/)

| File | What It Does |
|------|--------------|
| `RoleMiddleware.php` | Checks if the logged-in user has the required role (admin, librarian, or member). Blocks access with 403 if not |
| `Authenticate.php` | Built-in Laravel. Redirects to login page if user is not logged in |

### Routes

| File | What It Does |
|------|--------------|
| `routes/web.php` | Maps every URL to a controller method. Attaches middleware to protect routes |

### Blade Views (resources/views/)

| File | What It Does |
|------|--------------|
| `layouts/master.blade.php` | The main HTML skeleton. All other pages extend this. Contains Bootstrap CSS/JS and flash messages |
| `layouts/menu.blade.php` | The navigation bar. Shows different links based on whether user is logged in and what their role is |
| `welcome.blade.php` | Home page |
| `users/register.blade.php` | Registration form (name, email, password, confirm password) |
| `users/login.blade.php` | Login form (email, password) |
| `users/profile.blade.php` | Shows user info, borrowing limit/status, and list of borrowed books |
| `users/members.blade.php` | Shows all registered users (admin + librarian only) |
| `users/create-librarian.blade.php` | Form to create a new librarian account (admin only) |
| `users/roles.blade.php` | Shows all roles and their permissions, plus users by role (admin only) |
| `books/list.blade.php` | Shows all books in a table. Borrow button for members, Edit/Delete for admin+librarian |
| `books/edit.blade.php` | Form to add or edit a book (title, author, isbn, copies) |
| `borrows/history.blade.php` | Shows all books the logged-in user has borrowed |

### Config

| File | What It Does |
|------|--------------|
| `.env` | Environment config. Sets database name (`library`), DB username (`root`), DB password (empty for XAMPP) |
| `app/Http/Kernel.php` | Registers all middleware. We added `'role' => RoleMiddleware::class` here |

---

## Models

A **Model** represents one database table. Each instance of the model = one row in the table.

### User.php

```php
class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role'];

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
```

- `$fillable` → which columns can be mass-assigned (security feature - prevents unwanted fields from being set)
- `borrows()` → defines a **one-to-many** relationship: one user can have many borrow records
- Extends `Authenticatable` → gives us `Auth::attempt()`, `auth()->user()`, etc.
- `$hidden = ['password', 'remember_token']` → these fields won't show in JSON responses

### Book.php

```php
class Book extends Model
{
    protected $fillable = ['title', 'author', 'isbn', 'copies'];

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
```

- Fields match the exam requirement: Title, Author, ISBN, Copies
- `borrows()` → one book can be borrowed many times

### Borrow.php

```php
class Borrow extends Model
{
    protected $fillable = ['user_id', 'book_id', 'borrowed_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
```

- This is a **junction table** connecting users and books
- `belongsTo` → each borrow record belongs to one user and one book
- `user_id` and `book_id` are **foreign keys** pointing to the users and books tables

### How Relationships Work

```
User (1) ──── has many ────> Borrow (many)
Book (1) ──── has many ────> Borrow (many)
Borrow ──── belongs to ────> User (1)
Borrow ──── belongs to ────> Book (1)
```

Using them in code:
- `$user->borrows` → gets all borrow records for this user
- `$borrow->book` → gets the book object for this borrow
- `$borrow->book->title` → gets the title of the borrowed book
- `Borrow::where('user_id', auth()->id())->with('book')->get()` → gets all borrows for current user, loading the book data too (`with('book')` is called **eager loading** and prevents extra database queries)

---

## Controllers

A **Controller** is the brain of the application. It receives the request, processes it, talks to models, and returns a view.

### UsersController.php - Key Methods

**`register()`** - Just shows the registration form:
```php
public function register(Request $request) {
    return view('users.register');
}
```

**`doRegister()`** - Processes the registration form:
```php
public function doRegister(Request $request) {
    // 1. Validate inputs
    $this->validate($request, [
        'name' => ['required', 'string', 'min:3', 'max:128'],
        'email' => ['required', 'email', 'max:255'],
        'password' => ['required', 'confirmed', 'min:8'],
    ]);

    // 2. Check duplicate email (with generic error to prevent user enumeration)
    if (User::where('email', $request->email)->first()) {
        return redirect()->route('register')
            ->withErrors(['email' => 'Registration failed. Please try again.']);
    }

    // 3. Create user with bcrypt password and default role = member
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->role = 'member';
    $user->save();

    return redirect()->route('login');
}
```

**`doLogin()`** - Processes login:
```php
public function doLogin(Request $request) {
    $this->validate($request, [
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Auth::attempt checks email AND compares bcrypt hash of password
    // Returns false if email doesn't exist OR password is wrong
    if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['login' => 'Invalid email or password']);
        // ↑ GENERIC error - doesn't reveal which part is wrong
    }

    $user = User::where('email', $request->email)->first();
    Auth::setUser($user);
    return redirect('/');
}
```

**`doLogout()`** - Logs user out and destroys session:
```php
public function doLogout(Request $request) {
    Auth::logout();
    return redirect('/');
}
```

**`profile()`** - Shows current user's profile with borrow info:
```php
public function profile(Request $request) {
    $user = auth()->user();                              // Get logged-in user
    $borrows = $user->borrows()->with('book')->get();    // Get their borrows with book data
    $borrowLimit = 3;                                    // Max allowed borrows
    $borrowCount = $borrows->count();                    // How many they have now
    return view('users.profile', compact('user', 'borrows', 'borrowLimit', 'borrowCount'));
}
```

**`storeLibrarian()`** - Admin creates a librarian:
```php
public function storeLibrarian(Request $request) {
    $this->validate($request, [...]);
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = bcrypt($request->password);
    $user->role = 'librarian';    // ← This is the key difference from normal registration
    $user->save();
    return redirect()->route('members');
}
```

### BookController.php - Key Methods

**`list()`** - Shows all books (public page):
```php
public function list(Request $request) {
    $books = Book::all();    // Eloquent: SELECT * FROM books
    return view('books.list', compact('books'));
}
```

**`save()`** - Creates or updates a book:
```php
public function save(Request $request, Book $book = null) {
    $this->validate($request, [
        'title' => ['required', 'string', 'max:255'],
        'author' => ['required', 'string', 'max:255'],
        'isbn' => ['required', 'string', 'max:13'],
        'copies' => ['required', 'integer', 'min:0'],
    ]);
    $book = $book ?? new Book();     // If $book is null, create new; otherwise edit existing
    $book->fill($request->all());    // Fill all fillable fields from form data
    $book->save();                   // INSERT or UPDATE in database
    return redirect()->route('books_list');
}
```

**`delete()`** - Deletes a book:
```php
public function delete(Request $request, Book $book) {
    $book->delete();    // Eloquent: DELETE FROM books WHERE id = ?
    return redirect()->route('books_list');
}
```

### BorrowController.php - Key Methods

**`borrow()`** - The most critical method in the exam:
```php
public function borrow(Request $request, Book $book) {
    // CHECK 1: Is the book available?
    if ($book->copies <= 0) {
        return redirect()->route('books_list')
            ->with('error', 'Book Currently Unavailable');
        //                    ↑ Exact message required by exam
    }

    // CHECK 2: Has user reached borrowing limit?
    $borrowCount = Borrow::where('user_id', auth()->id())->count();
    if ($borrowCount >= 3) {
        return redirect()->route('books_list')
            ->with('error', 'You have reached your borrowing limit (3 books).');
    }

    // ACTION 1: Decrease available copies
    $book->copies -= 1;
    $book->save();

    // ACTION 2: Save borrow record
    $borrow = new Borrow();
    $borrow->user_id = auth()->id();
    $borrow->book_id = $book->id;
    $borrow->borrowed_at = now();
    $borrow->save();

    return redirect()->route('books_list')
        ->with('success', 'Book borrowed successfully!');
}
```

---

## Routes

Routes are defined in `routes/web.php`. A route maps a URL + HTTP method to a controller method.

### Route Syntax

```php
Route::get('url', [Controller::class, 'method'])->name('route_name')->middleware('middleware');
//     ↑       ↑              ↑            ↑              ↑                      ↑
//  HTTP     URL path     Controller    Method to    Name (used in       Security check
//  method                 class        call         blade with           before controller
//                                                   route('name'))       runs
```

### All Routes in This Project

**Public routes (no middleware):**
```php
Route::get('/', function () { return view('welcome'); });           // Home page
Route::get('register', [UsersController::class, 'register']);       // Show register form
Route::post('register', [UsersController::class, 'doRegister']);    // Process registration
Route::get('login', [UsersController::class, 'login']);             // Show login form
Route::post('login', [UsersController::class, 'doLogin']);          // Process login
Route::get('logout', [UsersController::class, 'doLogout']);         // Logout
Route::get('books', [BookController::class, 'list']);               // View all books
```

**Authenticated routes (must be logged in):**
```php
Route::get('profile', ...)->middleware('auth:web');                  // My profile
Route::get('books/borrow/{book}', ...)->middleware('auth:web');      // Borrow a book
Route::get('borrows/history', ...)->middleware('auth:web');          // My borrow history
```

**Admin + Librarian routes:**
```php
Route::get('members', ...)->middleware(['auth:web', 'role:admin,librarian']);
Route::get('books/edit/{book?}', ...)->middleware(['auth:web', 'role:admin,librarian']);
Route::post('books/save/{book?}', ...)->middleware(['auth:web', 'role:admin,librarian']);
Route::get('books/delete/{book}', ...)->middleware(['auth:web', 'role:admin,librarian']);
```

**Admin only routes:**
```php
Route::get('librarian/create', ...)->middleware(['auth:web', 'role:admin']);
Route::post('librarian/store', ...)->middleware(['auth:web', 'role:admin']);
Route::get('roles', ...)->middleware(['auth:web', 'role:admin']);
```

### GET vs POST

- **GET** → Reading/viewing data (clicking a link, opening a page)
- **POST** → Sending/modifying data (submitting a form). POST includes CSRF token for security

### Route Parameters

- `{book}` → Required parameter. Laravel auto-finds the Book model by ID (called **Route Model Binding**)
- `{book?}` → Optional parameter. If not provided, `$book` is null (used for add vs edit: no ID = new book, with ID = edit existing)

### Middleware on Routes

```php
->middleware('auth:web')                  // Must be logged in
->middleware('role:admin,librarian')      // Must have admin OR librarian role
->middleware(['auth:web', 'role:admin'])  // Must be logged in AND must be admin
```

---

## Blade Views

**Blade** is Laravel's templating engine. It lets you write HTML with PHP logic using special `@` directives.

### Layout System

`master.blade.php` is the main skeleton:
```html
<html>
<head>
    <title>Library - @yield('title')</title>     ← Placeholder for page title
    <!-- Bootstrap CSS -->
</head>
<body>
    @include('layouts.menu')                     ← Inserts the menu.blade.php file here
    <div class="container">
        @yield('content')                        ← Placeholder for page content
    </div>
</body>
</html>
```

Every other page **extends** the master:
```html
@extends('layouts.master')                       ← Use master.blade.php as skeleton
@section('title', 'Books')                       ← Fill the title placeholder
@section('content')                              ← Fill the content placeholder
    <h1>Books List</h1>
    ...
@endsection
```

### Key Blade Directives Used

| Directive | What It Does | Example |
|-----------|-------------|---------|
| `@extends('layouts.master')` | Use master layout as the page skeleton | Every page has this |
| `@section('content')` / `@endsection` | Define content for a placeholder | Page body content |
| `@yield('content')` | Output the content from a section | In master.blade.php |
| `@include('layouts.menu')` | Insert another blade file | Menu bar |
| `{{ $variable }}` | Print a variable (auto-escaped for XSS safety) | `{{ $book->title }}` |
| `{{ csrf_field() }}` | Inserts hidden CSRF token in forms | Every `<form>` tag |
| `@foreach ... @endforeach` | Loop through a collection | Listing books, errors |
| `@if ... @else ... @endif` | Conditional logic | Show/hide buttons |
| `@auth ... @else ... @endauth` | Check if user is logged in | Menu: show Login vs Logout |
| `{{ route('name') }}` | Generate URL from route name | `href="{{ route('books_list') }}"` |
| `{{ old('field') }}` | Repopulate form field after validation error | `value="{{ old('email') }}"` |
| `{{ session('success') }}` | Get flash message from session | Success/error alerts |

### Role-based UI in menu.blade.php

```html
@auth                                              ← Only if logged in
    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'librarian')
        <a href="{{ route('members') }}">Members</a>         ← Only admin+librarian see this
    @endif
    @if(auth()->user()->role == 'admin')
        <a href="{{ route('create_librarian') }}">Create Librarian</a>  ← Only admin sees this
    @endif
@else                                              ← Not logged in
    <a href="{{ route('login') }}">Login</a>       ← Guest sees login/register
    <a href="{{ route('register') }}">Register</a>
@endauth
```

---

## Middleware

Middleware is code that runs **before** the controller. Think of it as a security guard at the door.

### How it works

```
Request → Middleware 1 (auth check) → Middleware 2 (role check) → Controller
              ↓ FAIL                        ↓ FAIL
         Redirect to login            Return 403 Forbidden
```

### RoleMiddleware.php

```php
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check 1: Is user logged in?
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check 2: Does user's role match any of the allowed roles?
        // Example: middleware('role:admin,librarian') → $roles = ['admin', 'librarian']
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Unauthorized');
        }

        // All checks passed → continue to the controller
        return $next($request);
    }
}
```

### Registering Middleware in Kernel.php

```php
protected $routeMiddleware = [
    'auth' => Authenticate::class,      // Built-in: checks if logged in
    'role' => RoleMiddleware::class,     // Custom: checks user role
    // ...
];
```

After registering, you can use `'role'` as a name in routes:
```php
Route::get('members', ...)->middleware('role:admin,librarian');
```

---

## Migrations

Migrations are PHP files that define the database table structure. They let you create tables without writing SQL manually.

### Users Table Migration

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();                                    // id BIGINT AUTO_INCREMENT PRIMARY KEY
    $table->string('name');                          // name VARCHAR(255)
    $table->string('email')->unique();               // email VARCHAR(255) UNIQUE
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');                      // password VARCHAR(255) - stores bcrypt hash
    $table->string('role')->default('member');        // role VARCHAR(255) DEFAULT 'member'
    $table->rememberToken();
    $table->timestamps();                            // created_at, updated_at TIMESTAMP
});
```

### Books Table Migration

```php
Schema::create('books', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('author');
    $table->string('isbn');
    $table->integer('copies')->default(0);           // How many copies are available
    $table->timestamps();
});
```

### Borrows Table Migration

```php
Schema::create('borrows', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    //     ↑ Creates user_id column as FOREIGN KEY referencing users(id)
    //       onDelete('cascade') = if user is deleted, their borrows are deleted too
    $table->foreignId('book_id')->constrained()->onDelete('cascade');
    $table->timestamp('borrowed_at')->useCurrent();
    $table->timestamps();
});
```

### Commands

```bash
php artisan migrate        # Creates all tables in the database
php artisan db:seed        # Runs DatabaseSeeder.php (creates admin user)
php artisan migrate:fresh  # Drops all tables and recreates them (CAREFUL: deletes all data)
```

---

## Security Features

### 1. Password Hashing with bcrypt

**Where:** `UsersController::doRegister()` and `storeLibrarian()`

```php
$user->password = bcrypt($request->password);
```

- `bcrypt()` converts "admin123" into something like `$2y$10$Xk3j...` (60 characters)
- It's a **one-way** function: you cannot reverse it back to "admin123"
- Even if someone steals the database, they can't read the passwords
- `Auth::attempt()` automatically uses `password_verify()` to compare during login

### 2. Generic Login Error Messages

**Where:** `UsersController::doLogin()`

```php
->withErrors(['login' => 'Invalid email or password']);
```

- NEVER say "Email not found" or "Wrong password" separately
- If you say "Email not found", an attacker knows that email doesn't exist
- If you say "Wrong password", an attacker knows the email DOES exist and can try brute-forcing the password
- Always use one generic message for both cases

### 3. CSRF Protection

**Where:** Every `<form>` in blade files

```html
<form action="..." method="post">
    {{ csrf_field() }}
    ...
</form>
```

- CSRF = Cross-Site Request Forgery
- Without it: A malicious website could create a hidden form that submits to YOUR site using the user's session
- `{{ csrf_field() }}` adds a hidden input with a random token
- Laravel checks this token on every POST request. If it doesn't match, the request is rejected (419 error)
- Laravel's `VerifyCsrfToken` middleware handles this automatically

### 4. Input Validation

**Where:** Every controller method that receives form data

```php
$this->validate($request, [
    'title' => ['required', 'string', 'max:255'],
    'copies' => ['required', 'integer', 'min:0'],
]);
```

- Prevents empty data, wrong data types, or excessively long strings
- `'required'` → field must be present
- `'string'` → must be text
- `'max:255'` → maximum 255 characters
- `'integer'` → must be a whole number
- `'min:0'` → minimum value is 0
- `'email'` → must be valid email format
- `'confirmed'` → must have a matching `fieldname_confirmation` field
- If validation fails, user is redirected back with error messages

### 5. Eloquent ORM (No Raw SQL)

**Where:** All database operations throughout the project

```php
// SECURE (Eloquent):
$books = Book::all();                           // SELECT * FROM books
$book->delete();                                // DELETE FROM books WHERE id = ?
$user = User::where('email', $email)->first();  // SELECT * FROM users WHERE email = ? LIMIT 1

// INSECURE (Raw SQL - we DO NOT use this):
DB::unprepared("DELETE FROM books WHERE id = $id");  // SQL Injection vulnerable!
```

- Eloquent automatically uses **prepared statements** with parameter binding
- This prevents SQL injection: even if someone puts `'; DROP TABLE users; --` in a form field, it's treated as a string, not SQL code

### 6. Authentication Middleware

**Where:** `routes/web.php` on protected routes

```php
->middleware('auth:web')
```

- Prevents unauthenticated users from accessing pages by typing the URL directly
- Without it: anyone could go to `/profile` or `/books/borrow/1` without logging in

### 7. Role-Based Authorization Middleware

**Where:** `routes/web.php` on admin/librarian routes

```php
->middleware('role:admin,librarian')
```

- Prevents users from accessing pages they shouldn't see
- Without it: a regular member could go to `/books/edit` or `/members` by typing the URL
- Returns 403 Forbidden if role doesn't match

### 8. Mass Assignment Protection

**Where:** Model `$fillable` arrays

```php
protected $fillable = ['title', 'author', 'isbn', 'copies'];
```

- Only these fields can be set via `$book->fill($request->all())`
- Prevents attackers from adding extra fields in the form (like adding `role=admin` to a registration form)

---

## Important Code Blocks

### The Borrow Logic (Most Critical for Exam)

**File:** `app/Http/Controllers/Web/BorrowController.php` → `borrow()` method

This single method handles requirements 9, 11, 12, 13:

```php
public function borrow(Request $request, Book $book)
{
    // Requirement #11: Only borrow if copies > 0
    if ($book->copies <= 0) {
        // Requirement #12: Show exact message
        return redirect()->route('books_list')
            ->with('error', 'Book Currently Unavailable');
    }

    // Requirement #13: Decrease copies
    $book->copies -= 1;
    $book->save();

    // Save borrow record for Requirement #14
    $borrow = new Borrow();
    $borrow->user_id = auth()->id();
    $borrow->book_id = $book->id;
    $borrow->borrowed_at = now();
    $borrow->save();

    return redirect()->route('books_list')
        ->with('success', 'Book borrowed successfully!');
}
```

### The Login Logic (Security Critical)

**File:** `app/Http/Controllers/Web/UsersController.php` → `doLogin()` method

```php
if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    return redirect()->back()
        ->withInput($request->only('email'))
        ->withErrors(['login' => 'Invalid email or password']);
}
```

- `Auth::attempt()` does TWO things: finds user by email AND checks bcrypt password
- Returns `false` for BOTH "email not found" and "wrong password"
- We give the SAME error message for both cases

### The Role Middleware (Authorization Critical)

**File:** `app/Http/Middleware/RoleMiddleware.php`

```php
public function handle(Request $request, Closure $next, ...$roles)
{
    if (!auth()->check()) return redirect()->route('login');
    if (!in_array(auth()->user()->role, $roles)) abort(403);
    return $next($request);
}
```

- `...$roles` collects all comma-separated role names: `middleware('role:admin,librarian')` → `$roles = ['admin', 'librarian']`
- `in_array()` checks if user's role is in the allowed list
- `abort(403)` stops execution and shows "Forbidden" page

### Default Role Assignment

**File:** `app/Http/Controllers/Web/UsersController.php` → `doRegister()` method

```php
$user->role = 'member';  // Every new registration gets 'member' role
```

---

## How Each Feature Works

### Registration Flow
1. User visits `/register` → sees form
2. Fills in name, email, password, confirm password
3. Submits form (POST) → `doRegister()` runs
4. Validation checks all fields
5. Checks if email already exists (generic error if so)
6. Creates user with bcrypt password and role = 'member'
7. Redirects to login page

### Login Flow
1. User visits `/login` → sees form
2. Enters email and password
3. Submits form (POST) → `doLogin()` runs
4. `Auth::attempt()` checks email + bcrypt password
5. If wrong → generic error "Invalid email or password"
6. If correct → sets session cookie → redirects to home

### Borrowing Flow
1. Member visits `/books` → sees book list
2. Clicks "Borrow" on a book → GET to `/books/borrow/5`
3. `auth:web` middleware checks: user logged in? Yes → continue
4. Controller checks: `copies > 0`? Yes → continue
5. Decreases copies by 1 in database
6. Creates new record in borrows table (user_id, book_id, now)
7. Redirects back to books list with success message

### Book Management Flow (Admin/Librarian)
1. Admin clicks "Add Book" → `/books/edit` → empty form
2. Fills in title, author, isbn, copies
3. Submits → `save()` validates all fields → creates new Book
4. To edit: clicks "Edit" → `/books/edit/5` → form pre-filled with book data
5. Submits → `save()` updates existing book
6. To delete: clicks "Delete" → confirm dialog → `/books/delete/5` → book removed

---

## Terminal Commands Reference

| Command | What It Does |
|---------|-------------|
| `php artisan serve` | Starts local development server at http://127.0.0.1:8000 |
| `php artisan migrate` | Creates all database tables from migration files |
| `php artisan migrate:fresh` | Drops ALL tables and recreates them (loses all data) |
| `php artisan db:seed` | Runs DatabaseSeeder.php to create initial data (admin user) |
| `php artisan migrate:fresh --seed` | Does both: fresh migration + seeding in one command |
| `php artisan route:list` | Shows all registered routes, their URLs, middleware, and controllers |
