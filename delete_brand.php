<?php
include('db.php');

if (isset($_GET['brand_name'])) {
    $brandName = $_GET['brand_name'];

    // Delete brand
    $sql = "DELETE FROM brands WHERE brand_name=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $brandName);

    if ($stmt->execute() === TRUE) {
        echo "Brand deleted successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$con->close();
?>
