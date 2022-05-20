<?php
	session_start();
	if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
	{
		header('Location:login.php');
		die("Redirecting to login page");
	}

	define('IncAccess46z891155jkl', TRUE);
	include '../ywd-inc/functions.php';
	$page_title = "Manage Account";
	$body_class = "manage-account";

	include_once '../ywd-inc/databaseClass.php';
	include_once '../ywd-inc/userClass.php';

	$database 	= 	new Database();
	$db_con 	= 	$database->db_connect();

	$user 		=	new User($db_con);
	$user->username = $_SESSION['user'];
	$user->readuser();

if(isset($_POST['update-account']))
{
	if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
		$user->current_password 	= htmlspecialchars($_POST['cur-password']);
		$user->new_password			= htmlspecialchars($_POST['new-password']);
		$user->retype_password  	= htmlspecialchars($_POST['retype-password']);

		$pass_status = $user->update_user();
	}
}

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>

<div class="admin-content-wrap">
	<div class="admin-title"><h1>Your account details: </h1></div>
	
	<form class="account-update" action="" method="post">
				<label>First name (Permanent): </label>
                <input type="text" name="uname" value="<?php echo $user->first_name; ?>" readonly>
				<label>Last name (Permanent): </label>
                <input type="text" name="uname" value="<?php echo $user->last_name; ?>" readonly>
           		<label>Your Username (Permanent): </label>
                <input type="text" name="uname" value="<?php echo $user->username; ?>" readonly>
                <label>Your E-Mail (Permanent): </label>
                <input type="text" name="email" value="<?php echo $user->email; ?>" readonly>
                <h3>Change Password :</h3>
                <label>Current Password * :</label>
                <input type="password" name="cur-password" required>
                <label>New Password * :</label>
                <input id="new-password" type="password" name="new-password" required>
                <label>Re-type Password * :</label>
                <input id="re-password" type="password" name="retype-password" required>
				<div id="re-status"></div>
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <input type="submit" name="update-account" value="Save">
     </form>
	<div class="pass-status">
		<?php 
			if(isset($pass_status)) echo $pass_status;
		?>
	</div>
	
</div>
<script>
	$('#re-password').keyup(function() {
		var password1 = $('#new-password').val();
		var password2 = $('#re-password').val();
		
		if(password2 == password1) {
			$('#re-status').html("<img src='admin-assets/images/success.png'>Passwords matched");
		}
		else {
			$('#re-status').html("<img src='admin-assets/images/warning.png'>Passwords don't match");
		}
	});
</script>
<?php include 'footer.php'; ?>