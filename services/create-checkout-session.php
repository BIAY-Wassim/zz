<?php

require './vendor/autoload.php';
require './config/secret.php';

global $STRIPE_API_KEY, $STRIPE_PRODUCT_PRICE_ID;
// This is your test secret API key.
\Stripe\Stripe::setApiKey($STRIPE_API_KEY);

header('Content-Type: application/json');


$YOUR_DOMAIN = 'http://localhost:8080';

$checkout_session = \Stripe\Checkout\Session::create([
  'line_items' => [[
    # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
    'price' => $STRIPE_PRODUCT_PRICE_ID,
    'quantity' => 1,
  ]],
  'mode' => 'payment',
  'success_url' => $YOUR_DOMAIN . '/view/success.php?session_id={CHECKOUT_SESSION_ID}',
  'cancel_url' => $YOUR_DOMAIN . '/view/cancel.html?session_id={CHECKOUT_SESSION_ID}',
  'client_reference_id' => $_GET['username']
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);