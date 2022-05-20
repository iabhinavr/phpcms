<?php
	session_start();
	if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
	{
		header('Location:login.php');
		die("Redirecting to login page");
	}

	define('IncAccess46z891155jkl', TRUE);
	include '../ywd-inc/functions.php';
	$page_title = 'Edit Book';
	$body_class = 'edit-book';


	include_once '../ywd-inc/databaseClass.php';
	include_once '../ywd-inc/bookClass.php';

	$database 	= 	new Database();
	$db_con 	= 	$database->db_connect();

	if( isset( $_GET['id'] ) ) {
		
		$book 				= 	new Book($db_con);
		$book->book_id 	= 	(int)$_GET['id'];
		$book_exists 		= 	$book->read_book_byId();
		
	}
	else {
		$book_exists = false;
	}

	if( isset( $_POST['save-details'] ) ) {
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {

			$book->book_id		=	(int)$_POST['book_id'];
			$book->title 		= 	htmlspecialchars($_POST['title']);
			$book->pages		=	(int)$_POST['pages'];
			$book->publisher	=	htmlspecialchars($_POST['publisher']);
			$book->price		=	htmlspecialchars($_POST['price']);
			$book->description	=	htmlspecialchars($_POST['description']);

			$book->save_details();
		}
	}
	if( isset( $_POST['delete-book'] ) ) {
		
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$book->book_id	=	(int)$_POST['del_id'];
			$book->delete_book();
			header("Location:book-manager.php");
			die();
		}
	}

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>

	<div class="admin-content-wrap edit-book">
		
		<div class="msg-wrap">
		
			<?php if (isset ($book->saving_status)) : ?>

				<?php if( $book->saving_status == true ) : ?>
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
		
		
		<div class="admin-title"><h2 class="well well-sm">Edit Book Details</h2></div>
		
		<?php if( $book_exists === true ) : ?>
			<div class="preview-image">
				<img src="<?php ywd_homeurl(); ?>/ywd-uploads/books/<?php echo $book->book_year; ?>/<?php echo $book->book_month; ?>/<?php echo $book->file_name; ?>">
			</div>
		
			<div class="image-details">
				<p>Full size image url: <strong><?php ywd_homeurl(); ?><?php echo "/ywd-uploads/books/" . $book->book_year . "/" . $book->book_month . "/" . $book->file_name; ?></strong></p>
				<p>Thumbnail image url: <strong><?php ywd_homeurl(); ?><?php echo "/ywd-uploads/books/thumbnails-312/" . $book->book_year . "/" . $book->book_month . "/" . $book->file_name; ?></strong></p>
			</div>
		
			<form action="" method="post" class="details-form">
				<input type="hidden" name="token" value="<?php echo $token; ?>">
				<label>Title: </label>
				<input type="text" name="title" value="<?php echo $book->title; ?>">
				<label>No. of Pages: </label>
				<input type="text" name="pages" value="<?php echo  $book->pages; ?>">
				<label>Publisher: </label>
				<input type="text" name="publisher" value="<?php echo  $book->publisher; ?>">
				<label>Price: </label>
				<input type="text" name="price" value="<?php echo $book->price; ?>">
				<label>Description (Maximum of 200 characters): </label>
				<textarea name="description" maxlength="200"><?php echo $book->description; ?></textarea>
				<input type="hidden" name="book_id" value="<?php echo $book->book_id; ?>">
				<input type="submit" name="save-details" value="Save">
			</form>
			
		
		<form method="post" action="" class="details-form" onsubmit="return confirm('Are you sure you want to delete this media?');">
			<input type="hidden" name="token" value="<?php echo $token; ?>">
			<input type="hidden" name="del_id" value="<?php echo $book->book_id; ?>">
			<input type="submit" name="delete-book" value="Delete Book">
		</form>
		
		<?php else : ?>
			<p>Nothing found!</p>
		<?php endif; ?>
	
	</div><!--.admin-content-wrap-->
<?php include 'footer.php'; ?>