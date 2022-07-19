<?php
session_start();
require_once 'components/db_connect.php';
// if session is not set this will redirect to login page
if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
//if session user exist it shouldn't access dashboard.php
if (isset($_SESSION["user"])) {
    header("Location: home.php");
    exit;
}

$id = $_SESSION['adm'];
$status = 'adm';
$sql = "SELECT * FROM users WHERE status != '$status'";
$sql2 = "SELECT * FROM users WHERE status = '$status'";
$result = mysqli_query($connect, $sql);
$result2 = mysqli_query($connect, $sql2);

//this variable will hold the body for the table
$tbody = '';
$abody = '';
$bbody = '';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $tbody .= "<tr>
            <td><img class='img-thumbnail rounded-circle shadow' src='pictures/" . $row['picture'] . "' alt=" . $row['first_name'] . "></td>
            <td>" . $row['first_name'] . " " . $row['last_name'] . "</td>
            <td>" . $row['date_of_birth'] . "</td>
            <td>" . $row['email'] . "</td>
            <td><a href='update.php?id=" . $row['id'] . "'><button class='btn btn-primary btn-sm' type='button'>Edit</button></a>
            <a href='delete.php?id=" . $row['id'] . "'><button class='btn btn-danger btn-sm m-1' type='button'>Delete</button></a></td>
         </tr>";
    }
} else {
    $tbody = "<tr><td colspan='5'><center>No Data Available </center></td></tr>";
}


$res = mysqli_query($connect, "SELECT * FROM users WHERE id=" . $_SESSION['adm']);
$row2 = mysqli_fetch_array($res, MYSQLI_ASSOC);
$adminpic = $row2['picture'];
$adminname = $row2['first_name'];
$adminlname = $row2['last_name'];

$sql3 = "SELECT * FROM products";
$result3 = mysqli_query($connect, $sql3);
$cbody = ''; //this variable will hold the body for the table
if (mysqli_num_rows($result3)  > 0) {
    while ($row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
        $cbody .= "<tr>
            <td><img class='img-thumbnail shadow' src='pictures/" . $row3['picture'] . "'</td>
            <td>" . $row3['name'] . "</td>
            <td>" . $row3['price'] . "</td>
            <td><a href='products/update.php?id=" . $row3['id'] . "'><button class='btn btn-primary btn-sm' type='button'>Edit</button></a>
            <a href='delete.php?id=" . $row3['id'] . "'><button class='btn btn-danger btn-sm m-1' type='button'>Delete</button></a></td>
            </tr>";
    };
} else {
    $cbody =  "<tr><td colspan='5'><center>No Data Available </center></td></tr>";
}


mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adm-Dashboard</title>
    <?php require_once 'components/boot.php' ?>
    <style type="text/css">
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

        .userImage {
            width: 100px;
            height: auto;
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

        <div class="row gap-2">

            <div class="col col-md-12 col-lg-3">
                <div class="card shadow" style="width: 18rem;">
                    <img src="pictures/<?php echo $adminpic; ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">🔑 Hi <?php echo $adminname ?></h5>
                        <p class="card-text">Welcome to the Admin Dashboard. Her you got all the controls you need.</p>
                        <a href="update.php?id=<?php echo $_SESSION['adm'] ?>" class="btn btn-primary">Edit Profile</a>
                        <a class="btn btn-success m-1" href="products/index.php">Products</a>
                        <a class="btn btn-danger" href="logout.php?logout">Sign Out</a>
                    </div>
                </div>
            </div>

            <div class="col col-md-12 col-lg-8">

                <div class="row">

                    <div class="col col-md-12 col-lg-12 mb-5">
                        <p class='h2'>🙋 Users</p>
                        <table class='table table-striped shadow'>
                            <thead class='table-success'>
                                <tr>
                                    <th>Picture</th>
                                    <th>Name</th>
                                    <th>Date of birth</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?= $tbody ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col col-md-12 col-lg-12 mb-5">
                        <div class="manageProduct w-75 ">

                            <p class='h2'>🌮 Dishes</p>
                            <table class='table table-striped shadow'>
                                <thead class='table-success'>
                                    <tr>
                                        <th>Picture</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?= $cbody; ?>
                                </tbody>
                            </table>                            
                            <div class='mb-3'>
                                <a href="products/create.php"><button class='btn btn-success' type="button">Add product</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>