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
<?php
// Start the session
session_start();
$user = $_SESSION["userID"];
echo $user;
?>
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
                <font size="3">

                <!--
                * userPageDisplay.php
                *
                * Displays a user game list. Verifies if user is list owner to enable edits and deletes.
                *
                * Created by Nishi
                * Date: 11/22/14
                */
                -->

                <?php
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

                //Display games from userGame list matching user, separated by status
                function displayGameList($user, $profileOwner, $conn){
                    echo "<br> Currently Playing:";
                    displayGames($user, $profileOwner,'playing', $conn);
                    echo "<br>";
                    echo "<br> Completed:";
                    displayGames($user, $profileOwner,'completed', $conn);
                    echo "<br>";
                    echo "<br> Want to Play:";
                    displayGames($user, $profileOwner,'plan', $conn);
                    echo "<br>";
                    echo "<br> Dropped:";
                    displayGames($user, $profileOwner,'dropped', $conn);
                    echo "<br>";
                    echo "<br> Reviews:";
                    displayReviews($profileOwner, $conn);
                }

                /*
                 * Displays games under given status
                 */
                function displayGames($user, $profileOwner, $status, $conn){
                    $sql = "SELECT * FROM `gamecache`.`usergames` WHERE `User ID` LIKE '$profileOwner' AND `Status` LIKE '$status'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0){
                        echo "<table><tr><th>Title</th><th>Rating</th><th></th><th></th></tr>";
                        while ($row = $result->fetch_assoc()) {
                            $gameID = $row["Game ID"];

                            //Search game cache for game ID and retrieve additional information
                            $gameInfo = $conn->query("SELECT name FROM `gamecache`.`gamelist` WHERE `ID` LIKE '$gameID'");
                            $gameTitle = $gameInfo->fetch_assoc()["name"];


                            //Display results in table
                            echo "<tr><td>" .$gameTitle ."</td><td>" .$row["Rating"] ."</td>";

                            //If user is owner of list, give edit and delete options
                            if (isOwner($user, $profileOwner)) {
                                echo "<td>";
                                editButton($gameID);
                                echo "</td><td>";
                                deleteButton($gameID);
                                echo "</td></tr>";
                            }
                            else{
                                echo "</tr>";
                            }
                        }
                        echo "</table>";
                    }
                }

                /*
                 * Edit game button
                 */
                function editButton($gameID){
                    echo "<form action='gameListForm.php'>
           <input type='hidden' name='gameID' value='$gameID'>
           <input type='submit' name='listChange' value='Edit'>
           </form>";
                }

                /*
                 * Delete game button
                 */
                function deleteButton($gameID){
                    echo "<form action='updateGameList.php'>
            <input type='hidden' name='gameID' value='$gameID'>
           <input type='submit' name='listChange' value='Delete'>
           </form>";
                }

                function deleteFriend($friendID){
                    echo "<form action='updateFriendList.php'>
            <input type='hidden' name='friendID' value='$friendID'>
           <input type='submit' name='listChange' value='Delete'>
           </form>";
                }

                /*
                 *  Check if user is owner of profile
                 */
                function isOwner($user, $profileOwner){
                    if ($user==$profileOwner) {
                        return true;
                    }
                    else{
                        return false;
                    }
                }

                /*
                 *  Displays reviews from most recent to oldest
                 */
                function displayReviews($profileOwner, $conn){
                    $sql = "SELECT * FROM `gamecache`.`usergames` WHERE `User ID` LIKE '$profileOwner' AND `Review` IS NOT NULL AND `Review`!='' ORDER BY `Timestamp` DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                            $gameID = $row["Game ID"];
                            $gameInfo = $conn->query("SELECT name FROM `gamecache`.`gamelist` WHERE `ID` LIKE '$gameID'");
                            $gameTitle = $gameInfo->fetch_assoc()["name"];
                            $timestamp = $row["Timestamp"];
                            $review = $row["Review"];

                            echo "<br>";
                            echo "<br>" .$gameTitle;
                            echo "<br> " .date('Y/m/d', $timestamp);
                            echo "<br>" .$row["Rating"];
                            echo "<br>".$review;
                        }
                    }
                }

                /*
                 * Displays users profile owner is following
                 */
                function displayFriendList($user, $profileOwner, $conn){
                    echo "<br>";
                    $sql = "SELECT * FROM `gamecache`.`userfriends` WHERE `User ID` LIKE '$profileOwner'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0){

                        echo "<br> Following: ";

                        while ($row = $result->fetch_assoc()) {
                            $friendID = $row["Friend ID"];

                            echo "<br>";
                            echo " <a href='userPageDisplay.php?profileOwner=$friendID'> $friendID </a> ";

                            //If user is owner of list, give edit and delete options
                            if (isOwner($user, $profileOwner)) {
                                deleteFriend($friendID);
                            }
                        }
                    }
                }

                /*
                 * Displays list of users that have added the profile owner to friends list.
                 * No add or delete options regardless of user: users may follow whoever they like.
                 */
                function displayFollowerList($profileOwner, $conn){
                    echo "<br>";
                    $sql = "SELECT * FROM `gamecache`.`userfriends` WHERE `Friend ID` LIKE '$profileOwner'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0){

                        echo "<br> Followers: ";

                        while ($row = $result->fetch_assoc()) {
                            $followerID = $row["User ID"];

                            echo "<br>";
                            echo " <a href='userPageDisplay.php?profileOwner=$followerID'> $followerID </a> ";

                        }
                    }
                }
                ?>


                <?php

                //Get user viewing profile from session
                $user = $_SESSION["userID"];

                //Retrieve profile owner from link
                $profileOwner = test_input($_GET["profileOwner"]);

                $conn = connectSQL();
                displayGameList($user, $profileOwner, $conn);
                displayFriendList($user, $profileOwner, $conn);
                displayFollowerList($profileOwner, $conn);

                $conn->close();

                ?>
                </font>

                </div>
                <div class="cart-navigation">
                </div>
            </div>



        </div>
    </div>




</form>
</body>

</html>




