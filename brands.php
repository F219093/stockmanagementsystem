<?php
include('db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brand Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #333;
            color: #fff;
            padding: 15px;
            text-align: center;
        }

        .sidebar {
            width: 200px;
            background-color: #f4f4f4;
            padding: 15px;
            position: fixed;
            height: 100%;
            overflow: auto;
        }

        .sidebar h2 {
            text-align: center;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 8px;
            text-align: center;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            display: block;
        }

        .sidebar ul li a:hover {
            background-color: #ddd;
        }

        .main-content {
            margin-left: 220px;
            padding: 20px;
        }

        .form-container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container form label {
            display: block;
            margin-bottom: 10px;
        }

        .form-container form input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container form button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container form button:hover {
            background-color: #0056b3;
        }

        .brand-list {
            list-style-type: none;
            padding: 0;
            margin-top: 30px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-gap: 20px;
        }

        .brand-item {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .brand-item:nth-child(3n+1) {
            grid-column: 1;
        }

        .brand-item:nth-child(3n+2) {
            grid-column: 2;
        }

        .brand-item:nth-child(3n+3) {
            grid-column: 3;
        }

        .brand-name {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .brand-actions {
            display: flex;
            justify-content: space-between;
        }

        .actions {
            font-size: 18px;
            cursor: pointer;
        }

        .actions:hover {
            color: #007bff;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <?php include('sidebar.php'); ?>

    <div class="main-content">
        <div class="form-container">
            <h1>Add Brand</h1>
            <form method="post" action="process_brand.php">
                <input type="hidden" name="original_name" id="originalName">
                <label for="brandName">Brand Name:</label>
                <input type="text" name="brand_name" id="brandName" required>
                <button type="submit" name="submit">Submit</button>
            </form>
        </div>

        <h2>Existing Brands</h2>
        <ul class="brand-list">
            <?php
            include('db.php');
            $sql = "SELECT brand_name FROM brands";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li class='brand-item'>";
                    echo "<div class='brand-name'>" . htmlspecialchars($row['brand_name']) . "</div>";
                    echo "<div class='brand-actions'>";
                    echo "<span class='actions' onclick=\"editBrand('" . htmlspecialchars(addslashes($row['brand_name'])) . "')\">✏️</span>";
                    echo "<span class='actions' onclick=\"deleteBrand('" . htmlspecialchars(addslashes($row['brand_name'])) . "')\">❌</span>";
                    echo "</div>";
                    echo "</li>";
                }
            }
            ?>
        </ul>
    </div>

    <script>
        function editBrand(name) {
            document.getElementById('brandName').value = name;
            document.getElementById('originalName').value = name;
        }

        function deleteBrand(name) {
            if (confirm('Are you sure you want to delete the brand "' + name + '"?')) {
                window.location.href = 'delete_brand.php?brand_name=' + encodeURIComponent(name);
            }
        }
    </script>
</body>
</html>
