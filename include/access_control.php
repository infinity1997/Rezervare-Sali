<?php
function checkUserDetails($email, $password)
{
    require 'config/db-config.php';
    $conn = connectToDB();
    $result = NULL;
    $query = "SELECT * FROM utilizatori WHERE Email=\"$email\" AND Parola=\"$password\"";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        //adauga in sesiune nume, prenume, id, email & rol user
        $object = $result->fetch_object();
        $_SESSION["idUser"] = $object->id;
        $_SESSION["lastName"] = $object->nume;
        $_SESSION["firstName"] = $object->prenume;
        $_SESSION["username"] = $object->email;
        $_SESSION["userRole"] = $object->tipUser;
        $result->free();
        return true;
    }
    $result->free();
    return false;
}

function login()
{
    $loginOk = NULL;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset ($_POST["loginAsGuest"])) {
            $processed_email = "maria@mail.com";
            $processed_password = "maria1234";
            echo "1";
        } else {
            $processed_email = $_POST['username'];
            $processed_password = $_POST["password"];
            echo "2";
        }
        //hash the password
        $loginOk = checkUserDetails($processed_email, $processed_password);
        if (true == $loginOk) {
            $path_parts = pathinfo("$_SERVER[REQUEST_URI]");
            $partial_url = $path_parts['dirname'];
            if (isset ($_SESSION["userRole"]) && $_SESSION["userRole"] != "guest") {
                header('Location:' . $partial_url . '/rezervari.php');
            } else {
                header('Location:' . $partial_url . "/index.php");
            }
        } else {
            echo("Bad credentials");
        }
    }
}

function logout()
{
    if (isset($_SESSION["username"])) {
        session_unset();
        session_destroy();
    }
    header("location: login.php");
}
