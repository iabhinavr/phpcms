<?php
	session_start();
	if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
	{
		header('Location:login.php');
		die("Redirecting to login page");
	}

	define('IncAccess46z891155jkl', TRUE);
	include '../ywd-inc/functions.php';
	$page_title = 'Manage Books';
	$body_class = 'book-manager';


	include_once '../ywd-inc/databaseClass.php';
	include_once '../ywd-inc/bookClass.php';

	$database 	= 	new Database();
	$db_con 	= 	$database->db_connect();
	

	$book 		=	new Book($db_con);
	$results 	=	$book->read_book();

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>

	<div class="admin-content-wrap">
		
		
		
		<div class="admin-title"><h1>Manage Books</h1></div>
		<div class="add-new-button"><a href="new-book.php">Click Here to Add More Books</a></div>
		
			<div class="books-wrap">
			
			<?php while( $result = $results->fetch(PDO::FETCH_ASSOC) ) : ?>
				<?php
					$date_arr = date_parse( $result['uploaded_date'] );
				?>
				<div class="photo-single">
					<div class="center-photo">
					<a href="<?php ywd_homeurl(); ?>/ywd-admin/edit-book.php?id=<?php echo $result['id']; ?>">
						<img src="<?php ywd_homeurl(); ?>/ywd-uploads/books/thumbnails-312/<?php echo $date_arr['year']; ?>/<?php echo $date_arr['month']; ?>/<?php echo $result['file_name']; ?>">
					</a>
					</div>
				</div>
			<?php endwhile; ?>
			</div>
	</div><!--.admin-content-wrap-->

<?php include 'footer.php'; ?>