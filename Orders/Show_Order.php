<?php
include "../Database/Database.php";

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM orders");
    $stmt->execute();

    $results = $stmt->fetchAll();
    foreach ($results as $result) {
        echo "<tr>";
        echo "<td>" . $result['orderID'] . "</td>";
        echo "<td>" . $result['name'] ."</td>";
        echo "<td>" . $result['coffeeType'] ."</td>";
        echo "<td>" . $result['size'] ."</td>";
        echo "<td>₱". number_format($result['totalPrice']) ."</td>";
        echo "<td>" . $result['instructions'] . '</td>';
        echo "<td>" . $result['extras'] . '</td>';
        echo "</tr>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
?>