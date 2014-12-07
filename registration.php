<!DOCTYPE html>

<html class="en-us nojs us en amr" lang="en-US">
<head>
    <meta name="viewport" content="width=1024" />
    <title>Sign in  - GoodGame (U.S.)</title>




    <!--[if gte IE 9]> FOR THE HEADER <!-->
    <link rel="stylesheet" href="https://store.storeimages.cdn-apple.com/4234/store.apple.com/rs/rel/base.css" media="screen, print" />
    <!--<![endif]-->
    <!--[if gte IE 9]><!-->
    <link rel="stylesheet" href="https://store.storeimages.cdn-apple.com/4234/store.apple.com/rs/rel/signin.css" media="screen, print" />
    <!--<![endif]-->




</head>

<body class="interim login">



    <div id="page">


        <nav aria-label="Global Navigation" role="navigation" lang="en-us" class="globalheader stack-item gh-selected-tab-store" id="globalheader">
            <div id="gh-content" class="gh-content">

                <div class="gh-nav">
                    <div class="gh-nav-view stack-item-body" >
                        <ul id="gh-nav-list" class="gh-nav-list">

                            <li class="gh-tab">
                                <BR>
                                <a href="login.html" class="gh-tab-link"><span class="gh-tab-inner"><FONT COLOR="#FFFFFF">Login</FONT></span></span></a>
                            </li>

                            <li class="gh-tab">
                                <BR>
                                <a class="gh-tab-link" href="login.html" ><span class="gh-tab-inner"><FONT COLOR="#FFFFFF">Home</FONT></span></span></a>
                            </li>

                            <li class="gh-tab">
                                <BR>
                                <a href="login.html" class="gh-tab-link"><span class="gh-tab-inner"><FONT COLOR="#FFFFFF">Search</FONT></span></span></a>
                            </li>
                            <li class="gh-tab">
                                <BR>
                                <a href="registration.php" class="gh-tab-link"><span class="gh-tab-inner"><FONT COLOR="#FFFFFF">Register</FONT></span></span></a>
                            </li>


                        </ul>
                    </div>
                </div>
            </div>
        </nav>


        <div class="store-header clearfix">
            <div class="masthead clearfix">
                <div class="masthead-title">
                    <font size="5" face="Fantasy"><font size="6">G</font>ood &nbsp  <font size="6">G</font>ame</font>

                </div>

            </div>
        </div>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div id="container">
            <div role="main" class="accountbox">
                <div id="account-page-header">Account Registration</div>
                <div id="account-content" class="content clearfix">
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
                            if (!preg_match("/^[a-zA-Z0-9]*$/", $name)) {
                                $nameErr = "Only letters and numbers allowed";
                                $valid= false;
                            }
                            if (strlen($name) > 100)
                            {
                                $valid = false;
                                $nameErr = "Username cannot exceed 100 characters";
                            }
                        }
                        if (empty($_POST["password"])) {
                            $passwordErr = "Password is required";
                            $valid= false;
                        } else {
                            $password = test_input($_POST["password"]);
                            //$valid= true;
                            if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
                                $passwordErr = "Only letters and numbers allowed";
                                $valid= false;
                            }
                            if (strlen($password) <=4)
                            {
                                $valid = false;
                                $passwordErr = "Password should be longer than 4 characters";
                            }
                            if (strlen($password) > 100)
                            {
                                $valid = false;
                                $passwordErr = "Password cannot exceed 100 characters";
                            }
                        }
                        if (empty($_POST["email"])) {
                            $emailErr = "Email is required";
                            $valid= false;
                        } else {
                            $email = test_input($_POST["email"]);
                            //$valid= true;
                            // check if e-mail address is well-formed
                            if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $email)) {
                                $emailErr = "Invalid email used";
                                $valid= false;
                            }
                            if (strlen($email) > 100)
                            {
                                $valid = false;
                                $emailErr = "Email cannot exceed 100 characters";
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
                    <font size="3">
                    <p><span class="error"><font color="#FF0000">* required field.</font></span></p>




                        <br>
                        Username: <input type="text" name="name" value="<?php echo $name;?>">
                        <span class="error"><font color="#FF0000">*</font><?php echo $nameErr;?></span>
                        <br><br>
                        Password: <input type="password" name="password" value="<?php echo $password;?>">
                        <span class="error"><font color="#FF0000">*</font><?php echo $passwordErr;?></span>
                        <br><br>
                        Email: <input type="text" name="email" value="<?php echo $email;?>">
                        <span class="error"><font color="#FF0000">*</font><?php echo $emailErr;?></span>
                        <br><br>
                    </font>


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


                </div>
                <div class="cart-navigation">
                    <input type="submit" name="submit" value="Submit">
                </div>
            </div>
            </form>



        </div>
    </div>




</body>

</html>



