<?php
	if( !defined('IncAccess46z891155jkl') )
	{
		die('Access Denied');
	}
?><!doctype html>
<html>
	<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?php echo $page_title; ?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->
		
		<link rel="shortcut icon" type="image/x-icon" href="<?php ywd_homeurl(); ?>/assets/images/favicon.ico">
		<link href='https://fonts.googleapis.com/css?family=Raleway:400,700,500' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,500|Raleway:400,700|PT+Sans:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php ywd_homeurl(); ?>/ywd-admin/admin-assets/fonts/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php ywd_homeurl(); ?>/ywd-admin/admin-assets/css/normalize.css">
		<link rel="stylesheet" type="text/css" href="<?php ywd_homeurl(); ?>/ywd-admin/admin-assets/css/dropzone.css">
		<link rel="stylesheet" href="<?php ywd_homeurl(); ?>/ywd-admin/admin-assets/css/jquery-ui.css">
		<link rel="stylesheet" href="<?php ywd_homeurl(); ?>/ywd-admin/admin-assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php ywd_homeurl(); ?>/ywd-admin/admin-assets/css/admin-style.css">
		
        <script src="<?php ywd_homeurl(); ?>/assets/js/vendor/modernizr-2.8.3.min.js"></script>
		<script src="admin-assets/js/jquery-1.11.3.min.js"></script>
		<script src="<?php ywd_homeurl(); ?>/ywd-admin/admin-assets/js/jquery-ui.js"></script>
        <script src="admin-assets/js/dropzone.js"></script>
    </head>
	<body class="<?php echo $body_class; ?>">
		<div class="admin-wrap">
			<header class="admin-header clearfix">
				<h1>Dashboard: PHP CMS</h1>
				<ul class="admin-header-nav">
					<li class="loggedin-menu">Hello, <?php echo $_SESSION['user']; ?></li>
					<li class="vsite-menu"><a href="<?php ywd_homeurl(); ?>">View Site</a></li>
					<li class="logout-menu"><a href="logout.php?token=<?php echo $token; ?>">Logout</a></li>
				</ul>
			</header>
			<div class="admin-nav-bg"></div>
			<nav class="admin-nav">
				<ul class="admin-menu">
					<li class="dashboard-menu"><a href="index.php">Dashboard</a></li>
					<li class="books-menu"><a href="book-manager.php">Books</a></li>
					<li class="articles-menu"><a href="articles.php">Articles</a></li>
					<li class="news-menu"><a href="news-manager.php">News</a></li>
					<li class="photos-menu"><a href="photo-manager.php">Photos</a></li>
					<li class="audios-menu"><a href="audios.php">Musics</a></li>
					<li class="videos-menu"><a href="videos.php">Videos</a></li>
					<li class="events-menu"><a href="event-manager.php">Events</a></li>
					<li class="seo-menu"><a href="seo.php">SEO</a></li>
					<li class="account-menu"><a href="account.php">Manage Account</a></li>
					
				</ul>
			</nav>
		