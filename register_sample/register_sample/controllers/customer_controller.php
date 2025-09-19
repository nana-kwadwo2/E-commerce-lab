```php
<?php
require_once __DIR__ . '/db_class.php';

class Customer {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    public function add($name, $email, $hash, $country, $city, $contact, $role, $image) {
        $sql = "INSERT INTO customer (customer_name, customer_email, customer_pass, customer_country, customer_city, customer_contact, user_role, customer_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $params = [$name, $email, $hash, $country, $city, $contact, $role, $image];
        $types = 'ssssssis'; // name, email, pass, country, city, contact: string; role: int; image: string (nullable)
        $result = $this->db->write($sql, $params, $types);
        if (!$result[0]) {
            error_log("Customer::add error: " . $result[1]);
            return [false, "oops an error occurred: " . $result[1]]; // Include SQL error for debugging
        }
        return [true, "Customer added"];
    }

    public function get_by_email($email) {
        $sql = "SELECT * FROM customer WHERE customer_email = ?";
        $result = $this->db->read($sql, [$email], 's');
        if (!$result[0]) {
            error_log("Customer::get_by_email error: " . $result[1]);
            return false;
        }
        return $result[1] ? $result[1][0] : false;
    }
}
?>
```


<xaiArtifact artifact_id="22865e50-8b5b-4a04-bd90-0a6e48cadd5f" artifact_version_id="f1a5d63e-5af1-4365-b3d1-7229c6f80b96" title="customer_controller.php" contentType="text/php">
```php
<?php
require_once __DIR__ . '/../classes/customer_class.php';

function register_customer_ctr($payload) {
    foreach (['name', 'email', 'password', 'country', 'city', 'contact'] as $f) {
        if (empty($payload[$f])) return [false, "Missing field: $f"];
    }
    $name = trim($payload['name']);
    $email = strtolower(trim($payload['email']));
    $password = $payload['password'];
    $country = trim($payload['country']);
    $city = trim($payload['city']);
    $contact = trim($payload['contact']);
    $role = isset($payload['role']) ? (int)$payload['role'] : 2;
    $image = $payload['image'] ?? null;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return [false, "Invalid email"];
    if (strlen($password) < 8) return [false, "Password must be at least 8 characters"];
    if (!preg_match('/^[0-9+\-\s]{7,20}$/', $contact)) return [false, "Invalid contact number"];
    if (strlen($contact) > 15) return [false, "Contact number too long (max 15 characters)"];
    $m = new Customer();
    if ($m->get_by_email($email)) return [false, "Email already exists"];
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $res = $m->add($name, $email, $hash, $country, $city, $contact, $role, $image);
    error_log("Customer::add input: name=$name, email=$email, country=$country, city=$city, contact=$contact, role=$role, image=" . ($image ?? 'null'));
    error_log("Customer::add result: " . print_r($res, true));
    return $res[0] ? [true, $res[1]] : [false, $res[1]];
}

function login_customer_ctr($email, $password) {
    $m = new Customer();
    $row = $m->get_by_email(strtolower(trim($email)));
    if (!$row) return [false, "Invalid email or password"];
    if (!password_verify($password, $row['customer_pass'])) return [false, "Invalid email or password"];
    $_SESSION['customer_id'] = $row['customer_id'];
    $_SESSION['customer'] = ['id' => $row['customer_id'], 'name' => $row['customer_name'], 'email' => $row['customer_email'], 'role' => $row['user_role']];
    return [true, $row['customer_id']];
}
?>
```


  <?php
  require_once __DIR__ . '/db_cred.php';
  $conn = mysqli_connect(SERVER, USERNAME, PASSWD, DATABASE);
  if (!$conn) {
      die('Connection failed: ' . mysqli_connect_error());
  }
  echo "Connected to " . DATABASE . " successfully!";
  $result = mysqli_query($conn, "SELECT 1 FROM customer LIMIT 1");
  if ($result) {
      echo "Customer table exists!";
  } else {
      echo "Customer table error: " . mysqli_error($conn);
  }
  mysqli_close($conn);
  ?>
 
  <?php
  // File: actions/register_owner_action.php
  header('Content-Type: application/json');
  session_start();
  require_once __DIR__ . '/../controllers/customer_controller.php';
  error_log("POST data (owner): " . print_r($_POST, true));
  list($ok, $msg) = register_customer_ctr($_POST); // Reuse for now; replace with owner-specific logic
  $response = $ok ? ['status' => 'success', 'message' => 'Registered'] : ['status' => 'error', 'message' => $msg];
  error_log("Response (owner): " . print_r($response, true));
  echo json_encode($response);
  ?>
  ```

