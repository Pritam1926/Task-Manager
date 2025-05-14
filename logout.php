<?php
session_start();
session_unset();     // All session variables unset kore
session_destroy();   // Session completely destroy kore

header("Location: login.html"); // Ba login.html jodi form static hoy
exit;
?>
