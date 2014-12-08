<?php
// Start the session
session_start();
include 'navbar.php';
?>

<!DOCTYPE html>
<html>

<div class="container padding-top">
    <div class="row">
        <div class="col-sm-4">
        </div>

        <div class="col-sm-4">

<head lang="en">
    <meta charset="UTF-8">
    <title>Search</title>
</head>

<h1>Search</h1><br>
<form action="search.php" method="get" >
    <input type="hidden" name="pagenum" value="1">
    Search for: <input type="text" name="search_term"><br>
    <h2>Search in </h2>
    <select name='type'>
        <option value='game'> games </option>
        <option value='user'> users </option>
    </select>
    <input type="submit">
</form>
            </div>
        <div class="col-sm-4">
        </div>
        </div>

<body>

</body>
</html>
