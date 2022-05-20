<?php
Class Event {
	private $con;
	
	public $event_id;
	public $title;
	public $venue;
	public $start_date;
	public $end_date;
	public $description;
	
	public $saving_status;
	
	function __construct($db_con) {
		$this->con = $db_con;
	}
	
	public function new_event() {
		try {
			$stmt = $this->con->prepare("INSERT INTO events (title, venue, start_date, end_date, description) values (:title, :venue, :start_date, :end_date, :description)");
			$stmt->bindParam(":title", $this->title, PDO::PARAM_STR, 255);
			$stmt->bindParam(":venue", $this->venue, PDO::PARAM_STR, 255);
			$stmt->bindParam(":start_date", $this->start_date, PDO::PARAM_STR, 255);
			$stmt->bindParam(":end_date", $this->end_date, PDO::PARAM_STR, 255);
			$stmt->bindParam(":description", $this->description, PDO::PARAM_STR, 255);
			$stmt->execute();
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	public function read_event() {
		try {
			$stmt = $this->con->prepare("SELECT * FROM events ORDER BY id DESC");
			$stmt->execute();
			return $stmt;			
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	public function read_event_byId() {
		try {
			$stmt = $this->con->prepare("SELECT * FROM events WHERE id = :id");
			$stmt->bindParam(":id", $this->event_id, PDO::PARAM_STR, 255);
			$stmt->execute();
			while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$this->title = $result['title'];
				$this->venue = $result['venue'];
				$this->start_date = $result['start_date'];
				$this->end_date = $result['end_date'];
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
	
	public function read_current_event() {
		$today_1 = date("Y-m-d");
		$today_2 = $today_1;
		try {
			$stmt = $this->con->prepare("SELECT * FROM events WHERE start_date <= :today_1 AND end_date >=:today_2 ORDER BY start_date DESC");
			$stmt->bindParam(":today_1", $today_1, PDO::PARAM_STR, 255);
			$stmt->bindParam(":today_2", $today_2, PDO::PARAM_STR, 255);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	public function read_upcoming_event() {
		$today_1 = date("Y-m-d");
		try {
			$stmt = $this->con->prepare("SELECT * FROM events WHERE start_date > :today_1 ORDER BY start_date ASC");
			$stmt->bindParam(":today_1", $today_1, PDO::PARAM_STR, 255);
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	public function update_event() {
		try {
			$stmt = $this->con->prepare("UPDATE events SET title = :title, venue = :venue, start_date = :start_date, end_date = :end_date, description = :description WHERE id = :id");
			$stmt->bindParam(":id", $this->event_id, PDO::PARAM_INT);
			$stmt->bindParam(":title", $this->title, PDO::PARAM_STR, 255);
			$stmt->bindParam(":venue", $this->venue, PDO::PARAM_STR, 255);
			$stmt->bindParam(":start_date", $this->start_date, PDO::PARAM_STR, 255);
			$stmt->bindParam(":end_date", $this->end_date, PDO::PARAM_STR, 255);
			$stmt->bindParam(":description", $this->description, PDO::PARAM_STR, 255);
			$save = $stmt->execute();
			if($save)
				$this->saving_status = true;
			else
				$this->saving_staus = false;
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	public function delete_event() {
		try {
			$stmt = $this->con->prepare("DELETE FROM events WHERE id = :id");
			$stmt->bindParam(":id", $this->event_id, PDO::PARAM_STR, 255);
			$stmt->execute();
		}
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
}