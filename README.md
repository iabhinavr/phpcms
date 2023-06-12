This is the code repository for a PHP project published on YouTube.

View the video here: https://www.youtube.com/watch?v=-MlwoUL-9DE

It can be thought of as a lightweight content management system, which includes the ability to upload images, manage articles, authenticate users, and manage site settings like on a blogging platform.

## Important Features / Topics Covered

For an end-user, the application may appear quite basic, as it's just a simple blogging system. But when looked from a developer's perspective, you can easily recognize that it covers a lot of different topics, ranging from dependecy injection to PHP Security, and from JavaScript modules to styling with Bootstrap.

So, here are the top things that the project currently includes:

- upload images, generate thumbnails
- create articles (using EditorJS), edit, delete
- pagination for image library and blog articles pages
- multi-user with authentication and authorization (roles like admin & editor)
- frontend with FastRoute - index and single post pages
- dev. environment created with Docker (nginx, php-fpm, mariadb)
- styling using Bootstrap and some custom CSS
- Llght and dark modes for the admin interface
- form handling using JavaScript
- JavaScript code organized as modules, then bundled using Webpack
- SQL injection taken care of with PHP PDO prepared statements
- XSS taken care of with HTMLPurifier & htmlspecialchars
- CSRF tokens added to forms
- PHP Classes defined for the entities, like Image, Article, User, etc
- PHP-DI to manage class dependencies
- version control using Git

### Plans for the future

In addition to the above mentioned features, I also plan to include the following features to the project:

- Commenting system for blog posts
- Fine tuning the SEO features, with the ability to add custom title, meta description, schema markups, and XML sitemaps
- Accessibility features
- Automated testing using PHPUnit
- Performance optimization, like lazy loading and page caching
- Changeable templating system for the frontend


The project environment is created using Docker. Below you can find the instructions on how to setup the demo:

## Prerequisites

In order to setup the demo and start working with it on a local machine, you may need the following installed on your system:

- Docker
- PHP
- Composer
- MySQL Client
- Nodejs & NPM
- Any Database manager of your choice - DBeaver, PhpMyAdmin, etc.

## How to Setup the Demo

Once you have the above requirements in place, clone the repo to an appropriate location on your machine:

```sh
git clone https://github.com/iabhinavr/phpcms.git
```

Then run the PHP Composer command to install the dependencies:

```sh
composer --version
composer install
```

Following that, install the NPM packages:

```sh
npm --version
npm install
```

Now build the Docker containers and start them. It uses the configuration given in the docker-compose.yml file.

```sh
docker compose build
docker compose up -d
```

## Viewing the Project

The docker-compose.yml maps the ports 8084 (for localhost) and 33094 (for database). The localhost domain name used in the nginx.conf file is php-cms.local.

So, you should be able to access the project from: http://php-cms.local:8084/

To access the backend: http://php-cms.local:8084/admin/login.php

You may use either of the following credentials to login to the admin area:

```
Username: abhinavr
Password: JzTKpr4ytt2Tjm

Username: testuser
Password: L9fGszFGYPRh6b
```