<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
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

        .category-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .category-item {
            width: calc(33.33% - 20px);
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .category-item:nth-child(3n+1) {
            grid-column: 1;
        }

        .category-item:nth-child(3n+2) {
            grid-column: 2;
        }

        .category-item:nth-child(3n+3) {
            grid-column: 3;
        }

        .category-name {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .category-actions {
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
            <h1>Add Category</h1>
            <form method="post" action="process_category.php">
                <input type="hidden" name="original_name" id="originalName">
                <label for="categoryName">Category Name:</label>
                <input type="text" name="category_name" id="categoryName" required>
                <button type="submit" name="submit">Submit</button>
            </form>
        </div>

        <h2>Existing Categories</h2>
        <div class="category-grid">
            <?php
            include('db.php');
            $sql = "SELECT category_name FROM categories";
            $result = $con->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='category-item'>";
                    echo "<div class='category-name'>" . htmlspecialchars($row['category_name']) . "</div>";
                    echo "<div class='category-actions'>";
                    echo "<span class='actions' onclick=\"editCategory('" . htmlspecialchars(addslashes($row['category_name'])) . "')\">✏️</span>";
                    echo "<span class='actions' onclick=\"deleteCategory('" . htmlspecialchars(addslashes($row['category_name'])) . "')\">❌</span>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            ?>
        </div>
    </div>

    <script>
        function editCategory(name) {
            document.getElementById('categoryName').value = name;
            document.getElementById('originalName').value = name;
        }

        function deleteCategory(name) {
            if (confirm('Are you sure you want to delete the category "' + name + '"?')) {
                window.location.href = 'delete_category.php?category_name=' + encodeURIComponent(name);
            }
        }
    </script>
</body>
</html>
