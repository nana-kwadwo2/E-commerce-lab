<?php
header('Content-Type: application/json');
session_start();

$response = array();

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    $response['status'] = 'error';
    $response['message'] = 'Please login to access categories';
    echo json_encode($response);
    exit();
}

require_once '../controllers/category_controller.php';

$result = get_categories_ctr(); // Get all categories

echo json_encode($result);
?>