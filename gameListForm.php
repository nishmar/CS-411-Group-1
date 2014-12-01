<?php
// Start the session
session_start();
?>

<?php
/**
 * gameListForm.php
 * Refactored from addGame.php
 *
 * Displays form for storing user information about games.
 *
 * Created by Nishi
 */

// Get game title and ID
//Find a way to pass on the ID as well

$listChange = $_GET["listChange"];
$gameID = $_GET["gameID"];

if ($listChange=='Add Game'){
    $title = $_GET["title"];
    $page = $_GET["pagenum"];
    $search = $_GET["search_term"];
}
?>

<html>
<head lang='en'>
    <meta charset='UTF-8'>
    <title>Add Game to List</title>
</head>

<h1>Add Game</h1><br>
<form action='updateGameList.php' method='get'>

<h2>Game Progress </h2>

    <select name='status'>
        <option value='playing'>Currently Playing</option>
        <option value='completed'>Completed</option>
        <option value='dropped'>Dropped</option>
        <option value='plan'>Want to Play</option>
     </select>
    <br><br>

    <h2> Rating </h2>
    <select name='rating'>
        <option value=''></option>
        <option value='1s'>* - Hated It</option>
        <option value='2s'>** - It was OK</option>
        <option value='3s'>*** - Liked It </option>
        <option value='4s'>**** - Loved It</option>
        <option value='5s'>***** - The best!</option>
    </select>
    <br><br>

    <h2> Review </h2>
    <textarea name='review' rows='5' cols='40'></textarea>
    <br><br>


    <input type='hidden' name='gameID' value='<?php echo ($gameID)?>'>
    <input type='hidden' name='timestamp' value='<?php echo (time()) ?>'>

    <?php if ($listChange =='Add Game') {
        echo "<input type='hidden' name='listChange' value='Add Game'>
        <input type='hidden' name='search_term' value='$search'>
        <input type='hidden' name='pagenum' value='$page'>";
    }
    else if ($_GET["listChange"]=='Edit'){
        echo "<input type='hidden' name='listChange' value='Edit'>";
    }?>
    <input type='submit'>

</form>

<!-- Add pagenum and search_term fields to allow user to return to search page if cancels -->
<form action='search.php'><input type='submit' value='Cancel'</form>
<body>
</body>




