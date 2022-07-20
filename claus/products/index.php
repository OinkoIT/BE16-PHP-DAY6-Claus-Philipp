<?php
session_start();
require_once '../components/db_connect.php';

if (isset($_SESSION['user']) != "") {
    header("Location: ../home.php");
    exit;
}

if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit;
}

$sql = "SELECT * FROM $tableProducts";
$result = mysqli_query($connect, $sql);
$tbody = ''; //this variable will hold the body for the table
if (mysqli_num_rows($result)  > 0) {
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        if ($row['available'] == 1){
            $available = "✅";
        }else{
            $available = "⛔️";
        }
        $tbody .= "<tr>
            <td class='text-center'><img class='img-thumbnail shadow' src='../pictures/" . $row['picture'] . "'</td>
            <td class='text-center'>" . $row['name'] . "</td>
            <td class='text-center'>" . $row['price'] . "</td>
            <td class='text-center'>" . $available . "</td>
            <td class='text-center'><a href='update.php?id=" . $row['id'] . "'><button class='btn btn-primary btn-sm shadow' type='button'>Edit</button></a>
            <a href='delete.php?id=" . $row['id'] . "'><button class='btn btn-danger btn-sm shadow' type='button'>Delete</button></a></td>
            </tr>";
    };
} else {
    $tbody =  "<tr><td colspan='5'><center>No Data Available </center></td></tr>";
}

mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <?php require_once '../components/boot.php' ?>
    <style type="text/css">
        .manageProduct {
            margin: auto;
        }

        .img-thumbnail {
            width: 70px !important;
            height: 70px !important;
        }

        td {
            text-align: left;
            vertical-align: middle;
        }

        tr {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="manageProduct w-75 mt-3">
        <div class='mb-3'>
            <a href="create.php"><button class='btn btn-primary shadow' type="button">Add product</button></a>
            <a href="../dashboard.php"><button class='btn btn-success shadow' type="button">Dashboard</button></a>
        </div>
        <p class='h2'>Products</p>
        <table class='table table-striped shadow'>
            <thead class='table-success'>
                <tr>
                    <th class='text-center'>Picture</th>
                    <th class='text-center'>Name</th>
                    <th class='text-center'>Price</th>
                    <th class='text-center'>Status</th>
                    <th class='text-center'>Action</th>
                </tr>
            </thead>
            <tbody>
                <?= $tbody; ?>
            </tbody>
        </table>
    </div>
</body>
</html>