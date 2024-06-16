<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>☕︎Coffee Shop - Orders</title>
<link rel="stylesheet" href="../CSS/Main.css" type="text/css">
</head>
<body>
    <div id="container">
        <h1>☕︎Coffee Shop Orders</h1>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Name</th>
                <th>Coffee Type</th>
                <th>Size</th>
                <th>Total Price</th>
                <th>Instructions</th>
                <th>Extras</th>
            </tr>
            <?php include '../Orders/Show_Order.php';?>
        </table>
        <br/>
        <form action="Menu.html"><button type="submit">Back to Main Menu</button></form>
    </div>
</body>
</html>