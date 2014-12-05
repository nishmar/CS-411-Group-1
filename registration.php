<!DOCTYPE HTML>
<html>
<head>
    <style>
        .error {color: #FF0000;}
    </style>
</head>
<body>

<?php
// define variables and set to empty values
$nameErr = $passwordErr = $emailErr = "";
$name = $password = $email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
        $valid= false;
    }
    else {
        $name = test_input($_POST["name"]);
        $valid= true;
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z0-9_ ]*$/", $name)) {
            $nameErr = "Only letters and numbers allowed";
            $valid= false;
        }
    }
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
        $valid= false;

    } else {
        $password = test_input($_POST["password"]);
        //$valid= true;
        // check if e-mail address is well-formed
        if (!preg_match("/^[a-zA-Z0-9_ ]*$/", $password)) {
            $passwordErr = "Only letters and numbers allowed";
            $valid= false;

        }
    }
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
        $valid= false;

    } else {
        $email = test_input($_POST["email"]);
        //$valid= true;

        if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email)) {
            $emailErr = "Invalid characters used";
            $valid= false;
        }
    }
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<h2>Account Registration</h2>
<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

    Username: <input type="text" name="name" value="<?php echo $name;?>">
    <span class="error">* <?php echo $nameErr;?></span>

    <br><br>
    Password: <input type="password" name="password" value="<?php echo $password;?>">
    <span class="error">* <?php echo $passwordErr;?></span>
    <br><br>
    Email: <input type="text" name="email" value="<?php echo $email;?>">
    <span class="error">*<?php echo $emailErr;?></span>
    <br><br>

    <input type="submit" name="submit" value="Submit">

    <?php

    if (isset($valid)){

        if ($valid) {
            $servername = "localhost";
            $username = "root";
            $sqlpassword = "";
            $conn = mysqli_connect($servername, $username, $sqlpassword);
            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }
            $sql = "INSERT INTO `gamecache`.`userlist` (`User ID`, `Password`, `Email`)
  VALUES ('$name','$password','$email')";

            if ($conn->query($sql) === TRUE) {
                echo "<br> Successfully registered. Redirecting to login page... <br>";
                header('Refresh: 5; url=login.html');
            }
            else {
            echo "<script> alert('Username or e-mail is already in use.')</script>";
            }
        }
    }

    ?>
</form>
</form>

</body>
</html>
