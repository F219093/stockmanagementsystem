<?php
include('db.php');
session_start();

if (isset($_POST['email']) && $_POST['password']) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $res = mysqli_query($con, "SELECT * FROM user WHERE email='$email' AND password='$password'");
    $check = mysqli_num_rows($res);
    if ($check > 0) {
        $row = mysqli_fetch_assoc($res);
        $status = $row['status'];
        if ($status == 0) {
            // Account is not verified
            $_SESSION['LOGIN_ERROR'] = "Your account is not verified. Contact your admin to verify it.";
            header("Location: index.php"); // Redirect to login page
            exit();
        } else {
            // Account is verified
            $_SESSION['IS_LOGIN'] = true;
            $role = $row['role'];
            if ($role == '0') {
                // Redirect to admin dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                // Redirect to staff dashboard
                header("Location: staffdashboard.php");
                exit();
            }
        }
    } else {
        // Invalid login credentials
        $_SESSION['LOGIN_ERROR'] = "Please enter correct login details";
        header("Location: index.php"); // Redirect to login page
        exit();
    }
} else {
    // Redirect to login page if login form is not submitted
    header("Location: index.php");
    exit();
}
?>
