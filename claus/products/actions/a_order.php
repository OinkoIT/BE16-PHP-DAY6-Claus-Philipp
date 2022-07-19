<?php
session_start();

// if (isset($_SESSION['user']) != "") {
//     header("Location: ../../home.php");
//     exit;
// }

if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit;
}

require_once '../../components/db_connect.php';
require_once '../../components/file_upload.php';

$res = mysqli_query($connect, "SELECT * FROM users WHERE id=" . $_SESSION['user']);
$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
$user = $row['id'];
$username = $row['first_name'];


if ($_GET) {
    $product = $_GET['id'];
    $sql = "INSERT INTO `order`(`fk_user_id`, `fk_product_id`) VALUES ('$user','$product')";
    $sql2 = mysqli_query($connect, "SELECT * FROM products WHERE id=$product");


    if (mysqli_query($connect, $sql) === true) {
        $row2 = mysqli_fetch_array($sql2, MYSQLI_ASSOC);
        $prodname = $row2['name'];
        $class = "success";
        $message = "Your order has been placed <br>
            <table class='table w-50'><tr>
            <td> User: $username </td>
            <td> Dish: $prodname </td>
            </tr></table><hr>";
    } else {
        $class = "danger";
        $message = "Error while ordering. Try again: <br>" . $connect->error;
        
    }
    mysqli_close($connect);
} else {
    header("location: ../error.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order</title>
    <?php require_once '../../components/boot.php' ?>
</head>

<body>
    <div class="container">
        <div class="mt-3 mb-3">
            <h1>Create request response</h1>
        </div>
        <div class="alert alert-<?= $class; ?>" role="alert">
            <p><?php echo ($message) ?? ''; ?></p>
            <p><?php echo ($uploadError) ?? ''; ?></p>
            <a href='../index.php'><button class="btn btn-primary" type='button'>Home</button></a>
        </div>
    </div>
</body>
</html>