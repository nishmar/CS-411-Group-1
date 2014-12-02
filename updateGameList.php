<?php
// Start the session
session_start();
?>

<?php
/**
 * updateGameList.php
 * Refactored from insertGame.php
 *
 * Inserts, updates, or deletes a game from the user's game list.
 * Then redirects user to either search page or profile page.
 *
 * Date: 11/19/14
 * Created by Nishi
 *
 * TO DO:
 *  * if review is different from previously stored, get timestamp, otherwise store same timestamp
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
    echo "Connected successfully";

    return $conn;
}
//Validate inputs
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/*
 * newReview - checks if review submitted is different from one in DB
 */
function newReview($conn, $thisReview, $gameID){

    $profileOwner = $_SESSION["userID"];
    $sql = "SELECT `Review` FROM `gamecache`.`usergames` WHERE `User ID` LIKE '$profileOwner' AND `Game ID` LIKE '$gameID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $prevReview = $row["Review"];
        }
    }
    if (strcmp($prevReview, $thisReview) !== 0)
        return true;
    else
        return false;
}

/*
 * checkUserDB - returns true if game and user id are already found in list
 */
function checkUserDB ($gameID, $conn){

    $userID= $_SESSION["userID"];

    $sql = "SELECT * FROM `gamecache`.`usergames` WHERE `User ID` LIKE '$userID' AND `Game ID` LIKE '$gameID'";
    $data = $conn->query($sql);
    $rows = $data->num_rows;

    if($rows >= 1){
        return true;
    }
    else{
        return false;
    }
}

//Profile link
$user = $_SESSION["userID"];
echo  "<a href='userPageDisplay.php?profileOwner=$user'> Your Profile </a><br>" ;

$conn = connectSQL();

//Retrieve variables
$listChange = test_input($_GET["listChange"]);
$userID = test_input($_SESSION["userID"]);
$gameID = test_input($_GET["gameID"]);

//Retrieve variables depending on condition
if ($listChange=='Add Game'){
    $page = test_input($_GET["pagenum"]);
    $search = test_input($_GET["search_term"]);
}
if ($listChange != 'Delete'){
    $status = test_input($_GET["status"]);
    $rating = test_input($_GET["rating"]);
    $review = test_input($_GET["review"]);
    $timestamp = test_input($_GET["timestamp"]);
}

//Add game to DB
if($listChange=='Add Game') {

    //Check again before that the game is not in DB to avoid double adds from user using back button
    if(!checkUserDB($gameID, $conn)) {
        $sql = "INSERT INTO `gamecache`.`usergames` (`User ID`, `Game ID`,`Status`, `Rating`, `Review`, `Timestamp`)
                VALUES ('$userID','$gameID', '$status', '$rating', '$review', '$timestamp')";

        if ($conn->query($sql) === TRUE) {
            echo "<br> New record created successfully <br>";
        } else {
            echo "<br> Error creating record <br> ";
        }
    }
    else{
        echo "<br>Game is already in your list<br>";
    }

    echo "<a href='search.php?pagenum=$page&search_term=$search&type=game'> Back to search page </a>";
}

//Save changes to DB
else if ($listChange=='Edit'){

    if (checkUserDB($gameID, $conn)) {

        if (newReview($conn, $review, $gameID)) {
            $sql = "UPDATE `gamecache`.`usergames`
        SET `Status`='$status',
        `Rating`='$rating',
        `Review`= '$review',
        `Timestamp` = $timestamp
        WHERE `Game ID`='$gameID' AND `User ID`='$userID'";
        } else {
            $sql = "UPDATE `gamecache`.`usergames`
        SET `Status`='$status',
        `Rating`='$rating'
        WHERE `Game ID`='$gameID' AND `User ID`='$userID'";
        }

        if ($conn->query($sql) === TRUE) {
            echo "<br> Record updated successfully <br>";
        } else {
            echo "<br> Error updating record. <br> ";
        }
    }
    else{
        echo "Game is not in your list";
    }
    echo '<a href=userPageDisplay.php?profileOwner=' . $_SESSION["userID"] . '> Return to profile page </a>' ;
}

//Delete game from DB
else if ($listChange == 'Delete'){

    if (checkUserDB($gameID, $conn)) {
        $sql = "DELETE FROM `gamecache`.`usergames` WHERE `Game ID`='$gameID' AND `User ID`='$userID'";

        if ($conn->query($sql) === TRUE) {
            echo "<br> Deleted <br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    else{
        echo "<br> Game is not in your list <br>";
    }
    echo "<a href='userPageDisplay.php?&profileOwner=" . $_SESSION["userID"] . "'> Return to profile page </a>";
}

$conn->close();

?>
