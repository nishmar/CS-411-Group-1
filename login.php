<?php
ob_start();
session_start();

function connectSQL(){
//MySQL default credentials
    $servername = "localhost";
    $username = "root";
    $password = "";

// Create connection
    $conn = new mysqli($servername, $username, $password);

// Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //echo "Connected successfully";

    return $conn;
}

$username = $_POST['username'];
$password = $_POST['password'];

$conn = connectSQL();

$username = mysql_real_escape_string($username);

$query = "SELECT *
        FROM `gamecache`.`userlist`
        WHERE `User ID` = '$username'";

$result = $conn->query($query);

if($result->num_rows == 0) // User not found. So, redirect to login_form again.
{
    header('Location: loginForm.php');
}
else {

    while ($row = $result->fetch_assoc()) {
        $DBuser= $row["User ID"] ;
        $DBpass= $row["Password"];

    }

    if ($password != $DBpass) // Incorrect password. So, redirect to login_form again.
    {
        header('Location: loginForm.php');
    }
    else { // Redirect to home page after successful login.

        $_SESSION['userID'] = $DBuser;

        header('Location: main.php');
    }
}
?>
