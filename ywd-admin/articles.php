<?php
session_start();
if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
{
	header('Location:login.php');
	die("Redirecting to login page");
}

define('IncAccess46z891155jkl', TRUE);
include '../ywd-inc/functions.php';
	
$page_title = 'Manage Articles';
$body_class = 'manage-articles dashboard';

include '../ywd-inc/databaseClass.php';
include '../ywd-inc/articleClass.php';

$database 	= 	new Database();
$db_con 	= 	$database->db_connect();

$article_1 = new Article($db_con);
$article_2 = new Article($db_con);

if(isset($_POST['add-article'])) {
	if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
		$article_2->title = htmlspecialchars($_POST['title']);
		$article_2->publisher = htmlspecialchars($_POST['publisher']);
		$article_2->new_article();
	}
}
if(isset($_POST['edit-article'])) {
	if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
		$article_2->article_id = (int)$_POST['article-id'];
		$article_2->title = htmlspecialchars($_POST['title']);
		$article_2->publisher = htmlspecialchars($_POST['publisher']);
		$article_2->update_article();
	}
}
if(isset($_POST['delete-article'])) {
	if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
		$article_2->article_id = (int)$_POST['article-id'];
		$article_2->delete_article();
	}
}
$article_list = $article_1->read_article();

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>

<div class="admin-content-wrap">
	<div class="admin-home">
		<h3>Add New Article</h3> 
		<form action="" method="post" class="form-inline">
			<input type="hidden" name="token" value="<?php echo $token; ?>">
			<div class="form-group">
				<label class="av-label">Article Title: </label><input type="text" name="title" placeholder="Article Title here" class="form-control" required>
			</div>
			<div class="form-group">
				<label class="av-label">Published in: </label><input id="pubname" type="text" name="publisher" placeholder="eg., Mathrubhumi, Manorama etc..." class="form-control" required>
			</div>
			<div class="form-group">
				<input type="submit" name="add-article" value="Add Article" class="form-control">
			</div>
		</form>
		<h3 class="well well-sm">Manage Articles</h3>
		<ul class="video-list">
			<?php while ($article_single = $article_list->fetch(PDO::FETCH_ASSOC)) : ?>
			<li>
				<form action="" method="post" class="form-inline">
					<input type="hidden" name="token" value="<?php echo $token; ?>">
					<div class="form-group">
						<label class="av-label">Title: </label><input type="text" name="title" value="<?php echo $article_single['title']; ?>" class="form-control">
					</div>
					<div class="form-group">
						<label>Published in: </label><input type="text" name="publisher" value="<?php echo $article_single['publisher']; ?>" class="form-control">
					</div>
					<input type="hidden" name="article-id" value="<?php echo $article_single['id']; ?>">
					<div class="form-group">
						<input type="submit" name="edit-article" value="Save" class="form-control">
					</div>
					<div class="form-group">
						<input type="submit" name="delete-article" value="Delete Article" class="form-control" onclick="return confirm('Are you sure you want to delete this article?');">
					</div>
				</form>
				<a class="av-btn" href="edit-article.php?article_id=<?php echo $article_single['id']; ?>">Add / Edit Pages</a>
			</li>
			<?php endwhile; ?>
		</ul>
	</div>
</div>
<script>
	$(function(){
		var availableTags = <?php echo $article_1->list_publishers(); ?>;
		$("#pubname").autocomplete({
			autoFocus: true,
			source: availableTags,
			open : function(){
				$(".ui-autocomplete:visible").css({top:"+=15"});
			},
		});
	});
</script>
<?php include 'footer.php'; ?>