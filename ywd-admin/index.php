<?php
	session_start();
	if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
	{
		header('Location:login.php');
		die("Redirecting to login page");
	}

	define('IncAccess46z891155jkl', TRUE);
	include '../ywd-inc/functions.php';
	
	$page_title = 'Dashboard';
	$body_class = 'admin dashboard';

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>

<div class="admin-content-wrap">
	<div class="admin-home">
		<img src="admin-assets/images/screenshot.png" alt="PHP CMS">
		<div class="admin-home-buttons">
			<div class="admin-home-button">
				<a href="new-book.php">Add Books</a>
			</div>
			<div class="admin-home-button">
				<a href="articles.php">Add Articles</a>
			</div>
			<div class="admin-home-button">
				<a href="new-photo.php">Add Photos</a>
			</div>
			<div class="admin-home-button">
				<a href="new-news.php">Add News</a>
			</div>
		</div>
	</div>
</div>

<?php include 'footer.php'; ?>