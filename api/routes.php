<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Accept, X-Requested-With");
date_default_timezone_set("Asia/Manila");
set_time_limit(1000);

require_once("./config/Connection.php");
require_once("./modules/get.php");
require_once("./modules/post.php");
require_once("./modules/auth.php");

function encryptResponse($data) {
  global $auth;
  return $auth->encryptData($data);
}

function decryptRequest() {
  global $auth;
  $rawData = file_get_contents("php://input");
  $decodedData = json_decode(base64_decode($rawData), true);

  if (!isset($decodedData['data']) || !isset($decodedData['iv'])) {
      return null;
  }

  $encryptedData = base64_decode($decodedData['data']);
  $iv = base64_decode($decodedData['iv']);

  $decrypted = openssl_decrypt(
      $encryptedData,
      'AES-256-CBC',
      $_ENV['ENCRYPTION_KEY'],
      OPENSSL_RAW_DATA,
      $iv
  );

  return json_decode($decrypted, true);
}

// Admin route to get all users
if ($_SERVER['REQUEST_METHOD'] === 'GET' && strpos($_SERVER['REQUEST_URI'], '/admin/users') === 0) {
    // Check if user is admin
    if ($auth->isAdmin()) { // You need to implement this method
        $users = handleGet('/users'); // Reuse existing get handler
        echo json_encode($users);
    } else {
        header('HTTP/1.1 403 Forbidden');
        echo json_encode(['error' => 'Unauthorized']);
    }
}