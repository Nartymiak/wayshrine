<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

	if(checkToken()){

		
		if(!$_POST['props']){

			echo '
				<p>Chat view not loading ...</p>
			';

		}else{
			
			$json = json_decode($_POST['props']);
			$userID = $json->{'userID'};
		    $chatLines = getGeneralChatLines();

		    if(empty($chatLines)){ ?>
			    <div class="talk-bubble tri-right left-in">
			    	<p class="chatText" style="margin-bottom:0">No activity yet</p>
			    	<p class="chatName" data-userID="<?php echo $line['UserID'] ?>">Chat Bot <span><?php echo date('Y-m-d H:i:s'); ?></span></p>
		    	</div>
		    <?php

			} else {

			    foreach ($chatLines as $line) {
			    	if($line['UserID'] !== $userID){
			    	?>
			    	<div class="chatLine" cl-note-id="<?php echo $line['EventNoteID'] ?>" cl-event-id="<?php echo $line['EventID'] ?>">
				    	<div class="talk-bubble tri-right left-in" style="opacity:.66;">
				    		<div class="talktext">
				    			<p class="chatText" cl-id="<?php echo $line['ChatLineID'] ?>" style="margin-bottom:0"> <?php echo $line['LineText'] ?></p>
				    		</div>
				    	</div>
				    	<p class="chatName" data-userID="<?php echo $line['UserID'] ?>"><?php echo $line['Fname'] ?> <span><?php echo $line['CreatedOn'] ?></span></p>
				    </div>
			    	<?php
			    	} else {
			    	?>
			    	<div class="chatLine" cl-note-id="<?php echo $line['EventNoteID'] ?>" cl-event-id="<?php echo $line['EventID'] ?>">
				    	<div style="text-align:right;">
					    	<div class="talk-bubble tri-right right-in" style="text-align:left;" cl-note-id="<?php echo $line['EventNoteID'] ?>" cl-event-id="<?php echo $line['EventID'] ?>">
					    		<div class="talktext">
					    			<p class="chatText" cl-id="<?php echo $line['ChatLineID'] ?>" style="margin-bottom:0"> <?php echo $line['LineText'] ?></p>
					    		</div>
					    	</div>
					    </div>
				    	<p style="text-align:right" class="chatName" data-userID="<?php echo $line['UserID'] ?>"><?php echo $line['Fname'] ?> <span><?php echo $line['CreatedOn'] ?></span></p>
				    </div>

			    	<?php
			    	}
			    }
			}
		}
    }
?>