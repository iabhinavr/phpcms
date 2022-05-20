<?php

class Audio {
	
	private $con;
	
	//audio specific variables
	
	public $title;
	public $uploaded_date;
	public $audio_year;
	public $audio_month;
	public $audio_id;
	
	
	//audio upload variables
	
	
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
	
	public function new_audio() {
		
		$this->uploaded_date 	= date("Y-m-d");
		$date_array 	= array();
		$date_array 	= date_parse( $this->uploaded_date );
		$this->audio_year 	= $date_array['year'];
		$this->audio_month 	= $date_array['month'];
		
		$this->validateAudio();
		
		
		if( $this->validation_status === true ) {
			
			$this->upload_audio();
			
			if( $this->upload_status === true ) {
					
				$this->insert_audio();
				$this->get_audioid();
			}
			
		}
	}
	
	private function upload_audio() {
		
		
		if( !file_exists( "../ywd-uploads/audios/$this->audio_year" ) )
		{
			mkdir( "../ywd-uploads/audios/$this->audio_year" );
		}
		if( !file_exists( "../ywd-uploads/audios/$this->audio_year/$this->audio_month" ) )
		{
			mkdir( "../ywd-uploads/audios/$this->audio_year/$this->audio_month" );
		}
		if ( move_uploaded_file($this->tmp_name, "../ywd-uploads/audios/$this->audio_year/$this->audio_month/" . $this->file_name) )
		{
			$this->upload_status = true;
		}
	}
	
	public function insert_audio() {
		
		try {
			$stmt2 = $this->con->prepare("INSERT INTO audios (title, file_name, uploaded_date) values (:title, :file_name, :uploaded_date)");
			$stmt2->bindParam(":title", $this->title);
			$stmt2->bindParam(":file_name", $this->file_name);
			$stmt2->bindParam(":uploaded_date", $this->uploaded_date);
			$stmt2->execute();
			
		}
		catch(PDOException $e) {
    		echo $e->getMessage();
		}
	}
	
	private function validateAudio() {
		
		
		/*
		*	Validating the file extension and mime type
		*	jpg, jpeg and png files are allowed
		*/
		
		$valid_extensions 	= array( "mp3");
		$audio_extension_tmp 	= explode( ".", $this->file_name);
		$audio_extension	= end( $audio_extension_tmp );
		
		if( !in_array( $audio_extension, $valid_extensions ) ) {
			$this->validation_status = false;
			$this->upload_msg 	= "The file you've chosen is incorrect type! Please select mp3 audio file to upload";
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
		
		if( file_exists( "../ywd-uploads/audios/$this->audio_year/$this->audio_month/" . $this->file_name ) ) {
			$this->validation_status = false;
			$this->upload_msg 	= "The audio file already exists!";
		}
		
	}
	
	public function get_audioid() {
		
		try {
		
			$stmt = $this->con->prepare("SELECT id FROM audios WHERE file_name = :file_name");
			$stmt->bindParam(':file_name', $this->file_name, PDO::PARAM_STR, 255);
			$stmt->execute();

			while( $result = $stmt->fetch(PDO::FETCH_ASSOC) ) {
				
				$this->audio_id = $result['id'];
				
			}
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
		
	}
	
	public function read_audio_byId() {
		
		try {
		
			$stmt = $this->con->prepare("SELECT * FROM audios WHERE id = :audio_id");
			$stmt->bindParam(':audio_id', $this->audio_id, PDO::PARAM_INT);
			$stmt->execute();

			while( $result = $stmt->fetch(PDO::FETCH_ASSOC) ) {
				
				$this->file_name = $result['file_name'];
				
				$this->uploaded_date = $result['uploaded_date'];
				$date_array 	= array();
				$date_array 	= date_parse( $this->uploaded_date );
				$this->audio_year 	= $date_array['year'];
				$this->audio_month 	= $date_array['month'];
				
				$this->title = $result['title'];
				
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
	
	public function update_audio() {
		$this->sanitize_validate_Details();
		
			try {
				$stmt = $this->con->prepare("UPDATE audios SET title = :title WHERE id = :id");
				$stmt->bindParam(':id', $this->audio_id, PDO::PARAM_INT);
				$stmt->bindParam(':title', $this->title, PDO::PARAM_STR);
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
	
	private function sanitize_validate_Details() {
		$this->title 			= 	filter_var($this->title, FILTER_SANITIZE_STRING);
	}
	
	
	public function read_audio() {
		
		try {
			$stmt = $this->con->prepare("SELECT * FROM audios ORDER BY id DESC");
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e) {
			$e->getMessage();
		}
	}

	public function delete_audio() {
		unlink("../ywd-uploads/audios/$this->audio_year/$this->audio_month/$this->file_name");
		try
		{
			$stmt = $this->con->prepare("DELETE FROM audios WHERE id=:id");
			$stmt->bindParam(":id", $this->audio_id, PDO::PARAM_INT);
			$stmt->execute();
		}
		catch(PDOException $e) {
			$e->getMessage();
		}
	}
		
	
	
}