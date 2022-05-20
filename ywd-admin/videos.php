<?php
	session_start();
	if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
	{
		header('Location:login.php');
		die("Redirecting to login page");
	}

	define('IncAccess46z891155jkl', TRUE);
	include '../ywd-inc/functions.php';
	
	$page_title = 'Manage Videos';
	$body_class = 'manage-videos dashboard';

	include '../ywd-inc/databaseClass.php';
	include '../ywd-inc/videoClass.php';

	$database 	= 	new Database();
	$db_con 	= 	$database->db_connect();

	$video_1		=	new Video($db_con);
	$video_2		=	new Video($db_con);
	$video_3		=	new Video($db_con);
	$video_4		=	new Video($db_con);
		

	if( isset($_POST['add-video']) ) {
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$video_1->title = htmlspecialchars($_POST['title']);
			$video_1->youtube_id = htmlspecialchars($_POST['youtube-id']);

			$video_1->new_video();
		}
	}

	if( isset($_POST['edit-video']) ) {
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$video_2->title = htmlspecialchars($_POST['edit-title']);
			$video_2->youtube_id = htmlspecialchars($_POST['edit-youtube-id']);
			$video_2->video_id = htmlspecialchars($_POST['video-id']);

			$video_2->update_video();
		}
	}
	if( isset($_POST['delete-video']) ) {
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$video_3->video_id = (int)$_POST['video-id'];
			$video_3->delete_video();
		}
	}
$video_list = $video_4->read_video();

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>

<div class="admin-content-wrap">
	<div class="admin-home">
		<h3>Add New Video from Youtube</h3>
		<form action="" method="post" class="form-inline">
			<input type="hidden" name="token" value="<?php echo $token; ?>">
			<input class="form-control" type="text" name="title" placeholder="Video Title here" required>
			<input class="form-control" type="text" name="youtube-id" placeholder="Youtube Video ID here" required>
			<input class="form-control" type="submit" name="add-video" value="Add Video">
		</form>
		<h3 class="well well-sm">Manage Videos</h3>
		<ul class="video-list">
			<?php while ($video_single = $video_list->fetch(PDO::FETCH_ASSOC)) : ?>
			<li>
				<form action="" method="post" class="form-inline">
					<input type="hidden" name="token" value="<?php echo $token; ?>">
					<img src="http://img.youtube.com/vi/<?php echo $video_single['youtube_id'];?>/1.jpg">
					<div class="form-group">
						<label>Title: </label>
						<input class="form-control" type="text" name="edit-title" value="<?php echo $video_single['title']; ?>">
					</div>
					
					<div class="form-group">
						<label>Youtube Video ID: </label>
						<input class="form-control" type="text" name="edit-youtube-id" value="<?php echo $video_single['youtube_id'];?>">
					</div>
					<input type="hidden" name="video-id" value="<?php echo $video_single['id']; ?>">
					
					<input class="form-control" type="submit" name="edit-video" value="Save">
					<input class="form-control" type="submit" name="delete-video" value="Delete Video" onclick="return confirm('Are you sure you want to delete this video?');">
				</form>
			</li>
			<?php endwhile; ?>
		</ul>
	</div>
</div>

<?php include 'footer.php'; ?>