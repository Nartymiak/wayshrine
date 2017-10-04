<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

	if(checkToken()){

		if(!$_POST['props']){

			echo '
				<p>Chat view not loading ...</p>
			';

		}else{
			// begin payload
			$json = json_decode($_POST['props']);
			$userID = $json->{'userID'};
			$noteID = $json->{'noteID'};
			$eventID = $json->{'eventID'};

		    $chatLines;
		    // begin payload
		    $chatLines = getChatLines($noteID);

		    if(empty($chatLines)){ ?>
		    	<p class="chatText" style="margin-bottom:0">No activity yet</p>
		    	<p class="chatName" data-userID="<?php echo $line['userID'] ?>">Chat Bot <span><?php echo date('Y-m-d H:i:s'); ?></span></p>
		    <?php

			} else {

			    foreach ($chatLines as $line) {
			    	?>
			    		<p class="chatText" style="margin-bottom:0"> <?php echo $line['LineText'] ?></p>
			    		<p class="chatName" data-userID="<?php echo $line['userID'] ?>"><?php echo $line['Fname'] ?> <span><?php echo $line['CreatedOn'] ?></span></p>
			    	<?php
			    }
			}
		}
    }
?>