<?php
// Mulai sesi
session_start();
session_destroy();

// Arahkan login
header("Location: login.php");
exit;
?>
