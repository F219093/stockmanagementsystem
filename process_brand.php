<?php
include('db.php');

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $total_price = $_POST['total_price'];

    // Record the sale
    $sql = "INSERT INTO sales_record (product_id, quantity, total_price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $product_id, $quantity, $total_price);

    if ($stmt->execute() === TRUE) {
        $msg = "Sale recorded successfully.";
    } else {
        $msg = "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Point of Sale</title>
</head>
<body>
    <h2>Point of Sale</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="product">Select Product:</label>
        <select name="product_id" id="product">
            <?php
            $sql = "SELECT * FROM products";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['id']."'>".$row['name']." - ".$row['price']."</option>";
                }
            }
            ?>
        </select><br><br>
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" min="1" value="1"><br><br>
        <label for="total_price">Total Price:</label>
        <input type="text" name="total_price" id="total_price" readonly><br><br>
        <input type="submit" name="submit" value="Sell">
    </form>
    
    <script>
        // Calculate total price based on selected product and quantity
        document.getElementById('product').addEventListener('change', function() {
            var selectedProduct = this.options[this.selectedIndex];
            var price = parseFloat(selectedProduct.text.split(' - ')[1]);
            var quantity = parseInt(document.getElementById('quantity').value);
            document.getElementById('total_price').value = (price * quantity).toFixed(2);
        });

        document.getElementById('quantity').addEventListener('input', function() {
            var selectedProduct = document.getElementById('product').options[document.getElementById('product').selectedIndex];
            var price = parseFloat(selectedProduct.text.split(' - ')[1]);
            var quantity = parseInt(document.getElementById('quantity').value);
            document.getElementById('total_price').value = (price * quantity).toFixed(2);
        });
    </script>
</body>
</html>
