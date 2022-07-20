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
$res = mysqli_query($connect, "SELECT * FROM $tableUser WHERE id=" . $_SESSION['user']);
$row = mysqli_fetch_array($res, MYSQLI_ASSOC);
$adminpic = $row['picture'];
$adminname = $row['first_name'];
$adminlname = $row['last_name'];



$sql = "SELECT * FROM $tableProducts";
$result = mysqli_query($connect, $sql);
$tbody = ''; //this variable will hold the body for the table
$orderBtn = '';
if (mysqli_num_rows($result)  > 0) {
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        if ($row['available'] == 1){
            $available = "✅";
            $orderBtn =  "<td class='text-center'></td>";
        }else{
            $available = "⛔️";
            $orderBtn =
            "<td class='text-center align-middle'><a href='products/actions/a_order.php?id=$row[id]' class='btn btn-success btn-sm shadow amatic' name='order' type='submit'>Order</a>
            </td>";        
            $tbody .= "<tr>
            <td class='text-center align-middle'><img class='img-thumbnail' src='pictures/" . $row['picture'] . "'</td>
            <td class='text-center align-middle'>" . $row['name'] . "</td>
            <td class='text-center align-middle'>" . $row['price'] . "</td>
            <td class='text-center align-middle'>" . $available . "</td>
            $orderBtn
            </td>
            </tr>";
        }

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
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@700&family=Square+Peg&display=swap" rel="stylesheet">
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
    <nav class="navbar navbar-expand-lg bg-light shadow">
        <div class="container-fluid w-75">
            <a class="navbar-brand peg-xs" href="#">🍳 Ristaurante</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link text-primary amatic-xs" aria-current="page" href="#"><?php echo $adminname . " " . $adminlname ?></a>
                    <a class="nav-link amatic-xs" href="#">Features</a>
                    <a class="nav-link amatic-xs" href="#">Pricing</a>
                    <a class="nav-link amatic-xs" href="#">Contact</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="hero mb-5">
        <div class="container w-50 justify-content-center p-5 inside-hero">
            <h1 class="edu text-center text-success peg">Welcome to Ristaurante</h1>
            <!-- <h5 class="edu text-center text-white">Here you can easily manage your products.</h5>
            <h5 class="edu text-center text-white">You can add, edit & delete products within a few clicks! </h5> -->
        </div>
    </div>

    <div class="container">

        <div class="row gap-3 d-flex justify-content-between">

            <div class="col col-md-12 col-lg-3">
                <div class="card shadow" style="width: 18rem;">
                    <img src="pictures/<?php echo $adminpic; ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title amatic">Hi <?php echo $adminname ?>,</h5>
                        <p class="card-text amatic">Welcome to Ristaurante. Here you can order dishes from a great variety of restaurants in you area!</p>
                        <a href="logout.php?logout" class="btn btn-primary m-1 shadow amatic">Sign Out</a>
                        <a href="update.php?id=<?php echo $_SESSION['user'] ?>" class="btn btn-primary shadow amatic">Edit Profile</a>
                    </div>
                </div>
            </div>


            <div class="col col-md-12 col-lg-8">
                <div class="manageProduct w-75">

                    <p class='h2 peg-sm'>Products</p>
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
                    <div class='mb-3'>
                        <a href="products/create.php"><button class='btn btn-primary shadow amatic' type="button">Add product</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>