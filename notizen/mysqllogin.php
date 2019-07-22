<?php
class db {
	private $connection;
	
	function __construct(){
		$this->connection = new mysqli("", "", "", "");
	}
	
	public function is_connected(){
		return !$this->connection->connect_error;
	}
	
	public function update($table, $values, $where){
		//$table = where, $values = array: $column => $value, $where = array $column => $value
		
		$sql = "UPDATE " . $this->connection->real_escape_string($table) . " SET ";
		
		$counter = 0;
		$end = count($values);
		foreach ($values as $col => $val){
			$counter++;
			$sql .= $this->connection->real_escape_string($col) . "='" . $this->connection->real_escape_string($val) . "'";
			if($counter < $end){
				$sql .= ", ";
			} else {
				$sql .= " ";
			}
		}
		
		$sql .= " WHERE " . $this->connection->real_escape_string(key($where)) . "='" . $this->connection->real_escape_string($where[key($where)]) . "'";
		
		//print_r($sql . "<br>");
		
		$this->connection->query($sql);
		
		return $this->connection->error;
	}
	
	public function select($table, $where=0){
		//$table = where, $values = array: $column => $value, $where = array $column => $value
		
		$sql = "SELECT * FROM " . $this->connection->real_escape_string($table) . " ";
		
		if ($where != 0){
			$sql .= " WHERE " . $this->connection->real_escape_string(key($where)) . "='" . $this->connection->real_escape_string($where[key($where)]) . "'";
		}
		//print_r($sql . "<br>");
		
		return $this->connection->query($sql);
	}
	
	public function delete($table, $where){
		//$table = where, $where = array $column => $value
		
		$sql = "DELETE FROM " . $this->connection->real_escape_string($table) . " ";
		
		$sql .= " WHERE " . $this->connection->real_escape_string(key($where)) . "='" . $this->connection->real_escape_string($where[key($where)]) . "'";
		
		//print_r($sql . "<br>");
		
		$this->connection->query($sql);
		
		return $this->connection->error;
	}
	
	public function insert($table, $values){
		$sql = "INSERT INTO " . $this->connection->real_escape_string($table) . " ";
		
		$counter = 0;
		$end = count($values);
		$col_values = "(";
		$val_values = "Values (";
		foreach ($values as $col => $val){
			$counter++;
			$col_values .= $this->connection->real_escape_string($col);
			$val_values .= "'" . $this->connection->real_escape_string($val) . "'";
			if($counter < $end){
				$col_values .= ", ";
				$val_values .= ", ";
			}
		}
		$col_values .= ") ";
		$val_values .= ");";
		
		$sql .= $col_values . $val_values;
		
		//print_r($sql . "<br>");
		
		$this->connection->query($sql);
		
		return $this->connection->error;
	}
	
	function __destruct(){
		$this->connection->close();
	}
}
?>