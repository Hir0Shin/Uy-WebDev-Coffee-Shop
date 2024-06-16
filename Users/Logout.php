<?php
session_start();
$_SESSION = array();
SESSION_DESTROY();
header('Location:../Pages/Login.html');
exit();
?>