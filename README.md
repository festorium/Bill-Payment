# Bill-Payment

This is a RESTful API for user authentication and transaction management built using Laravel. It includes features for registering, logging in, verifying email addresses, and managing transactions. The API is protected using JWT authentication.

# Features
User Authentication (Register, Login, Email Verification)
Transaction Management (Create, Read, Update, Delete)
JWT Authentication for secure access to protected routes

# Requirements
PHP >= 8.0
Composer
MySQL or any other supported database
Laravel 9.x
Mailtrap (or any SMTP email service for testing)

# Setup Instructions
1. Clone the repository -- git clone https://github.com/festorium/bill-payment.git
cd bill-payment

2. Install dependencies -- composer install

3. Configure environment variables
Copy the .env.example file and rename it to .env: -- cp .env.example .env
Update the .env file with your database credentials and mail configuration:
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    
    # Mail Configuration for Email Verification
    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=your_mailtrap_username
    MAIL_PASSWORD=your_mailtrap_password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=noreply@yourapp.com
    MAIL_FROM_NAME="${APP_NAME}"

4. Generate application key
Run the following command to generate an application key: -- php artisan key:generate

5. Run migrations
Run the migrations to create the necessary tables in the database: -- php artisan migrate

6. Set up JWT Authentication
Generate the JWT secret key: --php artisan jwt:secret

7. Start the application
Serve the application locally: --php artisan serve
You should now be able to access the API at http://localhost:8000.

8. Mail Setup for Email Verification
Make sure your mail configuration is set up in .env. You can use Mailtrap for local development to test email functionality. Set the credentials in the .env file as shown in the Configure Environment Variables section.

# API Endpoints
Authentication
POST /register – Register a new user
POST /login – Log in to the system
POST /verify-user – Verify email using the verification code

Transaction Management (Protected Routes)
POST /transactions – Create a new transaction
GET /transactions/{id} – Get details of a specific transaction
PUT /transactions/{id} – Update a transaction
DELETE /transactions/{id} – Delete a transaction

Token Management (Protected Routes)
GET /refresh-token – Refresh the JWT token
GET /logout – Log out the user

# Running Unit Tests
The API includes unit tests to ensure the code works as expected. To run the tests, follow these steps:

Ensure you have set up your testing environment in the .env.testing file: -- cp .env .env.testing
Run the PHPUnit tests: --php artisan test
This will run all the unit tests in the tests directory.

# Notes
Ensure that your mail server is set up correctly for sending verification emails.
Use Postman or any other API client to test the API endpoints.
Transactions can only be accessed when logged in, as they are protected routes using JWT.
