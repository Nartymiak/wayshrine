<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

	if(checkToken()){ 
		if(!$_POST['props']){

			echo '
				<p>Something went wrong. Please refresh this form.</p>
			';

		}else{
			// begin payload
			$json = json_decode($_POST['props']);
			$userID = $json->{'userID'};
			$noteID = $json->{'noteID'};
			$eventID = $json->{'eventID'};

			$draft = getEventDraft($id);
		?>

			<div id="noteChatView"></div>
			<form id="noteChatForm">
				<input name="EventNoteID" type="hidden" class="form-control" value="<?php echo $noteID;?>">
				<input name="EventID" type="hidden" class="form-control" value="<?php echo $eventID;?>">
				<input name="UserID" type="hidden" class="form-control" value="<?php echo $userID;?>">
				    <div class="input-group">
						<input name="LineText" id="chatLine" type="text" class="form-control" placeholder="enter text...">
						<span class="input-group-btn">
							<button class="btn btn-default" type="submit" id="chatBtn">submit</button>
						</span>
					</div>
			</form>

<?php 
		}
	}
?>