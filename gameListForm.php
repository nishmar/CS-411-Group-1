<?php
// Start the session
session_start();
include 'navbar.php';
?>

<?php
/**
 * gameListForm.php
 * Refactored from addGame.php
 *
 * Displays form for storing user information about games.
 *
 * Created by Nishi
 *
 * TO DO:
 * put cancel button : case add, case edit
 */

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
//Validate inputs
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$conn = connectSQL();

//Retrieves variables
$listChange = test_input($_GET["listChange"]);
$gameID = test_input($_GET["gameID"]);
$profileOwner = test_input($_SESSION["userID"]);

//Gets necessary variables depending on condition : Add or edit
if ($listChange=='Add Game'){
    $title = test_input($_GET["title"]);
    $page = test_input($_GET["pagenum"]);
    $search = test_input($_GET["search_term"]);
}
else if ($listChange=='Edit') {
    $title = test_input($_GET["title"]);
    $sql = "SELECT * FROM `gamecache`.`usergames` WHERE `User ID` LIKE '$profileOwner' AND `Game ID` LIKE '$gameID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $status = $row["Status"];
            $rating = $row["Rating"];
            $review = $row["Review"];
        }
    }
}

//Profile link
$user = $_SESSION["userID"];
?>

<!-- Form -->
<html>

<div class="container padding-top">
    <div class="row">
        <div class="col-sm-3">
        </div>

        <div class="col-sm-6">
<head lang='en'>
    <meta charset='UTF-8'>
    <title>Add Game to List</title>
</head>

<h1>Add <?php echo $title?> to your list:</h1><br>
<form action='updateGameList.php' method='get'>

<h2>Game Progress </h2>

    <select name='status'>
        <?php if($listChange=="Edit") {
            echo '<option value=' .$status .'>'. $status .'</option>';
        }  ?>
        <option value='playing'>Currently Playing</option>
        <option value='completed'>Completed</option>
        <option value='dropped'>Dropped</option>
        <option value='plan'>Want to Play</option>
     </select>
    <br><br>

    <h2> Rating </h2>
    <select name='rating'>
        <?php if ($listChange=="Edit") {
            echo '<option value=' .$rating .'>'.$rating.'</option>';
        } ?>
        <option value=''></option>
        <option value='*'>* - Hated It</option>
        <option value='**'>** - It was OK</option>
        <option value='***'>*** - Liked It </option>
        <option value='****'>**** - Loved It</option>
        <option value='*****'>***** - The Best!</option>
    </select>
    <br><br>

    <h2> Review </h2>
    <textarea name='review' rows='5' cols='40'><?php if($listChange=="Edit"){echo $review;}?></textarea>
    <br><br>

    <input type='hidden' name='gameID' value='<?php echo ($gameID) ;?>'>
    <input type='hidden' name='timestamp' value='<?php echo (time()); ?>'>

    <?php if ($listChange =='Add Game') {
        echo "<input type='hidden' name='listChange' value='Add Game'>
        <input type='hidden' name='search_term' value='$search'>
        <input type='hidden' name='pagenum' value='$page'>";
    }
    else if ($listChange=='Edit'){
        echo "<input type='hidden' name='listChange' value='Edit'>";
    }?>
    <input type='submit'>

</form>

<!-- Cancel add or edit. Add: return to search, Edit: return to profile  -->
<form action= '<?php if ($listChange=='Edit') echo "userPageDisplay.php"; elseif ($listChange=='Add Game') echo "search.php"; ?>' >

    <? if($listChange=='Edit') {
        echo "<input type='hidden' name='profileOwner' value='$profileOwner'>";
    }
    else if ($listChange=='Add Game'){
        echo "
    <input type='hidden' name='pagenum' value='$page' >
    <input type='hidden' name='search_term' value='$search' >
    <input type='hidden' name='type' value='game'>";
    }
    ?>
    <input type='submit' value='Cancel'>
</form>

            </div>
        <div class="col-sm-3">
        </div>
</div>

</html>

<?php
$conn->close();
?>

