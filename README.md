# my-fin

A personal finance management application built with **Symfony** and **PHP**.

## Tech Stack

* PHP 8.2+
* Symfony 7.4
* Doctrine ORM
* PostgreSQL
* Twig
* Symfony UX Turbo
* Sylius UI components
* PHPUnit
* Docker / Docker Compose

## Requirements

Before getting started, make sure you have:

* PHP >= 8.2
* Composer
* Docker and Docker Compose
* Git

## Installation

Clone the repository:

```bash
git clone https://github.com/Pigsas/my-fin.git
cd my-fin
```

Install PHP dependencies:

```bash
composer install
```

Create your local environment configuration:

```bash
cp .env .env.local
```

Review `.env.local` and configure your database and other environment variables as needed.

## Running the Application

Start the Symfony development server:

```bash
php bin/console server:start
```

Alternatively, use Symfony CLI if it is installed:

```bash
symfony server:start
```

The application will be available at:

```text
http://127.0.0.1:8000
```

## Xdebug

The project includes a script for enabling Xdebug:

```bash
./scripts/backend.sh /enable_xdebug.sh
```

For PhpStorm, configure the PHP server with:

* **Name:** `php.symfony`
* **Host:** `127.0.0.1`
* **Port:** `8000`
* **Debugger:** `Xdebug`
* **Path mapping:** project directory → `/code`

## Environment

Do not commit sensitive credentials or production secrets.

Use local environment files such as:

```text
.env.local
.env.dev
.env.test
```

for environment-specific configuration.

## License

This project is licensed under the **MIT License**.

See the [LICENSE](LICENSE) file for the complete license text.

## Contributing

Contributions, bug reports, and improvements are welcome.

1. Fork the repository.
2. Create a feature branch:

```bash
git checkout -b feature/my-feature
```

3. Make your changes.
4. Run the tests:

```bash
php bin/phpunit
```

5. Commit your changes:

```bash
git commit -m "Add my feature"
```

6. Push your branch:

```bash
git push origin feature/my-feature
```

7. Open a pull request.

---

Made with PHP and Symfony.

