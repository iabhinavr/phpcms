<?php
	session_start();
	if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
	{
		header('Location:login.php');
		die("Redirecting to login page");
	}

	define('IncAccess46z891155jkl', TRUE);
	include '../ywd-inc/functions.php';
	$page_title = 'Add New News';
	$body_class = 'new-news';


	include_once '../ywd-inc/databaseClass.php';
	include_once '../ywd-inc/newsClass.php';

	$database = new Database();
	$db_con = $database->db_connect();

	if( isset( $_POST['upload'] ) )
	{
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$news = new News($db_con);

			$news->file_name = $_FILES['news']['name'];
			$news->tmp_name = $_FILES['news']['tmp_name'];
			$news->file_type = $_FILES['news']['type'];
			$news->file_size = $_FILES['news']['size'];
			$news->file_error = $_FILES['news']['error'];

			$news->new_news();
		}

	}

	if( isset($_FILES['file']['name']) )
	{
		$news = new News($db_con);

		$news->file_name = $_FILES['file']['name'];
		$news->tmp_name = $_FILES['file']['tmp_name'];
		$news->file_type = $_FILES['file']['type'];
		$news->file_size = $_FILES['file']['size'];
		$news->file_error = $_FILES['file']['error'];

		$news->new_news();

	}

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>


<div class="admin-content-wrap new-news">
	<div class="admin-title"><h1>Upload New News</h1></div>
	<div class="admin-desc">Upload New Newss here</div>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
		<input type="hidden" name="token" value="<?php echo $token; ?>">
		<input type="file" name="news">
		<input type="hidden" name="form_token" value="">
		<input type="submit" name="upload" value="Upload">
	</form>
	
	<div class="admin-desc">Upload multiple news:</div>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" class="dropzone" id="my-awesome-dropzone"></form>
	
	
	<div class="msg-wrap">
		
		<?php if (isset ($news->upload_status)) : ?>
		
			<?php if( $news->upload_status == true ) : ?>
				<div class="msg-box-s">
					<span class="msg-success"><i class="fa fa-check-circle-o"></i><?php echo $news->upload_msg; ?></span>
					<a href="<?php echo ywd_homeurl() . '/ywd-admin/edit-news.php?id=' . $news->news_id; ?>">Edit News Details</a>
				</div>
		
			<?php else : ?>
		
				<div class="msg-box-e">
					<span class="msg-error"><i class="fa fa-exclamation-triangle"></i><?php echo $news->upload_msg; ?></span>
				</div>
		
			<?php endif; ?>
		
		<?php endif; ?>
		
	</div><!--.msg-wrap-->
	
</div><!--.admin-content-wrap-->

<?php include 'footer.php'; ?>



