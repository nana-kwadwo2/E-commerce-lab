<?php
header('Content-Type: application/json');
session_start();

$response = array();

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'Please login to delete categories';
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
if (!isset($_POST['cat_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'Category ID is required';
    echo json_encode($response);
    exit();
}

require_once '../controllers/category_controller.php';

$cat_id = intval($_POST['cat_id']);

$result = delete_category_ctr($cat_id);

if ($result['status'] === 'success') {
    $response['status'] = 'success';
    $response['message'] = $result['message'];
} else {
    $response['status'] = 'error';
    $response['message'] = $result['message'];
}

echo json_encode($response);
?>