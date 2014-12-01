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

$conn = connectSQL();

$listChange = $_GET["listChange"];

$userID = $_SESSION["userID"];
$gameID = $_GET["gameID"];
$timestamp = $_GET["timestamp"];

if ($listChange=='Add Game'){
    $page = $_GET["pagenum"];
    $search = $_GET["search_term"];
}

if ($listChange != 'Delete'){
    $status = $_GET["status"];
    $rating = $_GET["rating"];
    $review = $_GET["review"];
}

if($listChange=='Add Game') {
    $sql = "INSERT INTO `gamecache`.`usergames` (`User ID`, `Game ID`,`Status`, `Rating`, `Review`, `Timestamp`)
                VALUES ('$userID','$gameID', '$status', '$rating', '$review', '$timestamp')";

    if ($conn->query($sql) === TRUE) {
        echo "<br> New record created successfully <br>";
        echo "<a href='search.php?pagenum=$page&search_term=$search&type=game'> Back to search page </a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

else if ($listChange=='Edit'){
    $sql = "UPDATE `gamecache`.`usergames`
SET `Status`='$status',
    `Rating`='$rating',
    `Review`= '$review'
WHERE `Game ID`='$gameID' AND `User ID`='$userID'";

    if ($conn->query($sql) === TRUE) {
        echo "<br> Record updated successfully <br>";
        echo "<a href='userPageDisplay.php'> Return to profile page </a>";
    }
    else {
        echo "Error updating record: " . $conn->error;
    }
}

else if ($listChange == 'Delete'){
    $sql = "DELETE FROM `gamecache`.`usergames` WHERE `Game ID`='$gameID' AND `User ID`='$userID'";

    if ($conn->query($sql) === TRUE) {
        echo "<br> Deleted <br>";
        echo "<a href='userPageDisplay.php'> Return to profile page </a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}

?>
