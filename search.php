<?php
// Start the session
session_start();
?>

<?php

/*
 * Database Search Prototype -
 * Created by Proj Group 1
 * Updated by Nishi 11/14/14 to always display from DB and added functions.
 * 11/17/14 : Include pagination using this tutorial :
 * http://php.about.com/od/phpwithmysql/ss/php_pagination.htm
 */

//`````````````````````````````````````````````````````````
// <input type="hidden" name="pagenum" value="1">

/*
 * connectSQL - returns SQL connection
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

/*
 * displayGames - searches within DB to display results
 */
function displayGames($searchTerm, $conn)
{
    $searchWild = '%' . $searchTerm . '%'; //add wildcards

    $pagenum = $_GET["pagenum"];

    //This checks to see if there is a page number. If not, it will set it to page 1
    if (!(isset($pagenum)))
    {
        $pagenum = 1;
    }

    //Search in game cache DB
    $data = $conn->query("SELECT name, image, description, aliases FROM `gamecache`.`gamelist` WHERE name LIKE '$searchWild' ");

    //Here we count the number of results
    $rows = $data->num_rows;

    if($rows > 0) { //if in DB, display

        //This is the number of results displayed per page
        $page_rows = 5;

        //This tells us the page number of our last page
        $last = ceil($rows/$page_rows);

        //this makes sure the page number isn't below one, or more than our maximum pages
        if ($pagenum < 1)
        {
            $pagenum = 1;
        }
        elseif ($pagenum > $last)
        {
            $pagenum = $last;
        }

        //This sets the range to display in our query
        $max = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;


        $sql = "SELECT ID, name, image, description, aliases FROM `gamecache`.`gamelist` WHERE name LIKE '$searchWild' $max "; //SELECT name, image, description, aliases
        $result = $conn->query($sql);

        echo $result->num_rows;

        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<br>";
            echo $row["name"] . "<br>";
            echo "<img src='", $row["image"], "' alt= 'game picture'>" . "<br>";
            echo $row["description"] . "<br>";
            echo $row["aliases"] . "<br>";

            //If game is not already in user list, show add button
            if(!checkUserDB($row["ID"], $conn)) {
                addGameButton($row["name"], $row["ID"], $searchTerm, $pagenum);
            }
        }

        // This shows the user what page they are on, and the total number of pages
        echo " --Page $pagenum of $last-- <p>";
        // First we check if we are on page one. If we are then we don't need a link to the previous page or the first page so we do nothing. If we aren't then we generate links to the first page, and to the previous page.
        if ($pagenum == 1)
        {
        }
        else
        {
            echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=1&search_term=$searchTerm&type=game'> <<-First</a> ";
            echo " ";
            $previous = $pagenum-1;
            echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=$previous&search_term=$searchTerm&type=game'> <-Previous</a> ";
        }
        //just a spacer
        echo " ---- ";
        //This does the same as above, only checking if we are on the last page, and then generating the Next and Last links
        if ($pagenum == $last)
        {
        }
        else {
            $next = $pagenum+1;
            echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=$next&search_term=$searchTerm&type=game'>Next -></a> ";
            echo " ";
            echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=$last&search_term=$searchTerm&type=game'>Last ->></a> ";
        }

    }
    else {
        $found = cacheGames($searchTerm, $conn);

        if ($found) {
            displayGames($searchTerm, $conn);
        }
        else{
            echo "<br> No results found";;
        }
    }
}

/*
 * cacheGames - retrieve results from API and store in DB
 */
function cacheGames ($search, $conn)
{
    $KEY = '21a3347dc2f2f1848aa95e206fe41e2e05fd93c0';     //API Key

    $url = 'http://www.giantbomb.com/api/search/?api_key=' . $KEY . '&format=xml&query=' . $search . '&resources=game';

    $xml = simplexml_load_file($url);

    //print_r($xml); // Printing XML file contents

    $count =0;

    foreach ($xml->results->game as $game) {

        $image = $game->image->thumb_url; // Solves double -> problem

        $sql = "INSERT INTO `gamecache`.`gamelist` (`ID`, `Name`, `Image`, `Description`, `Aliases`, `Release Date`)
                VALUES ('$game->id', '$game->name', '$image', '$game->deck', '$game->aliases', '$game->original_release_date')";
        if ($conn->query($sql) === TRUE) {
            echo "<br> New record created successfully <br>";
            $count++;
        }
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

    }
    if ($count > 0) {
        return true;
    }
    else{
        return false;
    }

}

