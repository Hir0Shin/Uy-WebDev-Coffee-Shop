<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>☕︎Coffee Shop - Delete Order</title>
<link rel="stylesheet" href="../CSS/Main.css" type="text/css">
<link rel="stylesheet" href="../CSS/Custom/Delete.css" type="text/css">
</head>
<body>
    <div id="delete-container">
        <?php
        SESSION_START();
        include "../Database/Database.php";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (isset($_POST["order_id"]) && !empty($_POST["order_id"])) {
                
                $orderID = $_POST["order_id"];

                try {
                    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $stmt = $conn->prepare("DELETE FROM `orders` WHERE orderID = :orderID");
                    $stmt->bindParam(':orderID', $orderID);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        echo"Order with ID $orderID has been deleted successfuly.";
                        echo"<script>
                        alert('Order Deleted!');
                        </script>";
                    } else {
                        echo"No order found with the provided ID. Please check the Order ID and try again.";
                        echo"<script>
                            setTimeout(function() {
                            alert('No Order found. Please provide an existing ID!');
                            },100);
                        </script>";
                    }
                } catch (PDOException $e) {
                    echo "Error: ". $e->getMessage();
                }
            } else {
                echo"Please provide the Order ID";
            }
            $conn = null;
        }    
        ?>
    <br/>
    <form action="../Pages/Delete.html"><button type="submit">Back</button></form>
    </div>
</body>
</html>