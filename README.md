Minecraft Project

# Installation

### Install the dependencies
Stripe API
```sh
$ composer install
```

### Configuration of Stripe
First of all create Stripe account<br>
Create secret.php file in config folder and add and edit with your datas configuration:
```php
<?php

$STRIPE_API_KEY = 'YOUR_STRIPE_API_KEY';
$STRIPE_PRODUCT_PRICE_ID = 'YOUR_STRIPE_PRODUCT_PRICE_ID';

$db_host = "localhost:8889";
$db_username = "root";
$db_password = "root";
$db_name = "minecraft_lst";

?>
```

### Create the database
Execute assets/sql/CREATE.sql file in PHPMYADMIN

### Fake CB Stripe
https://stripe.com/docs/testing
- visa: 4242424242424242
- visa: 4000056655665556
- Mastercard: 5555555555554444






