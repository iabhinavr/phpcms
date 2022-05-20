<?php
	session_start();
	if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
	{
		header('Location:login.php');
		die("Redirecting to login page");
	}

	define('IncAccess46z891155jkl', TRUE);
	include '../ywd-inc/functions.php';
	$page_title = 'Add New Photo';
	$body_class = 'new-photo';


	include_once '../ywd-inc/databaseClass.php';
	include_once '../ywd-inc/photoClass.php';

	$database = new Database();
	$db_con = $database->db_connect();

	if( isset( $_POST['upload'] ) )
	{
		if( isset($_SESSION['token']) 
		&& !empty($_SESSION['token']) 
		&& $_POST['token'] == $_SESSION['token'])  {

			$photo = new Photo($db_con);

			$photo->file_name = $_FILES['photo']['name'];
			$photo->tmp_name = $_FILES['photo']['tmp_name'];
			$photo->file_type = $_FILES['photo']['type'];
			$photo->file_size = $_FILES['photo']['size'];
			$photo->file_error = $_FILES['photo']['error'];

			$photo->new_photo();

		}

	}

	if( isset($_FILES['file']['name']) )
	{
		$photo = new Photo($db_con);

		$photo->file_name = $_FILES['file']['name'];
		$photo->tmp_name = $_FILES['file']['tmp_name'];
		$photo->file_type = $_FILES['file']['type'];
		$photo->file_size = $_FILES['file']['size'];
		$photo->file_error = $_FILES['file']['error'];
		
		$photo->new_photo();

	}

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>


<div class="admin-content-wrap new-photo">
	<div class="admin-title"><h1>Upload New Photo</h1></div>
	<div class="admin-desc">Upload New Photos here</div>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="token" value="<?php echo $token; ?>">
		<input type="file" name="photo">
		<input type="hidden" name="form_token" value="">
		<input type="submit" name="upload" value="Upload">
	</form>
	
	<div class="admin-desc">Upload multiple photos:</div>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" class="dropzone" id="my-awesome-dropzone"></form>
	
	
	<div class="msg-wrap">
		
		<?php if (isset ($photo->upload_status)) : ?>
		
			<?php if( $photo->upload_status == true ) : ?>
				<div class="msg-box-s">
					<span class="msg-success"><i class="fa fa-check-circle-o"></i><?php echo $photo->upload_msg; ?></span>
					<a href="<?php echo ywd_homeurl() . '/ywd-admin/edit-photo.php?id=' . $photo->photo_id; ?>">Edit Photo Details</a>
				</div>
		
			<?php else : ?>
		
				<div class="msg-box-e">
					<span class="msg-error"><i class="fa fa-exclamation-triangle"></i><?php echo $photo->upload_msg; ?></span>
				</div>
		
			<?php endif; ?>
		
		<?php endif; ?>
		
	</div><!--.msg-wrap-->
	
</div><!--.admin-content-wrap-->

<?php include 'footer.php'; ?>



