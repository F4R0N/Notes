<?php
include "../mysqllogin.php";

class notes {
	
	private $db;					// database connection
	private $notes = []; 			// array: all notes
	private $note_count;
	
	function __construct(){
		$db = new db();
		if(!$db->is_connected()){
			die("MySQL Error!");
		}
		$this->db = $db;
		$this->get_all_notes();
	}
	
	function create($note, $x, $y){
		$this->db->insert("notes", ["note"=>$note, "positionx"=>$x, "positiony"=>$y]);
	}
	
	function update($id, $note){
		$this->db->update("notes", ["note"=>$note], ["id"=>$id]);
	}
	
	function move($id, $x, $y){
		$this->db->update("notes", ["positionx"=>$x, "positiony"=>$y], ["id"=>$id]);
	}
	
	function delete($id){
		$this->db->delete("notes", ["id"=>$id]);
	}
	
	private function get_all_notes(){
		$result = $this->db->select("notes");
		while($row = $result->fetch_assoc()){
			array_push($this->notes, ["id"=>$row["id"], "note"=>$row["note"], "positionx"=>$row["positionx"], "positiony"=>$row["positiony"]]);
		}
		$this->note_count = count($this->notes);
	}
	
	function get_notes(){
		return $this->notes;
	}
	
	function get_note_count(){
		return $this->note_count;
	}
	
	
}


?>