# IT0049 Midterm POS System

A Point-of-Sale web application developed using CodeIgniter 4 and MySQL. The system provides product, customer, staff, inventory, authentication, and sales-management features.

## Developers

- Charles Miguel Martin
- Lei E. Caabay

## Features

- Secure login and logout
- Protected management pages
- Product management
  - Add products
  - Edit products
  - Upload product images
  - Archive products
- Customer management
  - Add customers
  - Edit customers
  - Delete customers
- Staff and user management
  - Add users
  - Edit users
  - Upload user avatars
  - Delete users
  - Secure password hashing
- Record sales
- Support for registered and walk-in customers
- Automatic total-price calculation
- Automatic stock deduction
- Prevention of sales that exceed available stock
- Sales history showing:
  - Product
  - Customer
  - Staff member
  - Quantity
  - Total price
  - Transaction date

## Technologies Used

- PHP 8
- CodeIgniter 4
- MySQL
- HTML
- CSS
- XAMPP
- Composer

## Main Routes

- `/` – Home page
- `/about` – About page
- `/login` – Login page
- `/products` – Product management
- `/customers` – Customer management
- `/users` – Staff and user management
- `/sales/new` – Record a sale
- `/sales` – Sales history
- `/logout` – Logout

## Local Installation

1. Install XAMPP, PHP, Composer, and Git.
2. Place the project inside the XAMPP `htdocs` folder.
3. Start Apache and MySQL in XAMPP.
4. Open phpMyAdmin.
5. Create a database named:

   ```text
   it0049_midterm_pos
   ```

6. Import the database file:

   ```text
   database/it0049_midterm_pos.sql
   ```

7. Open a terminal inside the project folder.
8. Install the required dependencies:

   ```bash
   composer install
   ```

9. Copy the `env` file and rename the copy to `.env`.
10. Configure the database connection inside `.env`:

   ```ini
   CI_ENVIRONMENT = development

   database.default.hostname = localhost
   database.default.database = it0049_midterm_pos
   database.default.username = root
   database.default.password =
   database.default.DBDriver = MySQLi
   database.default.DBPrefix =
   database.default.port = 3306
   ```

11. Start the application:

   ```bash
   php spark serve
   ```

12. Open the application in a browser:

   ```text
   http://localhost:8080
   ```

## Security Features

- Passwords are stored using secure password hashing.
- Management pages are protected by an authentication filter.
- Forms use CSRF protection.
- User input is validated before being saved.
- Uploaded images are checked for valid file type and size.
- Uploaded images use randomly generated filenames.
- Database operations use CodeIgniter Models and Query Builder.
- Transactions that exceed available stock are rejected.

## Database Tables

- `products`
- `customers`
- `users`
- `sales`

## Repository Contents

The repository contains the complete CodeIgniter application, source code, database export, upload directories, and setup documentation.

## Important Notice

The `.env` file is excluded from the repository because it may contain private database credentials. Database settings must be configured separately when installing or deploying the application.