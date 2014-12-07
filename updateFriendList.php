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

                </div>
                <div class="cart-navigation">
                </div>
            </div>



        </div>
    </div>




</form>
</body>

</html>




