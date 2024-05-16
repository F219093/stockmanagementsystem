<?php
// edit_product.php

// Include database connection
include('db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Retrieve product details from the database
    $sql = "SELECT * FROM products WHERE id='$id'";
    $result = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Process edit logic here
        // You can populate a form with the product details and allow the user to edit them
        // Or perform any other edit operation as required
    } else {
        echo "Product not found.";
    }
} else {
    echo "Product ID not provided.";
}
?>
