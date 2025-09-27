<?php
// Start session manually at the very top
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simple admin check
if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login/login.php");
    exit();
}

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    header("Location: ../login/login.php?error=access_denied");
    exit();
}

// Include files with correct paths
require_once '../settings/db_cred.php';
require_once '../classes/db_class.php';

$db = new db_connection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Category Management - African Fashion Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .category-card {
            border-left: 4px solid #D19C97;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">
                <i class="fa fa-tags"></i> Category Management
            </span>
            <div>
                <a href="../index.php" class="btn btn-outline-light btn-sm">
                    <i class="fa fa-home"></i> Back to Site
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5><i class="fa fa-plus"></i> Add New Category</h5>
                    </div>
                    <div class="card-body">
                        <form id="category-form">
                            <div class="mb-3">
                                <label for="cat_name" class="form-label">Category Name *</label>
                                <input type="text" class="form-control" id="cat_name" name="cat_name" 
                                       required maxlength="100" placeholder="Enter category name">
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fa fa-plus"></i> Add Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5><i class="fa fa-list"></i> Existing Categories</h5>
                    </div>
                    <div class="card-body">
                        <div id="categories-list">
                            <?php
                            // Direct database test
                            if ($db->db_connect()) {
                                echo "<p style='color: green;'>✓ Database connected successfully</p>";
                                
                                $categories = $db->db_fetch_all("SELECT * FROM categories ORDER BY cat_name");
                                if ($categories && count($categories) > 0) {
                                    echo "<p>Found " . count($categories) . " categories:</p>";
                                    foreach ($categories as $category) {
                                        echo '<div class="card category-card mb-2">';
                                        echo '<div class="card-body">';
                                        echo '<h5>' . htmlspecialchars($category['cat_name']) . '</h5>';
                                        echo '<small class="text-muted">ID: ' . $category['cat_id'] . '</small>';
                                        echo '</div></div>';
                                    }
                                } else {
                                    echo '<div class="alert alert-info">No categories found. Add your first category!</div>';
                                    
                                    // Try to create a test category
                                    $test_sql = "INSERT INTO categories (cat_name) VALUES ('Test Category')";
                                    if ($db->db_write_query($test_sql)) {
                                        echo '<div class="alert alert-success">Test category added successfully! Refresh the page.</div>';
                                    } else {
                                        echo '<div class="alert alert-warning">Could not add test category. Table might not exist.</div>';
                                    }
                                }
                            } else {
                                echo '<div class="alert alert-danger">Database connection failed</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    $(document).ready(function() {
        $('#category-form').submit(function(e) {
            e.preventDefault();
            
            const catName = $('#cat_name').val().trim();
            
            if (!catName) {
                alert('Please enter a category name');
                return;
            }

            // Simple AJAX test
            $.ajax({
                url: '../actions/add_category_action.php',
                type: 'POST',
                data: { cat_name: catName },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Category added successfully!');
                        $('#cat_name').val('');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error adding category: ' + error);
                    console.error('AJAX Error:', error);
                }
            });
        });
    });
    </script>
</body>
</html>