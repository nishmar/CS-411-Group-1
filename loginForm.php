<?php include 'navbar.php' ;?>

<html>


<div class="container padding-top">
    <div class="row">
        <div class="col-sm-4">
            </div>

        <div class="col-sm-4">


<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login Form</title>
</head>

<body>

<form id="form1" name="form1" method="post" action="login.php">
    <div class="form-group">
        <label for="username">Username: </label>
        <input input type="text" name="username" id="username" required pattern="[a-zA-Z0-9]*$" class="form-control">
    </div>
    <div class="form-group">
        <label for="pwd">Password:</label>
        <input type="password" name="password" id="password" required pattern="[a-zA-Z0-9]*$" class="form-control" >
    </div>
    <button type="submit" name="button" id="button" value="Submit" class="btn btn-default">Submit</button>
</form>


</body>
        </div>

        <div class="col-sm-4">
        </div>

    </div>

</html>
