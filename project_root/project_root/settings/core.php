<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// For header redirection
ob_start();

/**
 * Check if a user is logged in by verifying if a session has been created
 * @return bool True if user is logged in, false otherwise
 */
function check_login() {
    return isset($_SESSION['customer_id']);
}

/**
 * Check if user has administrative privileges
 * @return bool True if user has admin role (1), false otherwise
 */
function check_admin_privileges() {
    return (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1);
}

/**
 * Get the current user's ID
 * @return int|null User ID if logged in, null otherwise
 */
function get_user_id() {
    return isset($_SESSION['customer_id']) ? $_SESSION['customer_id'] : null;
}

/**
 * Get the current user's role
 * @return int|null User role if logged in, null otherwise
 */
function get_user_role() {
    return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
}

/**
 * Check for specific role
 * @param int $required_role The required role to check against
 * @return bool True if user has the required role, false otherwise
 */
function check_role($required_role) {
    $user_role = get_user_role();
    return ($user_role == $required_role);
}

/**
 * Check if user is admin
 * @return bool True if user is admin, false otherwise
 */
function is_admin() {
    return check_admin_privileges();
}

/**
 * Check if user is customer
 * @return bool True if user is customer, false otherwise
 */
function is_customer() {
    return (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 2);
}

/**
 * Redirect to login if user is not logged in
 */
function require_login() {
    if (!check_login()) {
        header("Location: ../login/login.php");
        exit();
    }
}

/**
 * Redirect to login if user doesn't have admin privileges
 */
function require_admin() {
    require_login();
    if (!check_admin_privileges()) {
        header("Location: ../login/login.php?error=access_denied");
        exit();
    }
}

/**
 * Get current logged in user information (renamed from get_current_user)
 * @return array|null User data array or null if not logged in
 */
function get_logged_in_user() {
    if (!check_login()) {
        return null;
    }
    
    return [
        'customer_id' => $_SESSION['customer_id'],
        'customer_name' => $_SESSION['customer_name'],
        'customer_email' => $_SESSION['customer_email'],
        'user_role' => $_SESSION['user_role'],
        'customer_country' => $_SESSION['customer_country'],
        'customer_city' => $_SESSION['customer_city'],
        'customer_contact' => $_SESSION['customer_contact'],
        'customer_image' => $_SESSION['customer_image'],
        'login_time' => $_SESSION['login_time']
    ];
}

/**
 * Check if session has expired (optional security feature)
 * @param int $max_lifetime Maximum session lifetime in seconds (default: 2 hours)
 * @return bool True if session is valid, false if expired
 */
function check_session_expiry($max_lifetime = 7200) {
    if (!isset($_SESSION['login_time'])) {
        return false;
    }
    
    $current_time = time();
    $login_time = $_SESSION['login_time'];
    
    return (($current_time - $login_time) < $max_lifetime);
}

/**
 * Regenerate session ID for security (prevent session fixation)
 */
function regenerate_session() {
    if (check_login()) {
        session_regenerate_id(true);
    }
}

// Auto-regenerate session ID every 30 minutes for security
if (check_login() && (!isset($_SESSION['last_regeneration']) || (time() - $_SESSION['last_regeneration'] > 1800))) {
    regenerate_session();
    $_SESSION['last_regeneration'] = time();
}
?>