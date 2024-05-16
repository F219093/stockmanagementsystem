<?php
include('db.php');

$msg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_ids = $_POST['product_id'];
    $quantities = $_POST['quantity'];
    $total_price = $_POST['total_price'];

    foreach ($product_ids as $index => $product_id) {
        $quantity = $quantities[$index];

        // Check if the product ID exists
        $product_check_sql = "SELECT * FROM products WHERE id = ?";
        $product_check_stmt = $con->prepare($product_check_sql);
        if (!$product_check_stmt) {
            die("Error preparing statement: " . $con->error);
        }
        $product_check_stmt->bind_param("i", $product_id);
        $product_check_stmt->execute();
        $product_result = $product_check_stmt->get_result();

        if ($product_result->num_rows > 0) {
            // Record the sale
            $sql = "INSERT INTO sales_record (product_id, quantity, total_price) VALUES (?, ?, ?)";
            $stmt = $con->prepare($sql);
            if (!$stmt) {
                die("Error preparing statement: " . $con->error);
            }
            $stmt->bind_param("iid", $product_id, $quantity, $total_price);

            if ($stmt->execute() === TRUE) {
                $msg = "Sale recorded successfully.";

                // Update total_stock
                $update_stock_sql = "UPDATE products SET total_stock = total_stock - ? WHERE id = ?";
                $update_stock_stmt = $con->prepare($update_stock_sql);
                if (!$update_stock_stmt) {
                    die("Error preparing statement: " . $con->error);
                }
                $update_stock_stmt->bind_param("ii", $quantity, $product_id);

                if ($update_stock_stmt->execute() === FALSE) {
                    $msg = "Error updating total_stock: " . $con->error;
                }
            } else {
                $msg = "Error: " . $sql . "<br>" . $con->error;
            }

            $stmt->close();
        } else {
            $msg = "Product ID " . $product_id . " does not exist.";
        }

        $product_check_stmt->close();
    }
}

// Fetch all products
$products_sql = "SELECT * FROM products";
$products_result = $con->query($products_sql);
if (!$products_result) {
    die("Error fetching products: " . $con->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Point of Sale</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        select, input[type="number"], input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .product-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .product-row select, .product-row input {
            flex: 1;
        }
        .product-row button {
            padding: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Point of Sale</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="posForm">
            <div id="productsContainer">
                <div class="product-row">
                    <label for="product">Select Product:</label>
                    <select name="product_id[]" class="product">
                        <?php
                        if ($products_result->num_rows > 0) {
                            while($row = $products_result->fetch_assoc()) {
                                echo "<option value='".$row['id']."'>".$row['name']." - $".$row['price']." (".$row['total_stock']." in stock)</option>";
                            }
                        }
                        ?>
                    </select>
                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity[]" class="quantity" min="1" value="1">
                    <button type="button" onclick="addProductRow()">+</button>
                </div>
            </div>
            <label for="total_price">Total Price:</label>
            <input type="text" name="total_price" id="total_price" readonly>
            <input type="submit" name="submit" value="Sell">
        </form>
        <?php if (!empty($msg)): ?>
            <p><?php echo $msg; ?></p>
        <?php endif; ?>
        <h3>Sales Records</h3>
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Sale Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sales_sql = "SELECT * FROM sales_record";
                $sales_result = $con->query($sales_sql);
                if ($sales_result->num_rows > 0) {
                    while($row = $sales_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$row['product_id']."</td>";
                        echo "<td>".$row['quantity']."</td>";
                        echo "<td>$".$row['total_price']."</td>";
                        echo "<td>".$row['sale_date']."</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No sales records found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function addProductRow() {
            const productsContainer = document.getElementById('productsContainer');
            const newRow = document.createElement('div');
            newRow.classList.add('product-row');
            newRow.innerHTML = `
                <label for="product">Select Product:</label>
                <select name="product_id[]" class="product">
                    <?php
                    $products_result->data_seek(0); // Reset result pointer to the start
                    if ($products_result->num_rows > 0) {
                        while($row = $products_result->fetch_assoc()) {
                            echo "<option value='".$row['id']."'>".$row['name']." - $".$row['price']." (".$row['total_stock']." in stock)</option>";
                        }
                    }
                    ?>
                </select>
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity[]" class="quantity" min="1" value="1">
                <button type="button" onclick="removeProductRow(this)">-</button>
            `;
            productsContainer.appendChild(newRow);
            calculateTotalPrice();  // Calculate total price after adding a new row
        }

        function removeProductRow(button) {
            button.parentElement.remove();
            calculateTotalPrice();  // Calculate total price after removing a row
        }

        document.getElementById('posForm').addEventListener('input', calculateTotalPrice);

        function calculateTotalPrice() {
            const productElements = document.querySelectorAll('.product');
            const quantityElements = document.querySelectorAll('.quantity');
            let total = 0;

            productElements.forEach((productElement, index) => {
                const selectedOption = productElement.options[productElement.selectedIndex];
                const price = parseFloat(selectedOption.text.split(' - $')[1].split(' ')[0]);
                const quantity = parseInt(quantityElements[index].value);
                total += price * quantity;
            });

            document.getElementById('total_price').value = total.toFixed(2);
        }

        // Initial call to set total price based on default values
        calculateTotalPrice();
    </script>
</body>
</html>
