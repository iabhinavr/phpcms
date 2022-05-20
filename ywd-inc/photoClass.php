<?php

class Photo {
	
	private $con;
	
	//photo specific variables
	
	public $title;
	public $description;
	public $uploaded_date;
	public $photo_year;
	public $photo_month;
	public $photo_id;
	
	
	//photo upload variables
	
	
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
	
	public function new_photo() {
		
		$this->uploaded_date 	= date("Y-m-d");
		$date_array 	= array();
		$date_array 	= date_parse( $this->uploaded_date );
		$this->photo_year 	= $date_array['year'];
		$this->photo_month 	= $date_array['month'];
		
		$this->validatePhoto();
		
		
		if( $this->validation_status === true ) {
			
			$this->upload_photo();
			
			if( $this->upload_status === true ) {
				
				$this->create_thumbnail_312();
				
				if($this->thumbnail_status === true) {
					
					$this->insert_photo();
					$this->get_photoid();
				}
			}
			
		}
	}
	
	private function upload_photo() {
		
		
		if( !file_exists( "../ywd-uploads/photos/$this->photo_year" ) )
		{
			mkdir( "../ywd-uploads/photos/$this->photo_year" );
		}
		if( !file_exists( "../ywd-uploads/photos/$this->photo_year/$this->photo_month" ) )
		{
			mkdir( "../ywd-uploads/photos/$this->photo_year/$this->photo_month" );
		}
		if( !file_exists( "../ywd-uploads/photos/thumbnails-312/$this->photo_year" ) )
		{
			mkdir( "../ywd-uploads/photos/thumbnails-312/$this->photo_year" );
		}
		
		if( !file_exists( "../ywd-uploads/photos/thumbnails-312/$this->photo_year/$this->photo_month" ) )
		{
			mkdir( "../ywd-uploads/photos/thumbnails-312/$this->photo_year/$this->photo_month" );
		}
		if ( move_uploaded_file($this->tmp_name, "../ywd-uploads/photos/$this->photo_year/$this->photo_month/" . $this->file_name) )
		{
			$this->upload_status = true;
		}
	}
	
	public function insert_photo() {
		
		try {
			$stmt2 = $this->con->prepare("INSERT INTO photos (file_name, uploaded_date) values (:file_name, :uploaded_date)");
			$stmt2->bindParam(":file_name", $this->file_name);
			$stmt2->bindParam(":uploaded_date", $this->uploaded_date);
			$stmt2->execute();
			
		}
		catch(PDOException $e) {
    		echo $e->getMessage();
		}
	}
	
	private function validatePhoto() {
		
		
		/*
		*	Validating the file extension and mime type
		*	jpg, jpeg and png files are allowed
		*/
		
		$valid_extensions 	= array( "jpg", "jpeg");
		$photo_extension_tmp 	= explode( ".", $this->file_name);
		$photo_extension	= end( $photo_extension_tmp );
		
		if( !in_array( $photo_extension, $valid_extensions ) ) {
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
		
		if( file_exists( "../ywd-uploads/photos/$this->photo_year/$this->photo_month/" . $this->file_name ) ) {
			$this->validation_status = false;
			$this->upload_msg 	= "The image already exists!";
		}
		
	}
	
	private function create_thumbnail_312() {
		
		$src = "../ywd-uploads/photos/$this->photo_year/$this->photo_month/" . $this->file_name;
		$dest_src = "../ywd-uploads/photos/thumbnails-312/$this->photo_year/$this->photo_month/" . $this->file_name;
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
			$this->upload_msg = "Successfully uploaded the photo";
		}
		else {
			$this->thumbnail_status = false;
			$this->upload_msg 	= "The file you've chosen is incorrect! Please select a jpg, jpeg or png image to upload";
		}
		
	}
	
	public function get_photoid() {
		
		try {
		
			$stmt = $this->con->prepare("SELECT id FROM photos WHERE file_name = :file_name");
			$stmt->bindParam(':file_name', $this->file_name, PDO::PARAM_STR, 255);
			$stmt->execute();

			while( $result = $stmt->fetch(PDO::FETCH_ASSOC) ) {
				
				$this->photo_id = $result['id'];
				
			}
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
		
	}
	
	public function read_photo_byId() {
		
		try {
		
			$stmt = $this->con->prepare("SELECT * FROM photos WHERE id = :photo_id");
			$stmt->bindParam(':photo_id', $this->photo_id, PDO::PARAM_INT);
			$stmt->execute();

			while( $result = $stmt->fetch(PDO::FETCH_ASSOC) ) {
				
				$this->file_name = $result['file_name'];
				
				$this->uploaded_date = $result['uploaded_date'];
				$date_array 	= array();
				$date_array 	= date_parse( $this->uploaded_date );
				$this->photo_year 	= $date_array['year'];
				$this->photo_month 	= $date_array['month'];
				
				$this->title = $result['title'];
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
				$stmt = $this->con->prepare("UPDATE photos SET title = :title, description = :description WHERE id = :id");
				$stmt->bindParam(':id', $this->photo_id, PDO::PARAM_INT);
				$stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
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
	
	
	public function read_photo() {
		
		try {
			$stmt = $this->con->prepare("SELECT * FROM photos ORDER BY id DESC");
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

	public function delete_photo() {
		unlink("../ywd-uploads/photos/$this->photo_year/$this->photo_month/$this->file_name");
		unlink("../ywd-uploads/photos/thumbnails-312/$this->photo_year/$this->photo_month/$this->file_name");
		try
		{
			$stmt = $this->con->prepare("DELETE FROM photos WHERE id=:id");
			$stmt->bindParam(":id", $this->photo_id, PDO::PARAM_INT);
			$stmt->execute();
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
		
	
	
}