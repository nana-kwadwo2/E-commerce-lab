<?php
// Require admin privileges
require_once '../settings/core.php';
require_admin(); // This will redirect if not admin

// Page content for admins only
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - E-Commerce Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .admin-nav {
            background: #343a40;
            padding: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            border-left: 4px solid #D19C97;
        }
    </style>
</head>
<body>
    <nav class="admin-nav">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="text-white mb-0">
                    <i class="fa fa-cog"></i> Admin Dashboard
                </h4>
                <div>
                    <a href="../index.php" class="btn btn-outline-light btn-sm me-2">
                        <i class="fa fa-home"></i> Back to Site
                    </a>
                    <a href="../logout.php" class="btn btn-danger btn-sm">
                        <i class="fa fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h6>Admin Menu</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fa fa-users me-2"></i>User Management
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fa fa-box me-2"></i>Products
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fa fa-shopping-cart me-2"></i>Orders
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fa fa-chart-bar me-2"></i>Analytics
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <h5><i class="fa fa-users text-primary"></i> Users</h5>
                                <h3>1,234</h3>
                                <small class="text-muted">Total registered users</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <h5><i class="fa fa-box text-success"></i> Products</h5>
                                <h3>567</h3>
                                <small class="text-muted">Active products</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <h5><i class="fa fa-shopping-cart text-warning"></i> Orders</h5>
                                <h3>89</h3>
                                <small class="text-muted">Pending orders</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h6>Welcome, Administrator!</h6>
                    </div>
                    <div class="card-body">
                        <p>You have successfully accessed the admin dashboard with elevated privileges.</p>
                        <p><strong>Session Information:</strong></p>
                        <ul>
                            <li>User ID: <?php echo get_user_id(); ?></li>
                            <li>User Role: <?php echo get_user_role(); ?> (<?php echo is_admin() ? 'Administrator' : 'Customer'; ?>)</li>
                            <li>Login Status: <?php echo check_login() ? 'Logged In' : 'Logged Out'; ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>