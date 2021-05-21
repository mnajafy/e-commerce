# e-commerce

Le modèle e-commerce Symfony est une solution de commerce électronique ultime. Il est construit avec Symfony et Bootstrap. 

## Main Features:

* Symfony
* HTML, CSS and JS
* dart-sass
* Bootstrap-4.0.0
* Font Awesome-5.15.3

## Fonts:

* Poppins
* Prata

### images

* [Lorem Picsum](https://picsum.photos/)
* [Pixabay](https://pixabay.com/fr/)

## Symfony Command

### Project setup
```
$ yarn install
$ composer install
```

### Compiles and hot-reloads for development
```
$ symfony server:start
```

### Compiles and minifies for production

### Create Database
```
$ php bin/console --env=test doctrine:schema:create 
```

### Migration
```
$ php bin/console make:migration --env=test    
```

### Create user
```
$ php bin/console app:create-user --env=test admin admin@e-commerce.com Admin_1234 ROLE_ADMIN
```

### List users
```
$ php bin/console app:list-users --env=test
 ---- ---------- ----------------------- ----------------------- 
  ID   Username   Email                   Roles
 ---- ---------- ----------------------- -----------------------
  11   admin2     admin2@e-commerce.com   ROLE_ADMIN, ROLE_USER
  5    admin      admin@e-commerce.com    ROLE_ADMIN, ROLE_USER
 ---- ---------- ----------------------- -----------------------
```

### Delete user
```
$ php bin/console app:delete-user admin2 --env=test
```

## Symfony Tests

### Test Entity
```
$ php ./vendor/bin/phpunit Tests/Entity/UserEntityTest.php --testdox 
PHPUnit 9.5.4 by Sebastian Bergmann and contributors.

User Entity (App\Tests\Entity\UserEntity)
 ✔ Username is empty
 ✔ Username min length
 ✔ Username max length
 ✔ Username invalid cracther
 ✔ Username unique
 ✔ Email is empty
 ✔ Email is invalid
 ✔ Email is unique entity
 ✔ Password is empty
 ✔ Password min length
 ✔ Password max length
 ✔ Password invalid caracther
 ✔ User entity is valid

Time: 00:00.635, Memory: 20.00 MB

OK (13 tests, 13 assertions)
```

## Template

### View:

* Home

![e-commerce Home](https://github.com/mnajafy/e-commerce/blob/master/e-commerce.jpeg)