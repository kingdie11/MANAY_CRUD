<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple CRUD</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    

    <?php
    // Database connection
    $servername = "localhost";
    $username = "kingd"; 
    $password = "kingd"; 
    $dbname = "products_appdev"; 

    $conn = new mysqli($servername, $username, $password, $dbname);

   
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, quantity, barcode, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("ssdis", $name, $description, $price, $quantity, $barcode);

        
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $barcode = $_POST['barcode'];

        if ($stmt->execute()) {
            echo "<p>New record created successfully</p>";
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    // Fetch 
    $sql = "SELECT id, name, description, price, quantity, barcode, created_at, updated_at FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Name</th><th>Description</th><th>Price</th><th>Quantity</th><th>Barcode</th><th>Created At</th><th>Updated At</th><th>Actions</th></tr>";

       
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>" . $row['price'] . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>" . $row['barcode'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "<td>" . $row['updated_at'] . "</td>";
            echo "<td>
                    <form style='display:inline;' action='update.php' method='POST'>
                        <input type='hidden' name='id' value='" . $row['id'] . "'>
                        <input type='submit' value='Update'>
                    </form>
                    <form style='display:inline;' action='delete.php' method='POST'>
                        <input type='hidden' name='id' value='" . $row['id'] . "'>
                        <input type='submit' value='Delete'>
                    </form>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No records found</p>";
    }

    $conn->close();
    ?>
    <h2>Create New Record</h2>

    <form action="" method="POST">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="price">Price:</label><br>
        <input type="text" id="price" name="price" step="0.01" required><br><br>

        <label for="quantity">Quantity:</label><br>
        <input type="text" id="quantity" name="quantity" required><br><br>

        <label for="barcode">Barcode:</label><br>
        <input type="text" id="barcode" name="barcode" required><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
