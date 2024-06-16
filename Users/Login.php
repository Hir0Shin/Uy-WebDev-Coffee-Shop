<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>☕︎Coffee Shop</title>
<link rel="stylesheet" href="../CSS/Main.css" type="text/css">
<link rel="stylesheet" href="../CSS/Custom/LoginAndRegister.css" type="text/css">
</head>
<body>
    <div id="container">
        <?php
        SESSION_START();
        include "../Database/Database.php";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = $_POST["username"];
            $password = $_POST["password"];

            try {
                $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_username, $db_password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $checkStmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
                $checkStmt->bindParam(':username', $username);
                $checkStmt->execute();

                if ($checkStmt->rowCount() > 0) {
                    $user = $checkStmt->fetch(PDO::FETCH_ASSOC);

                    if (password_verify($password, $user['password'])) {
                        $_SESSION['username'] = $username;
                        header("Location:../Pages/Menu.html");
                        exit();
                    } else {
                        echo "Invalid username or password";
                    }
                } else {
                    echo "Username does not exist!";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        ?>
        <form action="../Pages/Login.html"><button type="submit">Back</button></form>
    </div>
</body>
</html>