<?php
// Start the session
session_start();
$user = $_SESSION["userID"];
echo $user;
?>
<!DOCTYPE html>

<html class="en-us nojs us en amr" lang="en-US">
<head>
    <meta name="viewport" content="width=1024" />
    <title>Sign in  - GoodGame (U.S.)</title>




    <!--[if gte IE 9]> FOR THE HEADER <!-->
    <link rel="stylesheet" href="https://store.storeimages.cdn-apple.com/4234/store.apple.com/rs/rel/base.css" media="screen, print" />
    <!--<![endif]-->
    <!--[if gte IE 9]><!-->
    <link rel="stylesheet" href="https://store.storeimages.cdn-apple.com/4234/store.apple.com/rs/rel/signin.css" media="screen, print" />
    <!--<![endif]-->




</head>

<body class="interim login">
<form id="form1" name="form1" method="post" action="login.php">



    <div id="page">


        <nav aria-label="Global Navigation" role="navigation" lang="en-us" class="globalheader stack-item gh-selected-tab-store" id="globalheader">
            <div id="gh-content" class="gh-content">

                <div class="gh-nav">
                    <div class="gh-nav-view stack-item-body" >
                        <ul id="gh-nav-list" class="gh-nav-list">

                            <li class="gh-tab">
                                <BR>
                                <a href="logout.php" class="gh-tab-link"><span class="gh-tab-inner"><FONT COLOR="#FFFFFF">Logout</FONT></span></span></a>
                            </li>

                            <li class="gh-tab">
                                <BR>
                                <script type="text/javascript">
                                    var php_var = "<?php echo $user; ?>";
                                    document.write('<a class="gh-tab-link" href="userPageDisplay.php?profileOwner=' + php_var + '"><span class="gh-tab-inner"><FONT COLOR="#FFFFFF">Home</FONT></span></span></a>')
                                </script>
                            </li>

                            <li class="gh-tab">
                                <BR>
                                <a href="searchForm.php" class="gh-tab-link"><span class="gh-tab-inner"><FONT COLOR="#FFFFFF">Search</FONT></span></span></a>
                            </li>
                            <li class="gh-tab">
                                <BR>
                                <a href="registration.php" class="gh-tab-link"><span class="gh-tab-inner"><FONT COLOR="#FFFFFF">Register</FONT></span></span></a>
                            </li>


                        </ul>
                    </div>
                </div>
            </div>
        </nav>


        <div class="store-header clearfix">
            <div class="masthead clearfix">
                <div class="masthead-title">
                    <font size="5" face="Fantasy"><font size="6">G</font>ood &nbsp  <font size="6">G</font>ame</font>

                </div>

            </div>
        </div>


        <div id="container">
            <div role="main" class="accountbox">
                <div id="account-page-header">Login Form</div>
                <div id="account-content" class="content clearfix">
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

                </div>
                <div class="cart-navigation">

                </div>
            </div>



        </div>
    </div>




</form>
</body>

</html>




