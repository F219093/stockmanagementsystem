<?php
include('db.php');

$msg = "";

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $sale_price = $_POST['sale_price'];
    $retail_price = $_POST['retail_price'];

    $sql = "INSERT INTO products (name, category, brand, sale_price, retail_price) VALUES ('$name', '$category', '$brand', '$sale_price', '$retail_price')";

    if (mysqli_query($con, $sql)) {
        $msg = "Product added successfully";
    } else {
        $msg = "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}

header("Location: add_product.php?msg=" . $msg);
exit(); // Make sure to exit after redirection
?>
