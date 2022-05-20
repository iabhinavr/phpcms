<?php
	session_start();
	if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
	{
		header('Location:login.php');
		die("Redirecting to login page");
	}

	define('IncAccess46z891155jkl', TRUE);
	include '../ywd-inc/functions.php';
	$page_title = 'Add New Book';
	$body_class = 'new-book';


	include_once '../ywd-inc/databaseClass.php';
	include_once '../ywd-inc/bookClass.php';

	$database = new Database();
	$db_con = $database->db_connect();

	if( isset( $_POST['upload'] ) )
	{
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$book = new Book($db_con);

			$book->file_name = $_FILES['book']['name'];
			$book->tmp_name = $_FILES['book']['tmp_name'];
			$book->file_type = $_FILES['book']['type'];
			$book->file_size = $_FILES['book']['size'];
			$book->file_error = $_FILES['book']['error'];

			$book->new_book();
		}

	}

	if( isset($_FILES['file']['name']) )
	{
		$book = new Book($db_con);

		$book->file_name = $_FILES['file']['name'];
		$book->tmp_name = $_FILES['file']['tmp_name'];
		$book->file_type = $_FILES['file']['type'];
		$book->file_size = $_FILES['file']['size'];
		$book->file_error = $_FILES['file']['error'];

		$book->new_book();

	}

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>


<div class="admin-content-wrap new-book">
	<div class="admin-title"><h1>Upload New Book</h1></div>
	<div class="admin-desc">Upload New Books here</div>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="token" value="<?php echo $token; ?>">
		<input type="file" name="book">
		<input type="hidden" name="form_token" value="">
		<input type="submit" name="upload" value="Upload">
	</form>
	
	<div class="admin-desc">Upload multiple books:</div>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" class="dropzone" id="my-awesome-dropzone"></form>
	
	
	<div class="msg-wrap">
		
		<?php if (isset ($book->upload_status)) : ?>
		
			<?php if( $book->upload_status == true ) : ?>
				<div class="msg-box-s">
					<span class="msg-success"><i class="fa fa-check-circle-o"></i><?php echo $book->upload_msg; ?></span>
					<a href="<?php echo ywd_homeurl() . '/ywd-admin/edit-book.php?id=' . $book->book_id; ?>">Edit Book Details</a>
				</div>
		
			<?php else : ?>
		
				<div class="msg-box-e">
					<span class="msg-error"><i class="fa fa-exclamation-triangle"></i><?php echo $book->upload_msg; ?></span>
				</div>
		
			<?php endif; ?>
		
		<?php endif; ?>
		
	</div><!--.msg-wrap-->
	
</div><!--.admin-content-wrap-->

<?php include 'footer.php'; ?>



