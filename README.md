# newspeek-baudrate

This repo hold stuff and code for test project (Symfony 6.4)

## Table of Contents

- [newspeek-baudrate](#newspeek-baudrate)
  - [Table of Contents](#table-of-contents)
  - [Quick FAQ](#quick-faq)
  - [Deployment](#deployment)
    - [Prerequisites](#prerequisites)
    - [Dependencies](#dependencies)
      - [Nginx](#nginx)
      - [MariaDB](#mariadb)
      - [Web Server](#web-server)
        - [Prepare web files](#prepare-web-files)
        - [Config Nginx and PHP-FPM](#config-nginx-and-php-fpm)
      - [PHP](#php)
      - [Symfony](#symfony)
    - [VSCode Extensions](#vscode-extensions)
    - [Secure Backup of .env Files](#secure-backup-of-env-files)
    - [Config SFTP Extention](#config-sftp-extention)

## Quick FAQ

> Quick answers to the questions you may have

1. - Q: Why newspeek-baudrate?
   - A: It's a play on words that combines "newspeak" from Nineteen Eighty-Four with baudrate, which is a measure of data transmission speed. It's a silly joke about controlling baudrate as a form of thought control, which popped into my head while thinking of the repository's name 🤷🏻‍♀️

2. - Q: What stack is used?
   - A: LEMP: Linux, Nginx, MySQL(MariaDB), PHP8.1

3. - Q: Technical assignment link.
   - A: [Here](docs/development/task_symfony.md)

4. - Q: Project a based on a template?
   - A: Particularly, the git repository itself is essentially a template, with everything from the `docs` folder, the `README.md`, and its recipes, `.editorconfig`, `.vscode` settings, and other QoL improvements all stemming from the my template. _However_, the Symfony project itself was built from scratch, not based on any template

5. - Q: Why not SSH authentication for test server?
   - A: I'm not feelin' it today. Too lazy for testing instance 🤷🏻‍♀️

6. - Q: Why not Docker?
   - A: The first reason is that the test server is cheap and has too few resources to "pull" a Docker in there as well. Second reason: For this test case I think that using Docker is overkill

7. - Q: Where is the test server?
   - A: [shynekomaid.space](https://shynekomaid.space)

8. - Q: Where is database structure?
   - A: [docs/development/database.md](docs/development/database.md)

9. - Q: How to work with API?
   - A: I [use Bruno](https://www.usebruno.com) to work with API. Bruno collection can be found in bruno folder in this repository

10. - Q: Answer to test assignment additional task?
    - A: I believe this refers to deviceId, and it seems to me that this field has more to do with addresses than users. Depending on how the system is set up, I would do the following:
    - If addresses and devices are a one-to-many relationship (where multiple devices can be associated with a single address), I would add the address_id field to the device entity (or table) to establish a relationship with the address.
    - If addresses and devices represent a many-to-many relationship (where many devices can be associated with one address, but the same devices can be associated with multiple addresses), I would create a DevicesAddresses join table with device_id and address_id fields to manage the associations.
    - Also, I find it strange to represent services as an object. I would prefer to implement it as an array of objects for better structure and flexibility, as the service can have new fields

## Deployment

Follow these steps to deploy your project:

### Prerequisites

> Work from root user is bad practice
>
> Note:
> `shyneko` is my username, replace it with your own

Create user:

```bash
sudo adduser shyneko
```

Add sudo:

```bash
apt install sudo
sudo adduser shyneko sudo
```

> SSH key authentication is skipped for this project - we will use password authentication for test server for test deploy

### Dependencies

> As server is used Ubuntu 22.04 LTS

#### Nginx

Install Nginx:

```bash
sudo apt install nginx
sudo apt install certbot # for let's encrypt
```

#### MariaDB

> Replace `db_user` and `db_password` with your own

```bash
sudo apt update
sudo apt install mariadb-server
sudo systemctl start mariadb
sudo systemctl enable mariadb
```

```bash
sudo mysql_secure_installation
mysql -u root -p
```

```SQL
CREATE DATABASE newspeek_baudrate;
CREATE USER 'db_user'@'localhost' IDENTIFIED BY 'db_password';
GRANT ALL PRIVILEGES ON newspeek_baudrate.* TO 'db_user'@'localhost';
FLUSH PRIVILEGES;
```

#### Web Server

##### Prepare web files

> [Info](https://symfony.com/doc/current/setup/web_server_configuration.html)

Create folder to store web files:

```bash
sudo mkdir /var/www
sudo mkdir /var/www/newspeek-baudrate
```

Ownering files:

> Used facl to ownering files for easy deploy by user `shyneko` and chown to user `www-data`. Replace `shyneko` with your own username

```bash
sudo chown -R www-data:www-data /var/www
```

```bash
sudo setfacl -R -d -m u:shyneko:rwx /var/www/newspeek-baudrate
```

> Test facl and ownering:

```bash
getfacl /var/www/newspeek-baudrate
ls -ld /var/www/newspeek-baudrate
```

##### Config Nginx and PHP-FPM

> If in plan to work with symfony without nginx - this command will start local server:
>
> ```bash
> symfony server:start
> ```
>
> And you can skip Nginx and PHP-FPM configuration

Configuring PHP-FPM:

```ini
; /etc/php/8.1/fpm/pool.d/www.conf

; a pool called www
[www]
user = www-data
group = www-data

; use a unix domain socket
listen = /var/run/php/php8.1-fpm.sock

; or listen on a TCP connection
; listen = 127.0.0.1:9000
```

Nginx config:

> I use NGINXconfig by [DigitalOcean](https://github.com/digitalocean/nginxconfig.io) to bootstrap NGINX configuration with SSL
>
> Don't forget use `sudo su` before run install commands from NGINXconfig

Link with my NGINX config - [here](https://www.digitalocean.com/community/tools/nginx?domains.0.server.domain=shynekomaid.space&domains.0.server.path=%2Fvar%2Fwww%2Fnewspeek-baudrate&domains.0.php.phpServer=%2Fvar%2Frun%2Fphp%2Fphp8.1-fpm.sock&global.nginx.clientMaxBodySize=1024&global.nginx.typesHashMaxSize=4096&global.nginx.typesHashBucketSize=2048)

If error while installing nginx config maybe you need increase server_names_hash_bucket_size - add it to nginx.conf like: `server_names_hash_bucket_size 2048`;

#### PHP

Add the PHP repository (for PHP 8.1):

> Try install without adding repository
> Maybe PHP 8.1 is already in your distributive default sources

```bash
sudo add-apt-repository ppa:ondrej/php
sudo apt update
```

Install PHP 8.1:

> This setup will install the latest versions of musthave PHP extensions

```bash
sudo apt update
sudo apt install php8.1 php8.1-cli php8.1-common php8.1-ctype php8.1-iconv php8.1-pcre php8.1-session php8.1-simplexml php8.1-tokenizer
```

Install Composer:

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer # To use composer command instead of php composer.phar
```

#### Symfony

Install Symfony CLI:

```bash
curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | sudo -E bash
sudo apt install symfony-cli
```

Check Symfony installation:

```bash
symfony check:requirements
```

If in plan to work with symfony without nginx - this command will start local server:

```bash
symfony server:start
```

Create project:

```bash
composer create-project symfony/skeleton newspeek-baudrate "6.4.\*"
```

Install Symfony requirements:

```bash
composer require symfony/orm-pack
composer require doctrine/doctrine-migrations-bundle
composer require symfony/maker-bundle --dev
```

Check Database working:

> Add `"DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/newspeek_baudrate"` to symfony .env file

```bash
php bin/console doctrine:query:sql "SELECT 1"
```

Check Symfony working:

Open `shynekomaid.space` or `127.0.0.1` in browser

If Any error - rewamp chown

```bash
sudo chown -R www-data:www-data /var/www/newspeek-baudrate
```

Create entities using `symfony make:entity`:

> Replace `User` with your entity

```bash
php bin/console make:entity User
```

And create according to [docs/development/database.md](docs/development/database.md)

After adding entity you can fullfill it manualy, or via wizard

Migration notes:

> After entity created you can make migrations:

```bash
php bin/console make:migration
```

> Apply migrations:

```bash
php bin/console doctrine:migrations:migrate
```

> Check SQL dump that performed migrations:

```bash
php bin/console doctrine:migrations:diff
```

> Rollback migrations:

```bash
php bin/console doctrine:migrations:rollback
```

Create controller:

```bash
php bin/console make:controller UserController
```

### VSCode Extensions

> I recommend to use VSCode for development
> If you use vscode you will be see recommended extensions (by .vscode/extensions.json)
> Alternatively, you can install extensions manually by links below

- [Markdown All in One](https://marketplace.visualstudio.com/items?itemName=yzhang.markdown-all-in-one) - for markdown and auto TOC
- [Gitmoji](https://marketplace.visualstudio.com/items?itemName=seatonjiang.gitmoji-vscode) - for gitmoji commits made easy
- [SFTP](https://marketplace.visualstudio.com/items?itemName=Natizyskunk.sftp) - for easy deploy to server
- [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client) - advanced PHP language support (see [docs](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client#quick-start) Quick Start)
- [Todo Tree](https://marketplace.visualstudio.com/items?itemName=Gruntfuggly.todo-tree) - for easy TODO list management (use a bootstrap staged version, not from git, because git version has a lot of TODOs from bootstrap developers)
- [Formatting Toggle](https://marketplace.visualstudio.com/items?itemName=tombonnike.vscode-status-bar-format-toggle) - for easy toggle formatter in VSCode (use when editing Bootstrap files)
- [EditorConfig for VS Code](https://marketplace.visualstudio.com/items?itemName=EditorConfig.EditorConfig) - for easy editorconfig support (see `.editorconfig` file)

### Secure Backup of .env Files

Read this [file](/docs/development/secured_env.md) for more info.

### Config SFTP Extention

Read this [file](/docs/development/sftp.md) for more info.
