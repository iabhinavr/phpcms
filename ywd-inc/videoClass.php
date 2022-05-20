<?php

class Video {
	private $con;
	
	public $video_id;
	public $title;
	public $youtube_id;
	
	function __construct($db_con) {
		$this->con = $db_con;
	}
	
	public function new_video() {
		try {
			$stmt = $this->con->prepare("INSERT INTO videos (title, youtube_id) values (:title, :youtube_id)");
			$stmt->bindParam(":title", $this->title, PDO::PARAM_STR, 255);
			$stmt->bindParam(":youtube_id", $this->youtube_id, PDO::PARAM_STR, 255);
			$stmt->execute();
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	public function read_video() {
		try {
			$stmt = $this->con->prepare("SELECT * FROM videos");
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	public function update_video() {
		try {
			$stmt = $this->con->prepare("UPDATE videos SET title=:title, youtube_id=:youtube_id WHERE id=:id");
			$stmt->bindParam(":title", $this->title, PDO::PARAM_STR, 255);
			$stmt->bindParam(":youtube_id", $this->youtube_id, PDO::PARAM_STR, 255);
			$stmt->bindParam(":id", $this->video_id, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	public function delete_video() {
		try {
			$stmt = $this->con->prepare("DELETE FROM videos WHERE id=:id");
			$stmt->bindParam(":id", $this->video_id, PDO::PARAM_INT);
			$stmt->execute();
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
}