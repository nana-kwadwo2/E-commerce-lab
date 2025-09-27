<?php
// Start session first
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$response = array();

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'Please login to add categories';
    echo json_encode($response);
    exit();
}

// Include files with correct paths
require_once '../controllers/category_controller.php';


$response = array();

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'Please login to add categories';
    echo json_encode($response);
    exit();
}

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit();
}

// Validate required fields
if (!isset($_POST['cat_name']) || empty(trim($_POST['cat_name']))) {
    $response['status'] = 'error';
    $response['message'] = 'Category name is required';
    echo json_encode($response);
    exit();
}

require_once '../controllers/category_controller.php';

$cat_name = trim($_POST['cat_name']);
$created_by = $_SESSION['customer_id'];

// Validate category name length
if (strlen($cat_name) > 100) {
    $response['status'] = 'error';
    $response['message'] = 'Category name must be 100 characters or less';
    echo json_encode($response);
    exit();
}

$result = add_category_ctr($cat_name, $created_by);

if ($result['status'] === 'success') {
    $response['status'] = 'success';
    $response['message'] = 'Category added successfully!';
    $response['category_id'] = $result['category_id'];
} else {
    $response['status'] = 'error';
    $response['message'] = $result['message'];
}

echo json_encode($response);
?>