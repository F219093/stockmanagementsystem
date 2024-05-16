<?php
include('db.php');

$msg = "";

if (isset($_GET['category_name'])) {
    $category_name = $_GET['category_name'];
    
    $sql = "DELETE FROM categories WHERE category_name='$category_name'";

    if (mysqli_query($con, $sql)) {
        $msg = "Category deleted successfully";
    } else {
        $msg = "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}

header("Location: categories.php?msg=" . $msg);
?>
