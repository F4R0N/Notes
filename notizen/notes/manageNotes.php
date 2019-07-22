<?php

	include "notes.class.php";
	
	$notes = new notes();

	if(isset($_POST["create"])){
		// check for empty fields
		if(strlen($_POST["note"]) > 1 && is_numeric($_POST["posx"]) && is_numeric($_POST["posy"]) && $notes->get_note_count() < 10){
			$notes->create($_POST["note"], (int)$_POST["posx"], (int)$_POST["posy"]);
		}
		header("location: notes.php");
	}

	if(isset($_GET["delete"])){
		if(isset($_GET["id"]) && (int)is_numeric($_GET["id"])){
			$notes->delete((int)$_GET["id"]);
			echo "deleted: " . (int)$_GET["id"];
		}
	}
	
	if(isset($_POST["update"])){
		if(isset($_POST["id"]) && (int)is_numeric($_POST["id"]) && isset($_POST["note"]) && strlen($_POST["note"]) > 1){
			$notes->update((int)$_POST["id"], $_POST["note"]);
			echo "updated: " . (int)$_POST["id"];
		}
	}
	
	if(isset($_GET["updatePosition"])){
		if(isset($_GET["id"]) && (int)is_numeric($_GET["id"]) && isset($_GET["posx"]) && is_numeric((int)$_GET["posx"]) && isset($_GET["posy"]) && is_numeric((int)$_GET["posy"])){
			$notes->move((int)$_GET["id"], (int)$_GET["posx"], (int)$_GET["posy"]);
			echo "new pos: " + (int)$_GET["posx"] + " " + (int)$_GET["posy"];
		}
	}
?>