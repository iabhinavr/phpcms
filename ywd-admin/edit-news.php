<?php
	session_start();
	if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
	{
		header('Location:login.php');
		die("Redirecting to login page");
	}

	define('IncAccess46z891155jkl', TRUE);
	include '../ywd-inc/functions.php';
	$page_title = 'Edit News';
	$body_class = 'edit-news';


	include_once '../ywd-inc/databaseClass.php';
	include_once '../ywd-inc/newsClass.php';

	$database 	= 	new Database();
	$db_con 	= 	$database->db_connect();

	if( isset( $_GET['id'] ) ) {
		
		$news 				= 	new News($db_con);
		$news->news_id 	= 	(int)$_GET['id'];
		$news_exists 		= 	$news->read_news_byId();
		
	}
	else {
		$news_exists = false;
	}

	if( isset( $_POST['save-details'] ) ) {
		
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$news->news_id	=	(int)$_POST['news_id'];
			$news->title 		= 	htmlspecialchars($_POST['title']);
			$news->description	=	htmlspecialchars($_POST['description']);

			$done_saving = $news->save_details();
		}
	}
	if( isset( $_POST['delete-news'] ) ) {
		
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$news->news_id	=	(int)$_POST['del_id'];
			$news->delete_news();
			header("Location:news-manager.php");
			die();
		}
	}

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>

	<div class="admin-content-wrap edit-news">
		
		<div class="msg-wrap">
		
			<?php if (isset ($news->saving_status)) : ?>

				<?php if( $news->saving_status == true ) : ?>
					<div class="msg-box-s">
						<span class="msg-success"><i class="fa fa-check-circle-o"></i>Details Successfully Saved</span>
					</div>

				<?php else : ?>

					<div class="msg-box-e">
						<span class="msg-error"><i class="fa fa-exclamation-triangle"></i>Couldn't Save! Some Error Occurred. Please try again or check back later.</span>
					</div>

				<?php endif; ?>

			<?php endif; ?>
		
		</div><!--.msg-wrap-->
		
		
		<div class="admin-title"><h2 class="well well-sm">Edit News Details</h2></div>
		
		<?php if( $news_exists === true ) : ?>
			<div class="preview-image">
				<img src="<?php ywd_homeurl(); ?>/ywd-uploads/news/<?php echo $news->news_year; ?>/<?php echo $news->news_month; ?>/<?php echo $news->file_name; ?>">
			</div>
		
			<div class="image-details">
				<p>Full size image url: <strong><?php ywd_homeurl(); ?><?php echo "/ywd-uploads/news/" . $news->news_year . "/" . $news->news_month . "/" . $news->file_name; ?></strong></p>
				<p>Thumbnail image url: <strong><?php ywd_homeurl(); ?><?php echo "/ywd-uploads/news/thumbnails-312/" . $news->news_year . "/" . $news->news_month . "/" . $news->file_name; ?></strong></p>
			</div>
		
			<form action="" method="post" class="details-form">
				<input type="hidden" name="token" value="<?php echo $token; ?>">
				<label>Title: </label>
					<input type="text" name="title" value="<?php echo $news->title; ?>">
				<label>Description (Maximum of 200 characters): </label>
					<textarea name="description" maxlength="200"><?php echo $news->description; ?></textarea>
				<input type="hidden" name="news_id" value="<?php echo $news->news_id; ?>">
				<input type="submit" name="save-details" value="Save">
			</form>
			
		
		<form method="post" action="" class="details-form" onsubmit="return confirm('Are you sure you want to delete this media?');">
			<input type="hidden" name="token" value="<?php echo $token; ?>">
			<input type="hidden" name="del_id" value="<?php echo $news->news_id; ?>">
			<input type="submit" name="delete-news" value="Delete News">
		</form>
		
		<?php else : ?>
			<p>Nothing found!</p>
		<?php endif; ?>
	
	</div><!--.admin-content-wrap-->
<?php include 'footer.php'; ?>