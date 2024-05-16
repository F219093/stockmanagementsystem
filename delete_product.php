<?php
// delete_product.php

// Include database connection
include('db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Delete product from the database
    $sql = "DELETE FROM products WHERE id='$id'";
    
    if (mysqli_query($con, $sql)) {
        echo "Product deleted successfully.";
    } else {
        echo "Error deleting product: " . mysqli_error($con);
    }
} else {
    echo "Product ID not provided.";
}
?>
