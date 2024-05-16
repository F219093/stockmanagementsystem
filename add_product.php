<?php
include('db.php');

$msg = "";

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $sale_price = $_POST['sale_price'];
    $retail_price = $_POST['retail_price'];
    $total_stock = $_POST['total_stock'];

    $sql = "INSERT INTO products (name, category, brand, sale_price, retail_price, total_stock) VALUES ('$name', '$category', '$brand', '$sale_price', '$retail_price', '$total_stock')";

    if (mysqli_query($con, $sql)) {
        $msg = "Product added successfully";
    } else {
        $msg = "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group select {
            width: calc(100% - 10px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group select {
            cursor: pointer;
        }

        .form-group input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        td.actions {
            white-space: nowrap;
        }

        td.actions a {
            margin-right: 5px;
            color: #007bff;
        }

        td.actions a:hover {
            text-decoration: none;
            color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <?php include('sidebar.php'); ?>

    <div class="container">
        <h1>Add Product</h1>
        <form action="add_product.php" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="">Select Category</option>
                    <?php
                    $sql = "SELECT * FROM categories";
                    $result = mysqli_query($con, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['category_name'] . "'>" . $row['category_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="brand">Brand:</label>
                <select id="brand" name="brand" required>
                    <option value="">Select Brand</option>
                    <?php
                    $sql = "SELECT * FROM brands";
                    $result = mysqli_query($con, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['brand_name'] . "'>" . $row['brand_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="sale_price">Sale Price:</label>
                <input type="text" id="sale_price" name="sale_price" required>
            </div>
            <div class="form-group">
                <label for="retail_price">Retail Price:</label>
                <input type="text" id="retail_price" name="retail_price" required>
            </div>
            <div class="form-group">
                <label for="total_stock">Total Stock:</label>
                <input type="text" id="total_stock" name="total_stock" required>
</div>

            <div class="form-group">
                <input type="submit" value="Add Product" name="submit">
            </div>
        </form>

        <h2>Existing Products</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Sale Price</th>
                    <th>Retail Price</th>
                    <th>Total Stock</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM products";
                $result = mysqli_query($con, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['category'] . "</td>";
                    echo "<td>" . $row['brand'] . "</td>";
                    echo "<td>" . $row['sale_price'] . "</td>";
                    echo "<td>" . $row['retail_price'] . "</td>";
                    echo "<td>" . $row['total_stock'] . "</td>";
                    echo "<td class='actions'>";
                    echo "<a href='edit_product.php?id=" . $row['id'] . "'><i class='fa fa-edit'></i></a> | ";
                    echo "<a href='delete_product.php?id=" . $row['id'] . "'><i class='fa fa-trash'></i></a>";
                    echo "</td>";

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
