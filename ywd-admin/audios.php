<?php
	session_start();
	if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
	{
		header('Location:login.php');
		die("Redirecting to login page");
	}

	define('IncAccess46z891155jkl', TRUE);
	include '../ywd-inc/functions.php';
	
	$page_title = 'Manage Audios';
	$body_class = 'manage-audios dashboard';

	include '../ywd-inc/databaseClass.php';
	include '../ywd-inc/audioClass.php';

	$database 	= 	new Database();
	$db_con 	= 	$database->db_connect();

	$audio_1		=	new Audio($db_con);
	$audio_2		=	new Audio($db_con);
	$audio_3		=	new Audio($db_con);
	$audio_4		=	new Audio($db_con);
		

	if( isset($_POST['add-audio']) ) {
		$audio_1->title = htmlspecialchars($_POST['title']);
		
		$audio_1->file_name = $_FILES['audio']['name'];
		$audio_1->tmp_name = $_FILES['audio']['tmp_name'];
		$audio_1->file_type = $_FILES['audio']['type'];
		$audio_1->file_size = $_FILES['audio']['size'];
		$audio_1->file_error = $_FILES['audio']['error'];
		
		$audio_1->new_audio();
	}

	if( isset($_POST['edit-audio']) ) {
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$audio_2->title = htmlspecialchars($_POST['edit-title']);
			$audio_2->audio_id = (int)$_POST['audio-id'];

			$audio_2->update_audio();
		}
	}
	if( isset($_POST['delete-audio']) ) {
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$audio_3->audio_id = (int)$_POST['audio-id'];
			$audio_3->read_audio_byId();
			$audio_3->delete_audio();
		}
	}
$audio_list = $audio_4->read_audio();

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>

<div class="admin-content-wrap">
	<div class="admin-home">
		<h3>Add New Audio File</h3> 
		<form action="" method="post" enctype="multipart/form-data" class="form-inline">
			<input type="hidden" name="token" value="<?php echo $token; ?>">
			<div class="form-group">
				<input type="text" name="title" placeholder="Audio Title here" class="form-control">
			</div>
			<div class="form-group">
				<input type="file" name="audio" class="form-control">
			</div>
			<div class="form-group">
				<input type="submit" name="add-audio" value="Add Audio" class="form-control">
			</div>
		</form>
		<?php 
		if(isset($audio_1->upload_msg)) {
			echo $audio_1->upload_msg;
		}
		?>
		<h3 class="well well-sm">Manage Audios</h3>
		<ul class="video-list">
			<?php while ($audio_single = $audio_list->fetch(PDO::FETCH_ASSOC)) : ?>
			<li>
				<div class="">
					<form action="" method="post" class="form-inline">
						<input type="hidden" name="token" value="<?php echo $token; ?>">
						<div class="form-group">
							<label>Title: </label><input type="text" name="edit-title" value="<?php echo $audio_single['title']; ?>" class="form-control">
							<input type="hidden" name="audio-id" value="<?php echo $audio_single['id'];?>">
						</div>
						
						<div class="form-group">
							<input type="submit" name="edit-audio" value="Save" class="form-control">
						</div>
						<div class="form-group">
							<input type="hidden" name="audio-id" value="<?php echo $audio_single['id']; ?>">
							<input type="submit" name="delete-audio" value="Delete Audio" class="form-control" onclick="return confirm('Are you sure you want to delete this audio?');">
						</div>
					</form>
				</div>
				<div style="position:relative; top: 5px; left: 0">File: <?php echo $audio_single['file_name']; ?></div>
			</li>
			<?php endwhile; ?>
		</ul>
	</div>
</div>

<?php include 'footer.php'; ?>