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
			$noteName = $json->{'noteName'};
		?>


		 	<h3 class="viewTitle">Messages<span id="messagesFor"></span></h3>
			<div id="generalChatView"></div>
			<form id="generalChatForm">
				<input name="EventNoteID" type="hidden" class="form-control" value="<?php echo $noteID;?>">
				<input name="EventID" type="hidden" class="form-control" value="<?php echo $eventID;?>">
				<input name="UserID" type="hidden" class="form-control" value="<?php echo $userID;?>">
			    <div class="input-group">
					<input name="LineText" id="generalChatLine" type="text" class="form-control" placeholder="enter text...">
					<span class="input-group-btn">
						<button class="btn btn-default" type="submit" id="generalChatBtn">submit</button>
					</span>
				</div>
			</form>

<?php 
		}
	}
?>