/*
 * addGameButton - Display link to form where users can enter information to store in personal game list
 */
function addGameButton ($gameName, $gameID, $search, $pagenum){
    //Make pop-up window later

    echo "<form action='gameListForm.php'>
            <input type='hidden' name='title' value='$gameName'>
            <input type='hidden' name='gameID' value='$gameID'>
            <input type='hidden' name='search_term' value='$search'>
            <input type='hidden' name='pagenum' value='$pagenum'>
           <input type='submit' name='listChange' value='Add Game'>
           </form>";

}

/*
 * addFriendButton - Display link to form where users can enter information to store in personal game list
 */
function addFriendButton ($userID, $search, $pagenum){
    //Make pop-up window later

    echo "<form action='updateFriendList.php'>
            <input type='hidden' name='friendID' value='$userID'>
            <input type='hidden' name='search_term' value='$search'>
            <input type='hidden' name='pagenum' value='$pagenum'>
           <input type='submit' name='listChange' value='Add'>
           </form>";

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

function isUser($userID){

    $thisUser= $_SESSION["userID"];
    if ($thisUser==$userID) {
        return true;
    }
    else{
        return false;
    }
}


function displayUsers($searchTerm, $conn){

    $searchWild = '%' . $searchTerm . '%'; //add wildcards

    $pagenum = $_GET["pagenum"];

    //This checks to see if there is a page number. If not, it will set it to page 1
    if (!(isset($pagenum)))
    {
        $pagenum = 1;
    }

    //Search in game cache DB
    $data = $conn->query("SELECT * FROM `gamecache`.`userlist` WHERE `User ID` LIKE '$searchWild' ");

    //Here we count the number of results
    $rows = $data->num_rows;

    if($rows > 0) { //if in DB, display

        //This is the number of results displayed per page
        $page_rows = 5;

        //This tells us the page number of our last page
        $last = ceil($rows/$page_rows);

        //this makes sure the page number isn't below one, or more than our maximum pages
        if ($pagenum < 1)
        {
            $pagenum = 1;
        }
        elseif ($pagenum > $last)
        {
            $pagenum = $last;
        }

        //This sets the range to display in our query
        $max = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;


        $sql = "SELECT * FROM `gamecache`.`userlist` WHERE `User ID` LIKE '$searchWild' $max ";
        $result = $conn->query($sql);

        echo $result->num_rows;

        // output data of each row
        while ($row = $result->fetch_assoc()) {

            $userID = $row["User ID"];

            if(!isUser($userID)) {
                echo "<br>";

                echo " <a href='userPageDisplay.php?profileOwner=$userID'> $userID </a> ";

                //If user is not already in user list, show add button
                if (!checkFriendDB($userID, $conn)) {
                    addFriendButton($userID, $searchTerm, $pagenum);
                }
            }
        }

        echo "<br>";
        // This shows the user what page they are on, and the total number of pages
        echo " --Page $pagenum of $last-- <p>";
        // First we check if we are on page one. If we are then we don't need a link to the previous page or the first page so we do nothing. If we aren't then we generate links to the first page, and to the previous page.
        if ($pagenum == 1)
        {
        }
        else
        {
            echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=1&search_term=$searchTerm&type=user'> <<-First</a> ";
            echo " ";
            $previous = $pagenum-1;
            echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=$previous&search_term=$searchTerm&type=user'> <-Previous</a> ";
        }
        //just a spacer
        echo " ---- ";
        //This does the same as above, only checking if we are on the last page, and then generating the Next and Last links
        if ($pagenum == $last)
        {
        }
        else {
            $next = $pagenum+1;
            echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=$next&search_term=$searchTerm&type=user'>Next -></a> ";
            echo " ";
            echo " <a href='{$_SERVER['PHP_SELF']}?pagenum=$last&search_term=$searchTerm&type=user'>Last ->></a> ";
        }

    }
    else {

            echo "<br> No results found";;
        }

}

$search = $_GET["search_term"];
$type = $_GET["type"];

echo 'You searched for: ', $search, "<br><br>";

$conn = connectSQL();

if($type=="game") {
    displayGames($search, $conn);
}
else if ($type=="user"){
    displayUsers($search, $conn);
}

$conn->close();

?>

