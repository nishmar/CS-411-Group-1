<?php
// Start the session
session_start();
$_SESSION["userID"] = "test2";
?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Game Search</title>
</head>

<h1>Search</h1><br>
<form action="search.php" method="get" >
    <input type="hidden" name="pagenum" value="1">
    Search for a game: <input type="text" name="search_term"><br>
    <h2> Search in </h2>
    <select name='type'>
        <option value='game'> games </option>
        <option value='user'> users </option>
    </select>

    <input type="submit">
</form>


<body>

</body>
</html>
