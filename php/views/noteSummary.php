<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

	if(checkToken()){

		if(!$_POST['props']){

		}else{
			// begin payload
			$json = json_decode($_POST['props']);
			$id = $json->{'noteID'};
			$userTypes = $json->{'userTypes'};
			$note = getNoteWorkspace($id);
			$existsID = exists($note[0]['Name']);
		?>

			<div class="row">
		 		<div class="col-sm-12">
		 			<h3 class="viewTitle">Notes (<?php echo $note[0]['Name'] ?>)</h3>
		 		</div>
		 	</div>
			<div class="row">
		 		<div class="col-sm-12">
		 			<label>Who</label>
		 			<p><?php echo $note[0]['NoteWho'] ?></p>
		 			<label>What</label>
		 			<p><?php echo $note[0]['NoteWhat'] ?></p>
		 			<label>Where</label>
		 			<p><?php echo $note[0]['NoteWhere'] ?></p>
		 			<label>Why</label>
		 			<p><?php echo $note[0]['NoteWhy'] ?></p>
		 			<label>Other Dates &amp; Times / Date Range</label>
		 			<p><?php echo $note[0]['NoteWhen'] ?></p>
		 			<label>Image Suggestions</label>
		 			<p><?php echo $note[0]['ImageSuggestions'] ?></p>
		 		</div>
		 	</div>


			
<?php
		}
	}
?>