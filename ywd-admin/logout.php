<?php
session_start();
if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_GET['token'] == $_SESSION['token']) {
	session_unset();
	session_destroy();
	header('Location:login.php');
}