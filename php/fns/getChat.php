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

		    echo json_encode($chatLines);
		}
    }
?>