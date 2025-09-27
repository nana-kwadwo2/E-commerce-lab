<?php
// Require login for this page
require_once 'settings/core.php';
require_login();

$user = get_logged_in_user(); // Changed from get_current_user()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - E-Commerce Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>My Profile</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['customer_name']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['customer_email']); ?></p>
                        <p><strong>Role:</strong> <?php echo $user['user_role'] == 1 ? 'Administrator' : 'Customer'; ?></p>
                        <p><strong>User ID:</strong> <?php echo $user['customer_id']; ?></p>
                        
                        <?php if (is_admin()): ?>
                            <div class="alert alert-warning mt-3">
                                <i class="fa fa-shield-alt"></i> You have administrator privileges.
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-3">
                            <a href="index.php" class="btn btn-secondary">Back to Home</a>
                            <?php if (is_admin()): ?>
                                <a href="admin/dashboard.php" class="btn btn-danger">Admin Dashboard</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>