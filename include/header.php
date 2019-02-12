<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="Ro-ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ocupare săli AC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" href="include/jquery.timepicker.css" />
    <script src="include/jquery.timepicker.js"></script>
    <link rel="stylesheet" href="include/jquery.timepicker.min.css" />
    <script src="include/jquery.timepicker.min.js"></script>            

</head>
<body>
<header>
    <div class="container">
        <div class="row">
            <div class="col-lg-2"><img src="img/logo_ac_iasi.png" alt="" width="120" height="120"></div>
            <div class="col-lg-8"><a href="index.php">Rezervare săli <br>Facultatea de Automatică și Calculatoare </a>
            </div>
            <div class="col-lg-2"><img src="img/logo_tuiasi.gif" alt="" width="85" height="120"></div>
        </div>
    </div>
    <div id="logo"><img src="img/motto_alb_50.png" width="auto" height="30">
        <div>
</header>
<nav>
    <div class="container">
        <div class="row">
            <div class="col col-lg-7">
                <button class="btn" onclick="window.location.href='index.php'"><i class="fa fa-home"></i></button>
                <a href="ocupare.php" class="top-nav">Ocupare săli</a>
                <a href="program.php" class="top-nav">Program cadru didactic</a>
                <a href="orar.php" class="top-nav">Orar grupe</a>
            </div>
            <div class="col col-lg-5 text-right">
                <?php
                if (isset($_SESSION["username"])) {
                    if ($_SESSION["userRole"] == "admin") {
                        ?>
                        <a href="admin.php" class="top-nav">Import orar</a>
                        <?php
                    }
                    if ($_SESSION["userRole"] != "guest") {
                        ?>
                        <a href="rezervari.php" class="top-nav">Rezervare săli</a>
                        <?php
                    }
                    ?>
                    <button class="btn" onclick="window.location.href='logout.php'">Log out</button>
                    <?php
                } else { ?>
                    <button class="btn" onclick="window.location.href='login.php'">Login</button>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="text-right">
        <div class="totMenu">
            <div class="cont" id="butonnull" onclick="myFunction(this)">
                <div class="bar1"></div>
                <div class="bar2"></div>
                <div class="bar3"></div>
            </div>
            <div id="menu">
                <a href="index.php">Acasă</a>
                <a href="ocupare.php" class="top-nav">Ocupare săli</a>
                <a href="program.php" class="top-nav">Program cadru didactic</a>
                <a href="orar.php" class="top-nav">Orar grupe</a>
                <?php
                if (isset($_SESSION["username"])) {
                    if ($_SESSION["userRole"] == "admin") {
                        ?>
                        <a href="admin.php">Import orar</a>
                        <?php
                    }
                    ?>
                    <a href="rezervari.php">Rezervare săli</a>
                    <a href="logout.php">Log out</a>
                    <?php
                } else { ?>
                    <a href="login.php">Log in</a>
                    <?php
                }
                ?>
            </div>
        </div>
        <script>
            function myFunction(x) {
                x.classList.toggle("change");

                var y = document.getElementById("menu");
                if (y.style.display === "block") {
                    y.style.display = "none";
                } else {
                    y.style.display = "block";
                }
            }
        </script>
    </div>
</nav>
<script>
    var page = window.location.pathname.split("/");
    page = page[page.length - 1];
    $("nav a[href='" + page + "']").addClass("current-page");
    $("nav #menu a[href='" + page + "']").addClass("current-page-menu-restrins");
</script>