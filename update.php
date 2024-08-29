<?php
$servername = "localhost";
$username = "kingd"; 
$password = "kingd"; 
$dbname = "products_appdev"; 

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    if (isset($_POST['name'])) { 
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $barcode = $_POST['barcode'];

        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, quantity=?, barcode=?, updated_at=NOW() WHERE id=?");
        $stmt->bind_param("ssdisi", $name, $description, $price, $quantity, $barcode, $id);

        if ($stmt->execute()) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
        header("Location: create.php"); 
    } else { 
        $stmt = $conn->prepare("SELECT name, description, price, quantity, barcode FROM products WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($name, $description, $price, $quantity, $barcode);
        $stmt->fetch();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Record</title>
</head>
<body>
    <h2>Update Record</h2>
    <form action="" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required><?php echo $description; ?></textarea><br><br>

        <label for="price">Price:</label><br>
        <input type="number" id="price" name="price" step="0.01" value="<?php echo $price; ?>" required><br><br>

        <label for="quantity">Quantity:</label><br>
        <input type="number" id="quantity" name="quantity" value="<?php echo $quantity; ?>" required><br><br>

        <label for="barcode">Barcode:</label><br>
        <input type="text" id="barcode" name="barcode" value="<?php echo $barcode; ?>" required><br><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>
