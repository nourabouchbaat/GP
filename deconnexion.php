<?php session_start() ?>
<?php error_reporting(0) ?>

<?php require_once('lang.php'); ?>
<?php require_once('fonctions.php'); ?>
<?php require_once('tabs.php'); ?>

<?php

echo "<center><h2>" . _REDIRECT . "</h2></center>";

unset($_SESSION['admin']);
redirect('login.php');
?>