## How to use

# Local
- Clone the repository with `git clone <Repo Link>`
- Copy `.env.example` file to `.env` and edit database credentials there
- Run `composer install`
- Run `php artisan key:generate`
- Go to the `http://127.0.0.1:8000/api/documentation` url to check api documentation.
- Update `FRONTEND_URL= http://localhost:3000` into `.env` file to redirect email verification.
- Update `L5_SWAGGER_CONST_HOST=http://127.0.0.1:8000` into `.env` for swagger api server.
- Run `php artisan l5-swagger:generate` to generate swagger documentation

# Production
- Clone the repository with `git clone <Repo Link>`
- Copy `.env.example` file to `.env` and update with the prod credentials
- Run `composer install --no-dev --optimize-autoloader` 
- Run `composer dump-autoload`
- Run `php artisan key:generate`
- Go to the `<Prod_URL>/api/documentation` url to check api documentation.
- Update `FRONTEND_URL= <Prod_URL>` into `.env` file to redirect email verification.
- Update `L5_SWAGGER_CONST_HOST=<Prod_URL>` into `.env` for swagger api server.
- Run `php artisan l5-swagger:generate` to generate swagger documentation

# Docker setup

-Docker environment is set up. A developer can clone the repository and run the application using Docker commands.