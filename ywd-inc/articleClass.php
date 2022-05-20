<?php

class Article {

	private $con;

	//article specific variables

	public $title;
	public $article_id;
	public $publisher;

	public $page_no;
	public $page_id;


	//article upload variables


	public $file_name;
	public $tmp_name;
	public $file_error;
	public $file_type;
	public $file_size;

	public $validation_status;
	public $upload_status;
	public $thumbnail_status;
	public $upload_msg;

	public $saving_status;

	function __construct($db_con) {
		$this->con 				= $db_con;
		$this->validation_status 	= true;
		$this->upload_status 	= false;
		$this->upload_msg		= "";
	}

	public function new_article() {
		try{
			$stmt = $this->con->prepare("INSERT into articles (title, publisher) values (:title, :publisher)");
			$stmt->bindParam(':title', $this->title, PDO::PARAM_STR, 255);
			$stmt->bindParam(':publisher', $this->publisher, PDO::PARAM_STR, 255);
			$stmt->execute();
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function read_article() {
		try {
			$stmt = $this->con->prepare("SELECT * FROM articles");
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function update_article() {
		try {
				$stmt = $this->con->prepare("UPDATE articles SET title = :title, publisher = :publisher WHERE id = :id");
				$stmt->bindParam(':id', $this->article_id, PDO::PARAM_INT);
				$stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
				$stmt->bindParam(':publisher', $this->publisher, PDO::PARAM_STR);
				$save = $stmt->execute();
				if( $save ) {
					$this->saving_status = true;
				}
				else {
					$this->saving_status = false;
				}
			}
			catch(PDOException $e) {
				$e->getMessage();
			}
	}

	public function read_article_byId() {

		try {

			$stmt = $this->con->prepare("SELECT * FROM articles WHERE id = :article_id");
			$stmt->bindParam(':article_id', $this->article_id, PDO::PARAM_INT);
			$stmt->execute();

			while( $result = $stmt->fetch(PDO::FETCH_ASSOC) ) {

				$this->title = $result['title'];
				$this->publisher = $result['publisher'];

			}

			if( $stmt->rowCount() === 0 ) {
				return false;
			}
			else {
				return true;
			}

		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}

	}

	public function read_articles_by_publisher($publisher = "") {
		try {
			$stmt = $this->con->prepare("SELECT * FROM articles WHERE publisher = :publisher");
			$stmt->bindParam(":publisher", $publisher, PDO::PARAM_STR, 255);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function delete_article() {

		try {
			$stmt = $this->con->prepare("DELETE FROM articles WHERE id = :id");
			$stmt->bindParam(':id', $this->article_id, PDO::PARAM_INT);
			$stmt->execute();
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}

		try {
			$stmt2 = $this->con->prepare("SELECT * FROM article_pages WHERE article_id = :article_id");
			$stmt2->bindParam(':article_id', $this->article_id, PDO::PARAM_INT);
			$stmt2->execute();
			while($page = $stmt2->fetch(PDO::FETCH_ASSOC)) {
				$page_file = $page['file_name'];
				unlink("../ywd-uploads/articles/$page_file");
				unlink("../ywd-uploads/articles/thumbnails-312/$page_file");
			}
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}

		try {
			$stmt3 = $this->con->prepare("DELETE FROM article_pages WHERE article_id = :article_id");
			$stmt3->bindParam(':article_id', $this->article_id, PDO::PARAM_INT);
			$stmt3->execute();
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function new_page() {

		$this->validatePage();


		if( $this->validation_status === true ) {

			$this->upload_page();

			if( $this->upload_status === true ) {

				$this->create_thumbnail_312();

				if($this->thumbnail_status === true) {

					$this->insert_page();
					//$this->get_pageid();
				}
			}

		}
	}

	private function upload_page() {

		if ( move_uploaded_file($this->tmp_name, "../ywd-uploads/articles/" . $this->file_name) )
		{
			$this->upload_status = true;
		}
	}

	private function insert_page() {
		try {

			$stmt = $this->con->prepare("INSERT INTO article_pages (article_id, page_no, file_name) values (:article_id, :page_no, :file_name)");
			$stmt->bindParam(':article_id', $this->article_id, PDO::PARAM_INT);
			$stmt->bindParam(':page_no', $this->page_no, PDO::PARAM_INT);
			$stmt->bindParam(':file_name', $this->file_name, PDO::PARAM_STR, 255);
			$stmt->execute();

		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	private function validatePage() {


		/*
		*	Validating the file extension and mime type
		*	jpg, jpeg and png files are allowed
		*/

		$valid_extensions 	= array( "jpg", "jpeg");
		$page_extension_tmp 	= explode( ".", $this->file_name);
		$page_extension	= end( $page_extension_tmp );

		if( !in_array( $page_extension, $valid_extensions ) ) {
			$this->validation_status = false;
			$this->upload_msg 	= "The file you've chosen is incorrect! Please select a jpg or jpeg image to upload";
		}
		if( !( $this->file_type == 'image/jpg' || $this->file_type == 'image/jpeg' ) )
		{
			$this->validation_status = false;
			$this->upload_msg 	= "The file you've chosen is incorrect! Please select a jpg or jpeg image to upload";
		}

		/*
		*	Maximum allowed size is 50MB
		*/

		if( $this->file_size >= 50000000 ) {
			$this->validation_status = false;
			$this->upload_msg 	= "The image is huge! Please select an image less than 50 MB in size";
		}

		/*
		*	Validating the file name for unwanted characters
		*/

		$this->file_name = str_replace(" ", "-", $this->file_name);
		if( !preg_match("/^[a-zA-Z0-9-_.]+$/", $this->file_name) ) {
			$this->validation_status = false;
			$this->upload_msg 	= "The file name can contain only letters, digits, hyphens, dots and underscores! Spaces will be replaced with hyphen.";
		}

		/*
		*	Check if the file already exists in the ywd-uploads folder
		*/

		if( file_exists( "../ywd-uploads/articles/" . $this->file_name ) ) {
			$this->validation_status = false;
			$this->upload_msg 	= "The file name already exists. Rename it or choose another one!";
		}

	}

	private function create_thumbnail_312() {

		$src = "../ywd-uploads/articles/" . $this->file_name;
		$dest_src = "../ywd-uploads/articles/thumbnails-312/" . $this->file_name;
		$orig_image = imagecreatefromjpeg($src);

		if( $orig_image ) {
			$orig_width = imagesx($orig_image);
			$orig_height = imagesy($orig_image);
			$new_width = 312;
			$new_height = floor(($orig_height / $orig_width) * $new_width);
			$new_image = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($new_image, $orig_image, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);
			imagejpeg($new_image, $dest_src, 80);
			$this->thumbnail_status = true;
			$this->upload_msg = "Successfully uploaded the file";
		}
		else {
			$this->thumbnail_status = false;
			$this->upload_msg 	= "The file you've chosen is incorrect!";
		}

	}

	public function read_pages() {
		try {
			$stmt = $this->con->prepare("SELECT * FROM article_pages WHERE article_id = :id ORDER BY page_no");
			$stmt->bindParam(":id", $this->article_id, PDO::PARAM_INT);
			$stmt->execute();
			if($stmt->rowCount() == 0) {
				return false;
			}
			else {
				return $stmt;
			}
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function update_page() {
		try {
			$stmt = $this->con->prepare("UPDATE article_pages SET page_no = :page_no WHERE id = :id");
			$stmt->bindParam(":page_no", $this->page_no, PDO::PARAM_INT);
			$stmt->bindParam(":id", $this->page_id, PDO::PARAM_INT);
			$stmt->execute();
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function delete_page() {
		unlink("../ywd-uploads/articles/$this->file_name");
		unlink("../ywd-uploads/articles/thumbnails-312/$this->file_name");
		try
		{
			$stmt = $this->con->prepare("DELETE FROM article_pages WHERE id=:id");
			$stmt->bindParam(":id", $this->page_id, PDO::PARAM_INT);
			$stmt->execute();
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function read_publishers() {
		try {
			$stmt = $this->con->prepare("SELECT DISTINCT publisher FROM articles ORDER BY id DESC");
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function list_publishers() {
		$publisher_list = $this->read_publishers();
		$publishers = array("Mathrubhumi", "Malayala Manorama", "Janmabhumi", "Madhyamam", "Mangalam", "Kerala Kaumudi", "Deepika", "Chandrika", "Kalakaumudi", "Deshabhimani", "Manorama Weekly", "Mathrubhumi Weekly", "Madhyamam Weekly", "Mangalam Weekly", "Bhashaposhini", "Jayakeralam", "Keraleeyam", "India Today", "The Hindu", "The Indian Express", "Puzha", "Yathra - Mathrubhumi", "Dina Thanthi", "Dinakaran", "Dinamalar");
		while($publisher_single = $publisher_list->fetch(PDO::FETCH_ASSOC)) {

			$publisher_name = $publisher_single['publisher'];;
			if(!in_array($publisher_name, $publishers)) {
				$publishers[] = $publisher_name;
			}
		}
		return json_encode($publishers);
	}

}
