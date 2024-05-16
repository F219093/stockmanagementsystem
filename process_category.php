<?php
include('db.php');

$msg = "";

if (isset($_POST['submit'])) {
    $category_name = $_POST['category_name'];
    
    $sql = "INSERT INTO categories (category_name) VALUES ('$category_name')";

    if (mysqli_query($con, $sql)) {
        $msg = "Category added successfully";
    } else {
        $msg = "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}

header("Location: categories.php?msg=" . $msg);
?>
