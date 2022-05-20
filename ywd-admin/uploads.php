<?php
session_start();
if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
{
	header('Location:login.php');
	die("Redirecting to login page");
}
header('Content-Type: application/json');
include '../ywd-inc/databaseClass.php';
include '../ywd-inc/articleClass.php';

$database 	= 	new Database();
$db_con 	= 	$database->db_connect();

$article_3 = new Article($db_con);
if(isset($_POST['page-no'])) {
		if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
			$article_3->article_id = (int)$_POST['article-id'];
			$article_3->file_name = $_FILES['page']['name'];
			$article_3->tmp_name = $_FILES['page']['tmp_name'];
			$article_3->file_type = $_FILES['page']['type'];
			$article_3->file_size = $_FILES['page']['size'];
			$article_3->file_error = $_FILES['page']['error'];

			$article_3->page_no = (int)$_POST['page-no'];

			$article_3->new_page();
			$page_list = $article_3->read_pages();
			$token = $_POST['token'];
			$out = "";
			if($page_list) {
			while ($page_single = $page_list->fetch(PDO::FETCH_ASSOC)) {
			$out .= "<li>
				<div class='updated'>
					<form action='' method='post' class='form-inline'>
						<input type='hidden' name='token' value='" . $token . "'>
						<div class='form-group'>
							<img class='av-img' src='../ywd-uploads/articles/thumbnails-312/" . $page_single['file_name'] . "'>
						</div>
						<div class='form-group'>
							<label>Page Number: </label><input type='text' name='page-no' value='" . $page_single['page_no'] . "' class='form-control'>
						</div>
						<input type='hidden' name='page-file' value='" . $page_single['file_name'] . "'>
						<input type='hidden' name='page-id' value='" .  $page_single['id'] . "'>
						<div class='form-group'>
							<input type='submit' name='edit-page' value='Save' class='form-control'>
						</div>
						<div class='form-group'>
							<input type='submit' name='delete-page' value='Delete Page' class='form-control' onclick=\"return confirm('Are you sure you want to delete this page?');\">
						</div>
					</form>
				</div>
			</li>";
			}
			}
			$resp = array("msg" => $article_3->upload_msg,
			 "st" => $article_3->upload_status, "out" => $out);
			echo json_encode($resp);
		}
	
	else {
		$resp = array("msg" => "Token Error",
			 "st" => false, "out" => "Output error");
			echo json_encode($resp);
	}
}
else {
	$resp = array("msg" => "Upload Error",
			 "st" => false, "out" => "Output error");
			echo json_encode($resp);
}
?>