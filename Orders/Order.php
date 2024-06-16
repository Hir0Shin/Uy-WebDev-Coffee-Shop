<html lang="EN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>☕︎Coffee Shop - Order Summary</title>
<link rel="stylesheet" href="../CSS/Main.css" type="text/css">
<link rel="stylesheet" href="../CSS/Custom/Order.css" type="text/css">
</head>
<body>
    <?php
        function displayOrderSummary() {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                echo "<div id='summary'>";
                echo "<h2>📝Order Summary</h2>";

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

                $name = htmlspecialchars($_POST["name"]);
                $coffeeType = htmlspecialchars($_POST["coffee"]);
                $size =htmlspecialchars($_POST["size"]);
                $instructions = htmlspecialchars($_POST["instructions"]);
                $coffee_type = $_POST["coffee"];
                $size = $_POST["size"];
                $extras = isset($_POST["extras"]) ? $_POST["extras"] : [];
                $total_price = calculateTotalPrice($coffee_prices, $size_prices, $extras_prices, $coffee_type, $size, $extras);
                $receiptContent = generateReceiptContent($name, $coffeeType, $coffee_prices, $size, $size_prices, $extras, $extras_prices, $total_price, $instructions);

                displayOrderDetails($name, $coffee_prices, $coffee_type, $size_prices, $size, $extras_prices, $extras, $total_price);
                displayJokeAndPassword($coffee_type, $name, $total_price);
                saveReceipttoFile($receiptContent);
                insertOrderToDatabase($name, $coffeeType, $size, $extras, $instructions, $total_price);
                echo "</div>";
            }
        }

        function calculateTotalPrice($coffee_prices, $size_prices, $extras_prices, $coffee_type, $size, $extras) {
            $total_price = $coffee_prices[$coffee_type] + $size_prices[$size];
            foreach ($extras as $extra) {
                $total_price += $extras_prices[$extra];
            }
            return $total_price;
        }

        function displayOrderDetails($name, $coffee_prices, $coffee_type, $size_prices, $size, $extras_prices, $extras, $total_price) {
            echo "<meta charset='UTF-8'>";
            echo "<table>";
            echo "<tr><td>Name</td><td>" . htmlspecialchars($name) . "</td></tr>";
            echo "<tr><td>Coffee Type</td><td>" . htmlspecialchars($coffee_type) . " (₱" . number_format($coffee_prices[$coffee_type], 2) . ")</td></tr>";
            if ($size != "Small") {
                echo "<tr><td>Size</td><td>" . htmlspecialchars($size) . " (+₱" . number_format($size_prices[$size], 2) . ")</td></tr>";
            } else {
                echo "<tr><td>Size</td><td>" . htmlspecialchars($size) . "</td></tr>";
            }
            if (!empty($extras)) {
                echo "<tr><td>Extras</td><td>" . implode(", ", $extras) . "(+₱" . number_format(array_sum(array_intersect_key($extras_prices, array_flip($extras))), 2) . ")</td></tr>";
            }
            echo "<tr><td>Total Price</td><td>" . "₱" . number_format($total_price, 2) . "</td></tr>";
            echo "<tr><td>Special instructions</td><td>" . htmlspecialchars($_POST["instructions"]) . "</td></tr>";
            echo "</table>";
        }

        function displayJokeAndPassword($coffee_type, $name, $total_price) {
            if ($coffee_type !== "Espresso") {
                echo "Hey, " . htmlspecialchars($name) . "!";
                echo "<p>Here's a joke for you: Why did the Coffee file a police report? It got mugged!</p>";
            }
            if ($total_price > 250 && $total_price < 350) {
                echo "<p>Password for the CR: Coffee147</p>";
            } elseif ($total_price >= 350) {
                echo "<p>Password for Wi-Fi: Mocha369</p>";
            }
        }

        function generateReceiptContent($name, $coffeeType, $coffee_prices, $size, $size_prices, $extras, $extras_prices, $total_price, $instructions) {
            $receiptcontent = "Order Summary\n";
            $receiptcontent .= "-------------------";
            $receiptcontent .= "Name: " . $name . "\n";
            $receiptcontent .= "Coffee Type: " . $coffeeType . "(₱" . number_format($coffee_prices[$coffeeType], 2) . ")\n";
            if ($size != "Regular") {
                $receiptcontent .= "Size: " . " (₱" . number_format($size_prices[$size], 2) . ")\n";
            } else {
                $receiptcontent .= "Size: " . htmlspecialchars($size) . "\n";
            }
            if (!empty($extras)) {
                $receiptcontent .= "Extras: " . implode(", ", $extras) . "(₱" . number_format(array_sum(array_intersect_key($extras_prices, array_flip($extras))), 2) . ")\n";
            }
            $receiptcontent .= "Total Price: ₱" . number_format($total_price, 2) . "\n";
            $receiptcontent .= "Special Instructions: " . $instructions . "\n";
            $receiptcontent .= "\n";
            $receiptcontent .= "Thank you for your order!";
            return $receiptcontent;
        }

        function saveReceipttoFile($receiptContent) {
            $file = fopen("Coffee Shop Receipt.txt", "w") or die("Unable to open file!");
            fwrite($file, $receiptContent);
            fclose($file);
            echo "Receipt Created Successfully as Coffee Shop Receipt.txt!";
        }
        displayOrderSummary();

        function insertOrderToDatabase($name, $coffeeType, $size, $total_price, $instructions, $extras) {
            $db_host = 'localhost';
            $db_name = 'coffeeshop';
            $db_username = 'root';
            $db_password = '456789';

            include "../Database/Database.php";
            
            try {
                $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $conn->prepare("INSERT INTO orders (name, coffeeType, size, totalPrice, instructions, extras)
                    VALUES (:name, :coffee_type, :size, :totalPrice, :instructions, :extras)");

                $extras_string = implode(", ", $extras);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':coffee_type', $coffeeType);
                $stmt->bindParam(':size', $size);
                $stmt->bindParam(':totalPrice', $total_price);
                $stmt->bindParam(':instructions', $instructions);
                $stmt->bindParam(':extras', $extras_string);
                $stmt->execute();

                echo"<br/> Order details inserted into the database successfully!";
            } catch (PDOException $e) {
                echo "Error: ". $e->getMessage();
            }
        }
    $conn = null;
    ?>
    <br>
    <form action="../Pages/Order.html"><button type="submit">Back</button></form>
</body>
</html>