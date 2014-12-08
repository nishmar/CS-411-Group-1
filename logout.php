<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
// remove all session variables

$_SESSION["userID"]="";

session_unset();

// destroy the session
session_destroy();

echo "You are now logged out. Redirecting to Login page...";

header('Refresh: 3; url=login.php');

?>
</body>
</html>
