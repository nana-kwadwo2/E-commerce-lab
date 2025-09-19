<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../controllers/customer_controller.php';
error_log("POST data: " . print_r($_POST, true)); // Log POST data for debugging
list($ok, $msg) = register_customer_ctr($_POST);
$response = $ok ? ['status' => 'success', 'message' => 'Registered'] : ['status' => 'error', 'message' => $msg];
error_log("Response: " . print_r($response, true)); // Log response for debugging
echo json_encode($response);
?>