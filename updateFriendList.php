<?php
// Start the session
session_start();
?>

<?php
/**
 * updateFriendList.php
 *
 * Inserts or deletes a user from the user's friend list.
 * Then redirects user to either search page or profile page.
 *
 * Date: 11/30/14
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
$friendID = $_GET["friendID"];

if ($listChange=='Add'){
    $page = $_GET["pagenum"];
    $search = $_GET["search_term"];
}

if($listChange=='Add') {
    $sql = "INSERT INTO `gamecache`.`userfriends` (`User ID`, `Friend ID`)
                VALUES ('$userID','$friendID')";

    if ($conn->query($sql) === TRUE) {
        echo "<br> New record created successfully <br>";
        echo "<a href='search.php?pagenum=$page&search_term=$search&type=user'> Back to search page </a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

else if ($listChange == 'Delete'){
    $sql = "DELETE FROM `gamecache`.`userfriends` WHERE `Friend ID`='$friendID' AND `User ID`='$userID'";

    if ($conn->query($sql) === TRUE) {
        echo "<br> Deleted <br>";
        echo "<a href='userPageDisplay.php?profileOwner=$userID'> Return to profile page </a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}

?>
