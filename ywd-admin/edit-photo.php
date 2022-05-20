<?php
	session_start();
	if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
	{
		header('Location:login.php');
		die("Redirecting to login page");
	}

	define('IncAccess46z891155jkl', TRUE);
	include '../ywd-inc/functions.php';
	$page_title = 'Edit Photo';
	$body_class = 'edit-photo';


	include_once '../ywd-inc/databaseClass.php';
	include_once '../ywd-inc/photoClass.php';

	$database 	= 	new Database();
	$db_con 	= 	$database->db_connect();

	if( isset( $_GET['id'] ) ) {
		
		$photo 				= 	new Photo($db_con);
		$photo->photo_id 	= 	(int)$_GET['id'];
		$photo_exists 		= 	$photo->read_photo_byId();
		
	}
	else {
		$photo_exists = false;
	}

	if( isset( $_POST['save-details'] ) ) {
		
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$photo->photo_id	=	(int)$_POST['photo_id'];
			$photo->title 		= 	htmlspecialchars($_POST['title']);
			$photo->description	=	htmlspecialchars($_POST['description']);

			$done_saving = $photo->save_details();
		}
	}
	if( isset( $_POST['delete-photo'] ) ) {
		
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$photo->photo_id	=	(int)$_POST['del_id'];
			$photo->delete_photo();
			header("Location:photo-manager.php");
			die();
		}
	}

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>

	<div class="admin-content-wrap edit-photo">
		
		<div class="msg-wrap">
		
			<?php if (isset ($photo->saving_status)) : ?>

				<?php if( $photo->saving_status == true ) : ?>
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
		
		
		<div class="admin-title"><h2 class="well well-sm">Edit Photo Details</h2></div>
		
		<?php if( $photo_exists === true ) : ?>
			<div class="preview-image">
				<img src="<?php ywd_homeurl(); ?>/ywd-uploads/photos/<?php echo $photo->photo_year; ?>/<?php echo $photo->photo_month; ?>/<?php echo $photo->file_name; ?>">
			</div>
		
			<div class="image-details">
				<p>Full size image url: <strong><?php ywd_homeurl(); ?><?php echo "/ywd-uploads/photos/" . $photo->photo_year . "/" . $photo->photo_month . "/" . $photo->file_name; ?></strong></p>
				<p>Thumbnail image url: <strong><?php ywd_homeurl(); ?><?php echo "/ywd-uploads/photos/thumbnails-312/" . $photo->photo_year . "/" . $photo->photo_month . "/" . $photo->file_name; ?></strong></p>
			</div>
		
			<form action="" method="post" class="details-form">
				<input type="hidden" name="token" value="<?php echo $token; ?>">
				<label>Title: </label>
					<input type="text" name="title" value="<?php echo $photo->title; ?>">
				<label>Description (Maximum of 200 characters): </label>
					<textarea name="description" maxlength="200"><?php echo $photo->description; ?></textarea>
				<input type="hidden" name="photo_id" value="<?php echo $photo->photo_id; ?>">
				<input type="submit" name="save-details" value="Save">
			</form>
			
		
		<form method="post" action="" class="details-form" onsubmit="return confirm('Are you sure you want to delete this media?');">
			<input type="hidden" name="token" value="<?php echo $token; ?>">
			<input type="hidden" name="del_id" value="<?php echo $photo->photo_id; ?>">
			<input type="submit" name="delete-photo" value="Delete Photo">
		</form>
		
		<?php else : ?>
			<p>Nothing found!</p>
		<?php endif; ?>
	
	</div><!--.admin-content-wrap-->
<?php include 'footer.php'; ?>