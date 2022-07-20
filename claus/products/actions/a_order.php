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

$res = mysqli_query($connect, "SELECT * FROM $tableUser WHERE id=" . $_SESSION['user']);
$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
$user = $row['id'];
$userfname = $row['first_name'];
$userlname = $row['last_name'];


if ($_GET) {
    $product = $_GET['id'];
    $res2 = mysqli_query($connect, "SELECT * FROM $tableProducts WHERE id= $product" );
    $row2 = mysqli_fetch_array($res2, MYSQLI_ASSOC);
    $productName = $row2['name'];
    $sql = "INSERT INTO `order`(`fk_user_id`, `fk_product_id`, `fk_product_name`, `fk_user_fname`, `fk_user_lname`) VALUES ('$user','$product', '$productName', '$userfname', '$userlname')";
    $sql2 = mysqli_query($connect, "SELECT * FROM $tableProducts WHERE id=$product");


    if (mysqli_query($connect, $sql) === true) {
        $row2 = mysqli_fetch_array($sql2, MYSQLI_ASSOC);
        $prodname = $row2['name'];
        $class = "success amatic-xs";
        $message = "We received your Order! <br>
            <table class='table w-50'><tr>
            <td> Client: $userfname </td>
            <td> Item ordered: $prodname </td>
            </tr></table><hr>";
    } else {
        $class = "danger amatic-xs";
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
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@700&family=Square+Peg&display=swap" rel="stylesheet">
    <?php require_once '../../components/boot.php' ?>
    <style>
        .userImage {
            width: 200px;
            height: 200px;
        }

        .hero {
            background: rgb(2, 0, 36);
            background: linear-gradient(24deg, rgba(2, 0, 36, 1) 0%, rgba(0, 212, 255, 1) 100%);
        }

        .img-thumbnail {
            width: 70px !important;
            height: 70px !important;
        }

        .hero {
            background-image: url(https://images.unsplash.com/photo-1505935428862-770b6f24f629?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2334&q=80);
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            height: 400px;
            box-shadow: inset 0px 0px 20px black;
        }

        .inside-hero {
            position: relative;
            top: 20%;
            text-shadow: 1px 1px 2px black;
            /* background-color: rgba(204, 202, 202, 0.448); */
            /* box-shadow: 0px 0px 10px black; */
            /* border-radius: 5px; */
        }
        .amatic{
        font-family: 'Amatic SC', cursive;
        font-size: 1.5em;
        }
        .amatic-xs{
        font-family: 'Amatic SC', cursive;
        font-size: 1.5em;
        }
        .peg{
        font-family: 'Square Peg', cursive;
        font-size: 5em;
        }
        .peg-sm{
        font-family: 'Square Peg', cursive;
        font-size: 3em;
        }
        .peg-xs{
        font-family: 'Square Peg', cursive;
        font-size: 2em;
        }
    </style>
</head>

<body>
    <div class="container w-75">
        <div class="mt-3 mb-3">
            <h1 class="peg">Your Order has been placed</h1>
        </div>
        <div class="alert alert-<?= $class; ?>" role="alert">
            <p class="amatic-xs"><?php echo ($message) ?? ''; ?></p>
            <p class="amatic-xs"><?php echo ($uploadError) ?? ''; ?></p>
            <a href='../index.php'><button class="btn btn-primary btn-sm amatic-xs" type='button'>Home</button></a>
        </div>
    </div>
</body>
</html>