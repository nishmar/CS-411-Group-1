<?php
// Start the session
session_start();
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
<?php
function test_input2($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
$user = $_SESSION["userID"];
echo $user;
$profileOwner = test_input2($_SESSION["userID"]);
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
                                <a class="gh-tab-link" href="userPageDisplay.php" ><span class="gh-tab-inner"><FONT COLOR="#FFFFFF">Home</FONT></span></span></a>
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

                    //Retrieves variables
                    $listChange = test_input($_GET["listChange"]);
                    $gameID = test_input($_GET["gameID"]);
                    $profileOwner = test_input($_SESSION["userID"]);

                    $conn = connectSQL();



                    //Gets necessary variables depending on condition : Add or edit
                    if ($listChange=='Add Game'){
                        $title = test_input($_GET["title"]);
                        $page = test_input($_GET["pagenum"]);
                        $search = test_input($_GET["search_term"]);
                    }
                    else if ($listChange=='Edit') {
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

                    echo  "<a href='userPageDisplay.php?profileOwner=$user'> Your Profile </a><br>" ;
                    ?>

                    <!-- Form -->
                    <html>
                    <head lang='en'>
                        <meta charset='UTF-8'>
                        <title>Add Game to List</title>
                    </head>

                    <h1>Add Game</h1><br>
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
                    <body>
                    </body>
                    </html>

                    <?php
                    $conn->close();
                    ?>

                </div>
                <div class="cart-navigation">
                    <input type="submit" name="button" id="button" value="Submit"/>
                </div>
            </div>



        </div>
    </div>




</form>
</body>

</html>



