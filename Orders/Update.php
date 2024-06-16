<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>☕︎Coffee Shop - Update</title>
<link rel="stylesheet" href="../CSS/Main.css" type="text/css">
<link rel="stylesheet" href="../CSS/Custom/Update.css" type="text/css">
</head>
<body>
    <div id="container">
        <?php
        include "../Database/Database.php";

        $coffee_prices = [
            "Americano"=> 200,
            "Espresso"=> 250,
            "Latte"=> 300,
            "Cappuccino"=> 350,
            "Mocha"=> 400,
        ];
        $size_prices = [
            "Small"=> 0,
            "Medium"=> 50,
            "Large"=> 80,
        ];
        $extras_prices = [
            "Sugar"=> 5.75,
            "Cream"=> 10.50,
        ];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $orderID = $_POST["order_id"];

            try {
                $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("SELECT * FROM orders WHERE orderID = :orderID");
                $stmt->bindParam(':orderID', $orderID);
                $stmt->execute();
                $result = $stmt->fetch();

                if ($result) {
                    $name = isset($_POST["name"]) && $_POST["name"] !== "" ? $_POST["name"] : $result["name"];
                    $coffeeType = isset($_POST["coffee"]) && $_POST["coffee"] !== "" ? $_POST["coffee"] : $result["coffee"];
                    $size = isset($_POST["size"]) && $_POST["size"] !== "" ? $_POST["size"] : $result["size"];
                    $extras = isset($_POST["extras"]) && $_POST["extras"] !== "" ? $_POST["extras"] : $result["extras"];
                    $instructions = isset($_POST["instructions"]) && $_POST["instructions"] !== "" ? $_POST["instructions"] : $result["instructions"];
                    $total_price = calculateTotalPrice($coffee_prices, $size_prices, $extras_prices, $coffeeType, $size, $extras);

                    $updateStmt = $conn->prepare("UPDATE orders SET name=:name, coffeeType=:coffeeType, size=:size, extras=:extras, totalPrice=:totalPrice, instructions=:instructions WHERE orderID=:orderID");
                    $updateStmt->execute(array(
                        ':name' => $name,
                        ':coffeeType' => $coffeeType,
                        ':size' => $size,
                        ':extras' => $extras,
                        ':totalPrice' => $total_price,
                        ':instructions' => $instructions,
                        ':orderID' => $orderID
                    ));
                    echo "Order details updated successfully!";
                } else {
                    echo "Order not found. Please check the Order ID and try again.";
                }
            } catch (PDOException $e) {
                echo "Error: ". $e->getMessage();
            }
            $conn = null;
        }

        function calculateTotalPrice($coffee_prices, $size_prices, $extras_prices, $coffee_type, $size, $extras) {
            $total_price = $coffee_prices[$coffee_type] + $size_prices[$size];

            foreach ($extras as $extra) {
                $total_price += $extras_prices["$extra"];
            }
            return $total_price;
        }
        ?>
        <br/>
        <form action="../Pages/Update.html"><button type="submit">Back</button></form>
    </div>
</body>
</html>