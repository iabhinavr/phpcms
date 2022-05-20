<?php
session_start();
if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
{
	header('Location:login.php');
	die("Redirecting to login page");
}

define('IncAccess46z891155jkl', TRUE);
include '../ywd-inc/functions.php';
	
$page_title = 'Edit Article';
$body_class = 'edit-articles dashboard';

include '../ywd-inc/databaseClass.php';
include '../ywd-inc/articleClass.php';

$database 	= 	new Database();
$db_con 	= 	$database->db_connect();

$article_1 = new Article($db_con);
$article_2 = new Article($db_con);

if(isset($_GET['article_id'])) {
	
	$article_2->article_id = (int)$_GET['article_id'];
	$article_exists = $article_2->read_article_byId();
	
	if(isset($_POST['add-page'])) {
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$article_2->file_name = $_FILES['page']['name'];
			$article_2->tmp_name = $_FILES['page']['tmp_name'];
			$article_2->file_type = $_FILES['page']['type'];
			$article_2->file_size = $_FILES['page']['size'];
			$article_2->file_error = $_FILES['page']['error'];

			$article_2->page_no = (int)$_POST['page-no'];

			$article_2->new_page();
		}
	}
	
	if(isset($_POST['edit-page'])) {
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$article_2->page_no = (int)$_POST['page-no'];
			$article_2->page_id = (int)$_POST['page-id'];
			$article_2->update_page();
		}
	}
	
	if(isset($_POST['delete-page'])) {
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$article_2->page_id = (int)$_POST['page-id'];
			$article_2->file_name = $_POST['page-file'];
			$article_2->delete_page();
		}
	}
	
	$article_1->article_id = $article_2->article_id;
	
	$page_list = $article_1->read_pages();
}
else {
	$article_exists = false;
}

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>

<div class="admin-content-wrap">
	<div class="admin-home">
		<?php if($article_exists) : ?>
		<h2><?php echo $article_2->title; ?></h2>
		<h3 class="well well-sm">Add Pages</h3>
		<p>Upload a scanned jpeg/jpg image of the page</p>
		<form id="page-form" action="" method="post" enctype="multipart/form-data" class="form-inline" name="pageform">
			<input type="hidden" name="token" value="<?php echo $token; ?>">
			<input type="hidden" name="article-id" value="<?php echo $article_1->article_id; ?>">
			<div class="form-group">
				<input type="file" name="page" class="form-control" required>
			</div>
			<div class="form-group">
				<input type="text" name="page-no" placeholder="Page number..." class="form-control" required>
			</div>
			<div class="form-group">
				<input type="submit" name="add-page" value="Upload Page" class="form-control">
			</div>
		</form>
		<div id="progressbar">
			<div id="progressvalue"></div>
		</div>
		<div class="percent"></div>
		<div class="msg"></div>
		<h3 class="well well-sm">Edit Pages</h3>
		<ul class="video-list" id="page-list">
			<?php if($page_list) : ?>
			<?php while ($page_single = $page_list->fetch(PDO::FETCH_ASSOC)) : ?>
			<li>
				<div class="">
					<form action="" method="post" class="form-inline">
						<input type="hidden" name="token" value="<?php echo $token; ?>">
						<div class="form-group">
							<img class="av-img" src="../ywd-uploads/articles/thumbnails-312/<?php echo $page_single['file_name']; ?>">
						</div>
						<div class="form-group">
							<label>Page Number: </label><input type="text" name="page-no" value="<?php echo $page_single['page_no']; ?>" class="form-control">
						</div>
						<input type="hidden" name="page-file" value="<?php echo $page_single['file_name'];?>">
						<input type="hidden" name="page-id" value="<?php echo $page_single['id'];?>">
						<div class="form-group">
							<input type="submit" name="edit-page" value="Save" class="form-control">
						</div>
						<div class="form-group">
							<input type="submit" name="delete-page" value="Delete Page" class="form-control" onclick="return confirm('Are you sure you want to delete this page?');">
						</div>
					</form>
				</div>
			</li>
			<?php endwhile; ?>
			<?php endif; ?>
		</ul>
		<?php else : ?>
		<span>Nothing Found!</span>
		<?php endif; ?>
	</div>
</div>
<script src="admin-assets/js/uploads.js"></script>
<?php include 'footer.php'; ?>