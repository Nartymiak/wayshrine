<?php
	include_once('../../../fns/db_fns.php');

	if(!empty($_POST)){

		foreach($_POST as $post){
			if($post == ''){
				$post = NULL;
			}
		}

		if(addChat()){
            return true;
        } else {
        	return false;
        } 
    }else{
    	return false;
    }

   	function addChat(){

        $conn = pdo_connect();

        $sql = 'INSERT INTO CHAT_LINE (
						UserID,
						LineText,
		                EventNoteID,
		                EventID
                )
                VALUES(	
                		:UserID, 
        				:LineText,
                        :EventNoteID,
                        :EventID
                )';

        // prepare the statement object
        $statement = $conn->prepare($sql);

        $statement->bindValue(":UserID", $_POST['UserID'], PDO::PARAM_INT);
        $statement->bindValue(":LineText", $_POST['LineText'], PDO::PARAM_STR);
        $statement->bindValue(":EventNoteID", $_POST['EventNoteID'], PDO::PARAM_STR);
        $statement->bindValue(":EventID", $_POST['EventID'], PDO::PARAM_STR);

        $statement->execute();

        $conn = null;
        return true;

    }
?>