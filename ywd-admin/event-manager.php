<?php
session_start();
if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
{
	header('Location:login.php');
	die("Redirecting to login page");
}
define('IncAccess46z891155jkl', TRUE);
include '../ywd-inc/functions.php';
$page_title = 'Events';
$body_class = 'event-manager';


include_once '../ywd-inc/databaseClass.php';
include_once '../ywd-inc/eventClass.php';

$database 	= 	new Database();
$db_con 	= 	$database->db_connect();

$event_1	=	new Event($db_con);

if(isset($_POST['add-event'])) {
	if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
		$event_1->title = htmlspecialchars($_POST['title']);
		$event_1->venue = htmlspecialchars($_POST['venue']);
		$event_1->start_date = htmlspecialchars($_POST['start-date']);
		$event_1->end_date = htmlspecialchars($_POST['end-date']);
		$event_1->description = htmlspecialchars($_POST['description']);

		$event_1->new_event();
	}
}

$event_2	= 	new Event($db_con);
$results	=	$event_2->read_event();

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>

	<div class="admin-content-wrap">
		
		<div class="admin-title"><h1>Add and Edit Events</h1></div>
		
		<h3>Add new Event</h3>
		<form class="form-event" action="" method="post">
			<input type="hidden" name="token" value="<?php echo $token; ?>">
			<div class="form-group">
				<label>Name of the Event: </label>
				<input type="text" name="title" placeholder="event name..." class="form-control">
			</div>
			<div class="form-group">
				<label>Venue: </label>
				<input type="text" name="venue" placeholder="venue..." class="form-control">
			</div>
			<div class="form-group">
				<label>Start-Date: </label>
				<input class="pick-date form-control" type="text" name="start-date" placeholder="click to add date" readonly>
			</div>
			<div class="form-group">
				<label>End-Date: </label>
				<input class="pick-date form-control" type="text" name="end-date" placeholder="click to add date" readonly>
			</div>
			<div class="form-group">
				<label>Event Description: </label>
				<textarea name="description" placeholder="event details..." class="form-control"></textarea>
			</div>
			
			<input type="submit" name="add-event" value="Add Event" class="form-control">
		</form>
		
		<h3>List of events</h3>
		<ol class="event-list">
			<?php while($result = $results->fetch(PDO::FETCH_ASSOC)) : ?>
			<li><a href="edit-event.php?id=<?php echo $result['id']; ?>"><?php echo $result['title']; ?></a></li>
			<?php endwhile; ?>
		</ol>
			
	</div><!--.admin-content-wrap-->

<script>
	$(function(){
		$('.pick-date').datepicker({
			dateFormat: "yy-mm-dd"
		});
	});
</script>

<?php include 'footer.php'; ?>