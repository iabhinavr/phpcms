<?php
session_start();
session_regenerate_id(true);
define('IncAccess46z891155jkl', TRUE);
include '../ywd-inc/functions.php';

include_once '../ywd-inc/databaseClass.php';
include_once '../ywd-inc/userClass.php';

$database = new Database();
$db_con = $database->db_connect();

$user = new User($db_con);

if(isset( $_POST['login'] )) {
	if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
		$user->username = htmlspecialchars($_POST['username']);
		$user->password = htmlspecialchars($_POST['password']);

		$login = $user->authenticate();

		if($login === true) {

			$_SESSION['user'] = $user->username;
			header('Location:index.php');
		}
	}
		
}
$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Login</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->
		
		<link rel="shortcut icon" type="image/x-icon" href="<?php ywd_homeurl(); ?>/assets/images/favicon.ico">
		<link href='https://fonts.googleapis.com/css?family=Raleway:400,700,500' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,500|PT+Sans:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php ywd_homeurl(); ?>/ywd-admin/admin-assets/fonts/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php ywd_homeurl(); ?>/ywd-admin/admin-assets/css/normalize.css">
		<link rel="stylesheet" href="<?php ywd_homeurl(); ?>/ywd-admin/admin-assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php ywd_homeurl(); ?>/ywd-admin/admin-assets/css/admin-style.css">
		<link rel="stylesheet" type="text/css" href="admin-assets/css/dropzone.css">
        <script src="<?php ywd_homeurl(); ?>/assets/js/vendor/modernizr-2.8.3.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="admin-assets/js/dropzone.js"></script>
    </head>
    <body class="login">
		<div class="admin-wrap">
			<header class="admin-header clearfix">
				<h1>Web Login</h1>
				<ul class="admin-header-nav">
					
					<li><a href="<?php ywd_homeurl(); ?>">View Site</a></li>

				</ul>
			</header>
			<div class="login-register">
				<?php if(isset($login)) : ?>
					<?php if($login === false) : ?>
						<p class="login-error-message">Incorrect username or password</p>
					<?php endif; ?>
				<?php endif; ?>
				<form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post" class="clearfix">
					<div class="form-group">
						<label>Username :</label>
						<input class="form-control" type="text" name="username" id="username" required="required" autofocus>
					</div>
					<div class="form-group">
						<label>Password :</label>
						<input class="form-control" type="password" name="password" id="password" required="required">
					</div>
					<input type="hidden" name="token" value="<?php echo $token; ?>">
					<input class="form-control" type="submit" name="login" id="register" value="Log In">
				</form>
			</div>
		</div>
		<script src="admin-assets/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
