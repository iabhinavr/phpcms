<?php

class Seo {
	
	private $con;
	
	public $id;
	public $page;
	public $title_tag;
	public $meta_desc;
	public $meta_keyword;
	
	function __construct($db_con)
	{
		$this->con = $db_con;
	}
	
	public function read_seo() {
		
		try
		{
			$stmt = $this->con->prepare("SELECT * FROM seo");
			$stmt->execute();
			return $stmt;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
		
	}
	
	public function read_seo_byid($id) {
		try
		{
			$stmt = $this->con->prepare("SELECT * FROM seo WHERE id = :id");
			$stmt->bindParam(":id", $id, PDO::PARAM_INT);
			$stmt->execute();
			while($result = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				$this->title_tag = $result['title_tag'];
				$this->page  = $result['page'];
				$this->meta_desc  = $result['meta_desc'];
				$this->meta_keyword = $result['meta_keyword'];
				$this->id    = $result['id'];
			}
			if($stmt->rowCount() > 0) {
				return true;
			}
			else {
				return false;
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function update_seo() {
		try
		{
			$stmt = $this->con->prepare("UPDATE seo set title_tag = :title_tag, meta_desc = :meta_desc, meta_keyword = :meta_keyword WHERE id = :id");
			$stmt->bindParam(":title_tag", $this->title_tag, PDO::PARAM_STR);
			$stmt->bindParam(":meta_desc", $this->meta_desc, PDO::PARAM_STR);
			$stmt->bindParam(":meta_keyword", $this->meta_keyword, PDO::PARAM_STR);
			$stmt->bindParam(":id", $this->id, PDO::PARAM_INT);
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}