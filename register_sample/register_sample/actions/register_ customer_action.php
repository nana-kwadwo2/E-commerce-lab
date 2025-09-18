<?php

header('Content-Type: application/json');
session_start();

$response = array();

// Check if the user is already logged in
if (isset($_SESSION['customer_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'You are already logged in';
    echo json_encode($response);
    exit();
}

// Check if all required fields are present
if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['password']) || 
    !isset($_POST['country']) || !isset($_POST['city']) || !isset($_POST['contact']) || 
    !isset($_POST['role'])) {
    $response['status'] = 'error';
    $response['message'] = 'All fields are required';
    echo json_encode($response);
    exit();
}

require_once '../controllers/customer_controller.php';

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$country = trim($_POST['country']);
$city = trim($_POST['city']);
$contact = trim($_POST['contact']);
$role = intval($_POST['role']);

// Basic validation
if (empty($name) || empty($email) || empty($password) || empty($country) || empty($city) || empty($contact)) {
    $response['status'] = 'error';
    $response['message'] = 'All fields are required';
    echo json_encode($response);
    exit();
}

// Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['status'] = 'error';
    $response['message'] = 'Invalid email format';
    echo json_encode($response);
    exit();
}

// Password validation
if (strlen($password) < 6) {
    $response['status'] = 'error';
    $response['message'] = 'Password must be at least 6 characters long';
    echo json_encode($response);
    exit();
}

// Contact validation (basic)
if (!preg_match('/^[0-9+\-\s()]+$/', $contact)) {
    $response['status'] = 'error';
    $response['message'] = 'Invalid contact number format';
    echo json_encode($response);
    exit();
}

// Check if email already exists
if (get_customer_by_email_ctr($email)) {
    $response['status'] = 'error';
    $response['message'] = 'Email already exists';
    echo json_encode($response);
    exit();
}

// Register the customer
$customer_id = register_customer_ctr($name, $email, $password, $country, $city, $contact, $role);

if ($customer_id) {
    $response['status'] = 'success';
    $response['message'] = 'Registration successful';
    $response['customer_id'] = $customer_id;
} else {
    $response['status'] = 'error';
    $response['message'] = 'Registration failed. Please try again.';
}

echo json_encode($response);