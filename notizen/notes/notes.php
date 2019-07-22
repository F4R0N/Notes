<?php
	include "notes.class.php";
	$notes = new notes();
	
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Notes</title>
		<style>
			body{
				background-color: #eee;
			}
			
			a {
				text-decoration: none;
			}
			
			.note {
				display: block;
				position: absolute;
				background-color: #EEA;
				padding: 6px;
				border: solid 1px yellow;
				box-shadow: 0px 0px 3px #AAA;
				max-width: 500px;
				overflow: auto;
			}
		
			.noteheader {
				text-align: right;
				border-bottom: thin solid #bbb;
				padding-bottom: 4px;
				margin-bottom: 2px;
				cursor: move;
				background-color: #E8E8A8;
			}
			
			.note textarea {
				background-color: #FFE;
			}
			
			#add {
				display: block;
				position: absolute;
				top: 20px;
				right: 25px;
				font-size: 44px;
				background-color: #595;
				width: 50px;
				height: 50px;
				text-align: center;
				cursor: pointer;
			}
			
			#addNoteForm {
				display: none;
				position: absolute;
				right: 0;
				left: 0;
				top: 0;
				bottom: 30%;
				margin:auto;
				background-color: #ddd;
				padding: 6px;
				width: 300px;
				height: 320px;
				text-align: center;
				z-index: 9999;
			}
			
			#addNoteForm .header {
				text-align: right;
			}
			
			#addNoteForm form textarea {
				width: 258px;
				height: 194px;
				margin-bottom: 5px;
			}
			
			#taskbar {
				position: fixed;
				bottom: 0;
				left: 0;
				right: 0;
				height: 1.3em;
				padding: 3px;
				background-color: #595;
				border-top: thin solid #666;
			}
			#taskbar div {
				display: inline;
			}
			
			#time {
				float:right;
			}
		</style>
		<script>
		
			// http://jsfiddle.net/wfbY8/4/
			
			var currentMovingNote;
			var offset;
			
			var currentUpdatingNote;
			
			function startMove(note, event){
				//note.style.cursor = "grabbing";
				currentMovingNote = note;
				offset = [event.layerX, event.layerY];
				window.addEventListener('mouseup', mouseUp, false);
				window.addEventListener('mousemove', noteMove, true);
				
			}
			
			function noteMove(event){
				currentMovingNote.parentNode.style.top = event.clientY - offset[1] + "px";
				currentMovingNote.parentNode.style.left = event.clientX - offset[0] + "px";
			}
			
			function mouseUp(){
				//currentMovingNote.style.cursor = "move";
				window.removeEventListener("mousemove", noteMove, true);
				window.removeEventListener('mouseup', mouseUp, false);
				updateNotePos();
			}
			
			function mouseDown(){
				window.addEventListener('mousemove', noteMove, true);
			}
			
			function updateNotePos(){
				var request = new XMLHttpRequest();
				request.open("GET", "manageNotes.php?updatePosition=true&posx=" + currentMovingNote.parentNode.style.left + "&posy=" + currentMovingNote.parentNode.style.top + "&id=" + currentMovingNote.parentNode.id, true);
				request.send(null);
			}
			
			function deleteNote(note){
				if(confirm("Delete this note? ID: " + note.parentNode.parentNode.id)){
					var request = new XMLHttpRequest();
					request.open("GET", "manageNotes.php?delete=true&id=" + note.parentNode.parentNode.id, true);
					request.send(null);
					note.parentNode.parentNode.remove();
				}
			}
			
			function editNote(note){
				if (note != currentUpdatingNote){
					
					var width = note.offsetWidth;
					
					note.innerHTML = "<textarea name=\"note\">" + note.innerText + "</textarea>";
					
					note.children[0].style.width = width + "px";
					//note.children[0].style.height = height + "px";
					note.children[0].style.height = note.children[0].scrollHeight + "px";
					
					currentUpdatingNote = note;
					window.addEventListener('mousedown', updateNote, true);
				}
			}
			
			function updateNote(event){
				if (event.target != currentUpdatingNote.children[0] && currentUpdatingNote != ""){
					var text = currentUpdatingNote.childNodes[0].value;
					currentUpdatingNote.innerText = text;
					
					var paraText = encodeURIComponent(text);
					var paraID = currentUpdatingNote.parentNode.id;
					
					var request = new XMLHttpRequest();
					request.open("POST", "manageNotes.php", true);
					request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					request.send("update=true&id=" + paraID + "&note=" + paraText);
					
					currentUpdatingNote = "";
					window.removeEventListener('mousedown', updateNote, true);
				}
			}
			
			
		</script>
	</head>
	<body>
		<div id="add" onclick="document.getElementById('addNoteForm').style.display = 'block';">+</div>
		<div id="addNoteForm">
			<div class="header"><a href="" onclick="document.getElementById(\"addNoteForm\").style.display = \"none\";">x</a></div>
			<h3>New Note</h3>
			<hr>
			<form action="manageNotes.php" name="create" method="POST">
				<textarea name="note"></textarea>
				<input type="number" hidden="true" name="posx" value="444">
				<input type="number" hidden="true" name="posy" value="222">
				<button name="create" type="submit" value="1">create</button>
			</form>
		</div>
			<?php foreach( $notes->get_notes() as $note): ?>
				<div id="<?=$note["id"]?>" style="left: <?=$note["positionx"]?>px; top: <?=$note["positiony"]?>px;" class="note">
					<div onmousedown="startMove(this, event)" class="noteheader"><a href="">_</a> <a href="" onclick="deleteNote(this)">x</a></div>
					<div class="notebody" onclick="editNote(this)"><?=nl2br(htmlspecialchars($note["note"]))?></div>
				</div>
			<?php endforeach; ?>
		<div id="taskbar">
			<div id="resetPos">reset positions</div>
			<div id="noteCount"> notes: <?=$notes->get_note_count();?></div>
			<div id="time"><?=date("G:i:s", time())?></div>
		</div>
	</body>
</html>