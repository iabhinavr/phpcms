<?php
	session_start();
	if( (!isset($_SESSION['user'])) || ($_SESSION['user'] === '') )
	{
		header('Location:login.php');
		die("Redirecting to login page");
	}

	define('IncAccess46z891155jkl', TRUE);
	include '../ywd-inc/functions.php';
	$page_title = 'SEO';
	$body_class = 'seo';

	include_once '../ywd-inc/databaseClass.php';
	include_once '../ywd-inc/seoClass.php';

	$database 	= 	new Database();
	$db_con 	= 	$database->db_connect();

	$seo	=	new Seo($db_con);
if(isset($_GET['id'])) {
	$seo_exists = $seo->read_seo_byid( (int)$_GET['id'] );
}
else {
	$seo_exists = false;
}
if(isset($_POST['save-seo'])) {
	if(isset($_SESSION['token']) && !empty($_SESSION['token']) && $_POST['token'] == $_SESSION['token']) {
		$seo->title_tag = htmlspecialchars($_POST['title']);
		$seo->meta_desc  = htmlspecialchars($_POST['meta-desc']);
		$seo->meta_keyword  = htmlspecialchars($_POST['meta-keyword']);
		$seo->id    = (int)$_POST['id'];
		$seo->update_seo();
	}
}

$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;

?><?php include 'header.php'; ?>

<div class="admin-content-wrap">
	<?php if($seo_exists) : ?>
	
	<div class="admin-title"><h3>Optimize your page for search engines: </h3></div>
	
	<?php $seo->read_seo_byid( (int)$_GET['id'] ); ?>
	<h4>Current Page: <?php echo ucfirst($seo->page) . " Page"; ?></h4>
	<form class="edit-seo" method="post" action="">
		<input type="hidden" name="token" value="<?php echo $token; ?>">
		<label>Title Tag (optimum 50 to 60 characters): </label>
		<input type="text" name="title" value="<?php echo $seo->title_tag; ?>" maxlength="100">
		<label>Meta Description (maximum length is 200 characters - optimum is 160): </label>
		<textarea name="meta-desc" maxlength="200"><?php echo $seo->meta_desc; ?></textarea>
		<label>Meta Keywords (optional): </label>
		<textarea name="meta-keyword"><?php echo $seo->meta_keyword; ?></textarea>
		<input type="hidden" name="id" value="<?php echo $seo->id; ?>">
		<input type="submit" name="save-seo" value="Save">
	</form>
	
	<?php else : ?>
	
	<div class="admin-title"><h1>SEO for individual pages: </h1></div>
	<?php $seo_list = $seo->read_seo(); ?>
	<ol class="content-list">
		<?php while ( $seo_single = $seo_list->fetch(PDO::FETCH_ASSOC) ) : ?>
			<li><?php echo ucfirst($seo_single['page']) . " - "; ?><a href="seo.php?id=<?php echo $seo_single['id']; ?>"><?php echo $seo_single['title_tag']; ?></a></li>
		<?php endwhile; ?>
	</ol>
	
	<?php endif; ?>
	
</div>

<?php include 'footer.php'; ?>