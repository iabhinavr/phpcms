<?php

class Book {
	
	private $con;
	
	//book specific variables
	
	public $title;
	public $pages;
	public $publisher;
	public $price;
	public $description;
	public $uploaded_date;
	public $book_year;
	public $book_month;
	public $book_id;
	
	
	//book upload variables
	
	
	public $file_name;
	public $tmp_name;
	public $file_error;
	public $file_type;
	public $file_size;
	
	private $validation_status;
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
	
	public function new_book() {
		
		$this->uploaded_date 	= date("Y-m-d");
		$date_array 	= array();
		$date_array 	= date_parse( $this->uploaded_date );
		$this->book_year 	= $date_array['year'];
		$this->book_month 	= $date_array['month'];
		
		$this->validateBook();
		
		
		if( $this->validation_status === true ) {
			
			$this->upload_book();
			
			if( $this->upload_status === true ) {
				
				$this->create_thumbnail_312();
				
				if($this->thumbnail_status === true) {
					
					$this->insert_book();
					$this->get_bookid();
				}
			}
			
		}
	}
	
	private function upload_book() {
		
		
		if( !file_exists( "../ywd-uploads/books/$this->book_year" ) )
		{
			mkdir( "../ywd-uploads/books/$this->book_year" );
		}
		if( !file_exists( "../ywd-uploads/books/$this->book_year/$this->book_month" ) )
		{
			mkdir( "../ywd-uploads/books/$this->book_year/$this->book_month" );
		}
		if( !file_exists( "../ywd-uploads/books/thumbnails-312/$this->book_year" ) )
		{
			mkdir( "../ywd-uploads/books/thumbnails-312/$this->book_year" );
		}
		
		if( !file_exists( "../ywd-uploads/books/thumbnails-312/$this->book_year/$this->book_month" ) )
		{
			mkdir( "../ywd-uploads/books/thumbnails-312/$this->book_year/$this->book_month" );
		}
		if ( move_uploaded_file($this->tmp_name, "../ywd-uploads/books/$this->book_year/$this->book_month/" . $this->file_name) )
		{
			$this->upload_status = true;
		}
	}
	
	private function insert_book() {
		
		try {
			
			$stmt = $this->con->prepare("INSERT INTO books (file_name, uploaded_date) values (:file_name, :uploaded_date)");
			$stmt->bindParam(':file_name', $this->file_name, PDO::PARAM_STR, 255);
			$stmt->bindParam(':uploaded_date', $this->uploaded_date, PDO::PARAM_STR, 255);
			$stmt->execute();
			
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	private function validateBook() {
		
		
		/*
		*	Validating the file extension and mime type
		*	jpg, jpeg and png files are allowed
		*/
		
		$valid_extensions 	= array( "jpg", "jpeg");
		$book_extension_tmp 	= explode( ".", $this->file_name);
		$book_extension	= end( $book_extension_tmp );
		
		if( !in_array( $book_extension, $valid_extensions ) ) {
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
		
		if( file_exists( "../ywd-uploads/books/$this->book_year/$this->book_month/" . $this->file_name ) ) {
			$this->validation_status = false;
			$this->upload_msg 	= "The image already exists!";
		}
		
	}
	
	private function create_thumbnail_312() {
		
		$src = "../ywd-uploads/books/$this->book_year/$this->book_month/" . $this->file_name;
		$dest_src = "../ywd-uploads/books/thumbnails-312/$this->book_year/$this->book_month/" . $this->file_name;
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
			$this->upload_msg = "Successfully uploaded the book";
		}
		else {
			$this->thumbnail_status = false;
			$this->upload_msg 	= "The file you've chosen is incorrect! Please select a jpg, jpeg or png image to upload";
		}
		
	}
	
	public function get_bookid() {
		
		try {
		
			$stmt = $this->con->prepare("SELECT id FROM books WHERE file_name = :file_name");
			$stmt->bindParam(':file_name', $this->file_name, PDO::PARAM_STR, 255);
			$stmt->execute();

			while( $result = $stmt->fetch(PDO::FETCH_ASSOC) ) {
				
				$this->book_id = $result['id'];
				
			}
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
		
	}
	
	public function read_book_byId() {
		
		try {
		
			$stmt = $this->con->prepare("SELECT * FROM books WHERE id = :book_id");
			$stmt->bindParam(':book_id', $this->book_id, PDO::PARAM_INT);
			$stmt->execute();

			while( $result = $stmt->fetch(PDO::FETCH_ASSOC) ) {
				
				$this->file_name = $result['file_name'];
				
				$this->uploaded_date = $result['uploaded_date'];
				$date_array 	= array();
				$date_array 	= date_parse( $this->uploaded_date );
				$this->book_year 	= $date_array['year'];
				$this->book_month 	= $date_array['month'];
				
				$this->title = $result['title'];
				$this->pages = $result['pages'];
				$this->publisher = $result['publisher'];
				$this->price = $result['price'];
				$this->description = $result['description'];
				
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
	
	public function save_details() {
		//$this->sanitize_validate_Details();
			try {
				$stmt = $this->con->prepare("UPDATE books SET title = :title, pages = :pages, publisher = :publisher, price = :price, description = :description WHERE id = :id");
				$stmt->bindParam(':id', $this->book_id, PDO::PARAM_INT);
				$stmt->bindParam(':title', $this->title, PDO::PARAM_STR, 255);
				$stmt->bindParam(':pages', $this->pages, PDO::PARAM_INT);
				$stmt->bindParam(':publisher', $this->publisher, PDO::PARAM_STR, 255);
				$stmt->bindParam(':price', $this->price, PDO::PARAM_STR, 255);
				$stmt->bindParam(':description', $this->description, PDO::PARAM_STR);
				$save = $stmt->execute();
				if( $save ) {
					$this->saving_status = true;
				}
				else {
					$this->saving_status = false;
				}
			}
			catch(PDOException $e) {
				echo $e->getMessage();
			}
		
		
	}
	
	private function sanitize_validate_Details() {
		
		
		$this->title 			= 	filter_var($this->title, FILTER_SANITIZE_STRING);
		$this->alt 				=	filter_var($this->alt, FILTER_SANITIZE_STRING);
		$this->description 		=	filter_var($this->description, FILTER_SANITIZE_STRING);
		$this->category 		=	filter_var($this->category, FILTER_SANITIZE_STRING);
		$this->tags 			=	filter_var($this->tags, FILTER_SANITIZE_STRING);
	}
	
	
	public function read_book() {
		
		try {
			$stmt = $this->con->prepare("SELECT * FROM books ORDER BY id DESC");
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e) {
			$e->getMessage();
		}
	}

	public function delete_book() {
		unlink("../ywd-uploads/books/$this->book_year/$this->book_month/$this->file_name");
		unlink("../ywd-uploads/books/thumbnails-312/$this->book_year/$this->book_month/$this->file_name");
		try
		{
			$stmt = $this->con->prepare("DELETE FROM books WHERE id=:id");
			$stmt->bindParam(":id", $this->book_id, PDO::PARAM_INT);
			$stmt->execute();
		}
		catch(PDOException $e) {
			$e->getMessage();
		}
	}
		
	
	
}