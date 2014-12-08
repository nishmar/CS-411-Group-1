<?php
// Start the session
session_start();
include 'navbar.php';
?>

<div class="container">
    <div class="jumbotron">
        <?php

        if(empty($_SESSION["userID"])){
            $greeting = "<h1>Welcome to GG: Good Game!</h1><br>";
            $message = "<p>Please register or log in.</p>
<a href='loginForm.php' class='btn btn-info btn-lg'><span class='glyphicon glyphicon-log-in'></span> Log In</a>
            <a href='registration.php' class='btn btn-info btn-lg'><span class='glyphicon glyphicon-user'></span> Register</a>";
        }
        else{
            $userID= $_SESSION["userID"];
            $greeting = "<h1>Welcome ".$userID."!</h1><br>";
            $message = "<p> Add a game or user to your list.</p>
<a href='searchForm.php' class='btn btn-info btn-lg'><span class='glyphicon glyphicon-search'></span> Search</a>";
        }

        echo $greeting;
        echo $message;
        ?>

    </div>

    <div class="row">
        <div class="col-md-3">
            <p>Search for games, add them to your list.</p>
        </div>
        <div class="col-md-3">
            <p>Track your progress.</p>
        </div>
        <div class="col-md-3">
            <p>Review and rate.</p>
        </div>
        <div class="col-md-3">
            <p>Follow your friends.</p>
        </div>
        <div class="clearfix visible-lg"></div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>
