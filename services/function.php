<?php

require './vendor/autoload.php';
require './config/secret.php';

/**
 * Check Stripe payment status
 */
function checkSession($session_id)
{
  global $STRIPE_API_KEY;
  $stripe = new \Stripe\StripeClient($STRIPE_API_KEY);

  if (!empty($session_id)) {

    try {
      $msg['data'] = $stripe->checkout->sessions->retrieve(
        $session_id,
        []
      );

      $msg['message'] = 'Status subscription';
      $msg['http'] = 200;
      $msg['status'] = 1;
    } catch (\Throwable $th) {
      $msg['data'] = null;
      $msg['message'] = 'Subscription failed';
      $msg['http'] = 400;
      $msg['status'] = -1;
    }
  } else {
    $msg['data'] = null;
    $msg['message'] = 'Subscription failed';
    $msg['http'] = 400;
    $msg['status'] = -2;
  }

  return json_encode($msg);
}

/**
 * Connection to the DATABASE
 */
function dbConnection()
{
  global $db_host, $db_username, $db_password, $db_name;
  try {
    $conn = new PDO(
      'mysql:host=' . $db_host . ';dbname=' . $db_name,
      $db_username,
      $db_password,
      array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET lc_time_names='fr_FR',NAMES utf8")
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES utf8mb4");
    return $conn;
  } catch (PDOException $e) {
    echo "Connection error " . $e->getMessage();
    exit;
  }
}

/**
 * Insert user to database if payment is successful
 */
function saveUserToDB($session_status)
{
  global $list_id;
  $session_status = json_decode($session_status, true);

  $stripe_id = $session_status['data']['id'];
  $payment_intent = $session_status['data']['payment_intent'];
  $payment_status = $session_status['data']['payment_status'];
  $email = $session_status['data']['customer_details']['email'];
  $username = $session_status['data']['client_reference_id'];

  $conn = dbConnection();
  if ($conn) {

    if (alreadyExist($session_status, $conn)) {
      return;
    }

    $insert_participant = "INSERT INTO `participant` (list_id, username, `email`, stripe_id, payment_intent, payment_status)
    VALUES (:list_id, :username, :email, :stripe_id, :payment_intent, :payment_status)";

    $stmt = $conn->prepare($insert_participant);
    $stmt->execute([
      ':list_id' => $list_id,
      ':username' => $username,
      ':email' => $email,
      ':stripe_id' => $stripe_id,
      ':payment_intent' => $payment_intent,
      ':payment_status' => $payment_status
    ]);
  }
}

/**
 * Check if a payment already exist or not
 */
function alreadyExist($session_status, $conn)
{
  $payment_exist = false;
  $find_one = "SELECT * FROM participant WHERE payment_intent = :payment_intent";
  $stmt = $conn->prepare($find_one);
  $payment_intent = $session_status['data']['payment_intent'];
  $stmt->execute([':payment_intent' => $payment_intent]);

  $val = $stmt->fetch();
  if ($val) {
    $payment_exist = true;
  }
  return $payment_exist;
}

function getUsersList($list_id)
{
  $conn = dbConnection();
  $user_array = [];
  $getUsersQuery = 'SELECT * FROM participant WHERE list_id = :list_id';

  if ($conn) {
    $stmt = $conn->prepare($getUsersQuery);
    $stmt->execute([':list_id' => $list_id]);

    if ($stmt->rowCount() > 0) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($user_array, $row);
      }
    }
  }

  return $user_array;
}
