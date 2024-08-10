# About Bookshelf

-----

Bookshelf is a new web application for fast access all people to all books

- Development Tools : Laravel framework

## Features

-----

- users is access to add book in website
- access download The desired book
- Introduction best books in year
- We will edit soon ♻️

## Install

-----

To install, you need to do the following steps in the project's command line or terminal

### step 1: clone bookshelf

```sh
git clone https://github.com/mk990/bookshelf.git
```

### step 2: install packages

```sh
composer install
cp .env.example .env 
php artisan sqlite:generate
php artisan key:generate
php artisan jwt:secret
php artisan migrate 
```

### Run project

```sh
php artisan serve
```

well , you can open the `http://127.0.0.1:8000` and watch bookshelf

-----

If you have done the above steps and have a problem, send a message.

**If you think this project is helpful to you, you may wish to give a** 🌟

**Free for All :** ❤️

### developed by

- **[mohammad hemmati](https://github.com/mk990)**
- **[Emad shirzad](https://github.com/Emadshirzad)**
- **[Mehdi Abedi](https://github.com/mehdiabedimehr)**
