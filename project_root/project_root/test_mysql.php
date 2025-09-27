<?php
echo "<h2>MySQL Connection Test</h2>";

// Test different configurations
$configs = [
    ['localhost', 'root', '', 'dbforlab', 3306],
];

foreach ($configs as $config) {
    list($server, $user, $pass, $db, $port) = $config;
    
    echo "<h3>Testing: $user@$server:$port database '$db'</h3>";
    
    $conn = @mysqli_connect($server, $user, $pass, $db, $port);
    
    if ($conn) {
        echo "✓ <strong>SUCCESS!</strong> Connected to database '$db'<br>";
        
        // Check if customer table exists
        $result = mysqli_query($conn, "SHOW TABLES LIKE 'customer'");
        if ($result && mysqli_num_rows($result) > 0) {
            echo "✓ Customer table exists<br>";
            
            // Show customer count
            $count_result = mysqli_query($conn, "SELECT COUNT(*) as count FROM customer");
            $count_data = mysqli_fetch_assoc($count_result);
            echo "✓ Number of customers: " . $count_data['count'] . "<br>";
        } else {
            echo "✗ Customer table NOT found<br>";
        }
        
        mysqli_close($conn);
    } else {
        echo "✗ Failed: " . mysqli_connect_error() . "<br>";
    }
    echo "<hr>";
}
?>