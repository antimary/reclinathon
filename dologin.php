<?php

session_start();
$_SESSION = array();
session_destroy();
session_start();
$_SESSION = array();

include "include/connect.php";

$LoginSuccessful = FALSE;
$user = $_POST["username"];
$password = $_POST["password"];
$message = "";

if ($user == "" || $password == "")
{
    $message="Please enter all fields.<br />";
}
else
{
    $message = "Login failed.  Please try again.<br />";

    $query = "SELECT * FROM RECLINEE WHERE UserName = '" . $user . "' and PasswordHash = '" . sha1($password) . "'";
    $result = mysql_query($query);
    if ($result && mysql_num_rows($result) > 0)
    {
        $LoginSuccessful = TRUE;
        $row = mysql_fetch_assoc($result);
        $_SESSION["ReclineeID"] = $row["ReclineeID"];
    }
}

if (!$LoginSuccessful)
{
    $URL = "http://" . $_SERVER['SERVER_NAME'] . "/login.php?username=" . $user . "&message=" . $message;
}
else
{ 
    $URL = "http://" . $_SERVER['SERVER_NAME'] . "/rtt/ControlCenter.php";
}


header ("Location: $URL");

