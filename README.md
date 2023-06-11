This is the code repository for a PHP project published as a series of video tutorials on YouTube. It can be thought of as a lightweight content management system, which includes the ability to upload images, manage articles, authenticate users, and manage site settings like on a blogging platform.

The project environment is created using Docker. You can find the instructions below:

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