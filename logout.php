<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
// remove all session variables
session_unset();

// destroy the session
session_destroy();

echo "You are now logged out. ";

header('Location: login.html');

?>


</body>
</html>
