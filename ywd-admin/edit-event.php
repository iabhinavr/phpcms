<?php
	session_start();
	if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
	{
		header('Location:login.php');
		die("Redirecting to login page");
	}

	define('IncAccess46z891155jkl', TRUE);
	include '../ywd-inc/functions.php';
	$page_title = 'Edit Event';
	$body_class = 'edit-event';


	include_once '../ywd-inc/databaseClass.php';
	include_once '../ywd-inc/eventClass.php';

	$database 	= 	new Database();
	$db_con 	= 	$database->db_connect();

	if( isset( $_GET['id'] ) ) {
		
		$event 				= 	new Event($db_con);
		$event->event_id 	= 	(int)$_GET['id'];
		$event_exists 		= 	$event->read_event_byId();
		
	}
	else {
		$event_exists = false;
	}

	if( isset( $_POST['save-details'] ) ) {
		
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$event->event_id	=	(int)$_POST['event_id'];
			$event->title 		= 	htmlspecialchars($_POST['title']);
			$event->venue 		= 	htmlspecialchars($_POST['venue']);
			$event->start_date 	= 	htmlspecialchars($_POST['start-date']);
			$event->end_date 	= 	htmlspecialchars($_POST['end-date']);
			$event->description	=	htmlspecialchars($_POST['description']);

			$event->update_event();
		}
	}
	if( isset( $_POST['delete-event'] ) ) {
		
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$event->event_id	=	(int)$_POST['del_id'];
			$event->delete_event();
			header("Location:event-manager.php");
			die();
		}
	}

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>

	<div class="admin-content-wrap edit-event">
		
		<div class="msg-wrap">
		
			<?php if (isset ($event->saving_status)) : ?>

				<?php if( $event->saving_status == true ) : ?>
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
		
		
		<div class="admin-title"><h2 class="well well-sm">Edit Event Details</h2></div>
		
		<?php if( $event_exists === true ) : ?>
		
			<form action="" method="post" class="details-form">
				<input type="hidden" name="token" value="<?php echo $token; ?>">
				<label>Title: </label>
					<input type="text" name="title" value="<?php echo $event->title; ?>">
				<label>Venue: </label>
					<input type="text" name="venue" value="<?php echo $event->venue; ?>">
				<label>Start-Date: </label>
					<input class="pick-date" type="text" name="start-date" value="<?php echo $event->start_date; ?>" readonly>
				<label>End-Date: </label>
					<input class="pick-date" type="text" name="end-date" value="<?php echo $event->end_date; ?>" readonly>
				<label>Description (Maximum of 200 characters): </label>
					<textarea name="description" maxlength="200"><?php echo $event->description; ?></textarea>
				<input type="hidden" name="event_id" value="<?php echo $event->event_id; ?>">
				<input type="submit" name="save-details" value="Save">
			</form>
			
		
		<form method="post" action="" class="details-form" onsubmit="return confirm('Are you sure you want to delete this media?');">
			<input type="hidden" name="token" value="<?php echo $token; ?>">
			<input type="hidden" name="del_id" value="<?php echo $event->event_id; ?>">
			<input type="submit" name="delete-event" value="Delete Event">
		</form>
		
		<?php else : ?>
			<p>Nothing found!</p>
		<?php endif; ?>
	
	</div><!--.admin-content-wrap-->
<script>
	$(function(){
		$('.pick-date').datepicker({
			dateFormat: "yy-mm-dd"
		});
	});
</script>

<?php include 'footer.php'; ?>