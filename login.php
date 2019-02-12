<?php
include "include/header.php";
if (!isset($_SESSION["username"])) {
    require 'include/access_control.php';
    login();
    ?>
    <div class="container">
        <div class="row">
            <div class="col col-lg-12">
                <h1>Login</h1>
                <form action="login.php" method="POST">
                    <label for="username">Email: </label>
                    <input type="text" id="username" name="username">
                    <br/>
                    <label for="password">Password: </label>
                    <input type="password" id="password" name="password">
                    <br/>
                    <input type="submit" value="login" style="width: 147px;">
                    <input type="submit" name="loginAsGuest" value="Login as guest" style="margin-left: 10px; text-transform: none;">
                </form>
            </div>
        </div>
    </div>
    <?php
} else {
    echo 'You are already log in.';
}
include "include/footer.php";
?>