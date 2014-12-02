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
//Validate inputs
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function checkFriendDB ($friendID, $conn){

    $userID= $_SESSION["userID"];

    $sql = "SELECT * FROM `gamecache`.`userfriends` WHERE `User ID` LIKE '$userID' AND `Friend ID` LIKE '$friendID'";
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
$userID = $_SESSION["userID"];
$friendID = test_input($_GET["friendID"]);

//If adding, retrieve variables
if ($listChange=='Add'){
    $page = test_input($_GET["pagenum"]);
    $search = test_input($_GET["search_term"]);
}

//Add user to DB
if($listChange=='Add') {
    if (!checkFriendDB($friendID, $conn)) {
        $sql = "INSERT INTO `gamecache`.`userfriends` (`User ID`, `Friend ID`)
                VALUES ('$userID','$friendID')";

        if ($conn->query($sql) === TRUE) {
            echo "<br> New record created successfully <br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    else{
        echo "<br>User is already in your friend list<br>";
    }
    echo "<a href='search.php?pagenum=$page&search_term=$search&type=user'> Back to search page </a>";
}

//Delete user from DB
else if ($listChange == 'Delete'){

    if (checkFriendDB($friendID, $conn)) {

        $sql = "DELETE FROM `gamecache`.`userfriends` WHERE `Friend ID`='$friendID' AND `User ID`='$userID'";

        if ($conn->query($sql) === TRUE) {
            echo "<br> Deleted <br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        echo "<a href='userPageDisplay.php?profileOwner=$userID'> Return to profile page </a>";
    }
    else{
        "<br> User is not in your friend list <br>";
    }
}

$conn->close();

?>
