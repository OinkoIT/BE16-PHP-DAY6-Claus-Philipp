<?php
session_start();
require_once 'components/db_connect.php';

// if adm will redirect to dashboard
if (isset($_SESSION['adm'])) {
    header("Location: dashboard.php");
    exit;
}
// if session is not set this will redirect to login page
if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// select logged-in users details - procedural style
$res = mysqli_query($connect, "SELECT * FROM users WHERE id=" . $_SESSION['user']);
$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
$adminpic = $row['picture'];
$adminname = $row['first_name'];
$adminlname = $row['last_name'];



$sql = "SELECT * FROM products";
$result = mysqli_query($connect, $sql);
$tbody = ''; //this variable will hold the body for the table
if (mysqli_num_rows($result)  > 0) {
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $tbody .= "<tr>
            <td><img class='img-thumbnail' src='pictures/" . $row['picture'] . "'</td>
            <td>" . $row['name'] . "</td>
            <td>" . $row['price'] . "</td>
            <td><a href='products/update.php?id=" . $row['id'] . "'><button class='btn btn-success btn-sm' action='actions/a_create.php' method='post' type='submit'>Order</button></a>
            </td>
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
    <title>Welcome - <?php echo $adminname; ?></title>
    <?php require_once 'components/boot.php' ?>
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
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-light shadow mb-5">
        <div class="container-fluid w-75">
            <a class="navbar-brand" href="#">🍳 Ristaurante</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link text-primary" aria-current="page" href="#"><?php echo $adminname . " " . $adminlname ?></a>
                    <a class="nav-link" href="#">Features</a>
                    <a class="nav-link" href="#">Pricing</a>
                    <a class="nav-link" href="#">Contact</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row row-cols-2">
            <div class="col-3">

                <div class="card shadow" style="width: 18rem;">
                    <img src="pictures/<?php echo $adminpic; ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Hi <?php echo $adminname ?></h5>
                        <p class="card-text">Welcome to Ristaurante. Here you can order dishes from a great variety of restaurants in you area!</p>
                        <a href="logout.php?logout" class="btn btn-primary M-1">Sign Out</a>
                        <a href="update.php?id=<?php echo $_SESSION['user'] ?>" class="btn btn-primary">Edit Profile</a>
                    </div>
                </div>
            </div>


            <div class="col-8">
                <div class="manageProduct w-75 mt-3">

                    <p class='h2'>Products</p>
                    <table class='table table-striped shadow'>
                        <thead class='table-success'>
                            <tr>
                                <th>Picture</th>
                                <th>Name</th>
                                <th>price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?= $tbody; ?>
                        </tbody>
                    </table>
                    <div class='mb-3'>
                        <a href="products/create.php"><button class='btn btn-primary' type="button">Add product</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>