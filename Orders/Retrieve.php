<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>☕︎Coffee Shop - Retrieve Order</title>
<link rel="stylesheet" href="../CSS/Main.css" type="text/css">
<link rel="stylesheet" href="../CSS/Custom/RetrieveAndDelete.css" type="text/css">
</head>
<body>
    <div id="container">
        <?php
        include "../Database/Database.php";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $orderID = $_POST['order_id'];

            try {
                $conn = new PDo("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $stmt = $conn->prepare("SELECT * FROM orders Where orderID = :orderID");
                $stmt->bindParam(':orderID', $orderID);
                $stmt->execute();
                
                $result = $stmt->fetch();
                if ($result) {
                    echo "<h1>☕︎Coffee Order Details</h1>";
                    echo "<table>";
                    echo "<tr><td>Order ID</td><td>" . $result['orderID'] . "</td></tr>";
                    echo "<tr><td>Name</td><td>" . $result['name'] . "</td></tr>";
                    echo "<tr><td>Coffee Type</td><td>" . $result['coffeeType'] . "</td></tr>";
                    echo "<tr><td>Size</td><td>" . $result['size'] . "</td></tr>";
                    echo "<tr><td>Extras</td><td>" . $result['extras'] . "</td></tr>";
                    echo "<tr><td>Total Price</td><td>" . $result['totalPrice'] . "</td></tr>";
                    echo "<tr><td>Special Instruction</td><td>" . $result['instructions'] . "</td></tr>";
                    echo "</table>";
                } else {
                    echo "Order not found. Please check the Order ID and try again.";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        $conn = null;
        ?>
        <br/>
        <form action="../Pages/Retrieve.html"><button type="submit">Back</button></form>
    </div>
</body>
</html>