<?php
	include_once('../../../fns/db_fns.php');
	include_once('checkToken.php');

	if(checkToken()){

		if(!empty($_POST)){

			foreach($_POST as $post){
				if($post == ''){
					$post = NULL;
				}
			}

			if(sendNoteToDraft()){ echo 'Excellent! '.$_POST['Name']. ' has been sent to <strong>DRAFT</strong>.'; }
	    }

	}

   	function sendNoteToDraft(){

   		$newID = null;

		$conn = pdo_connect();

		$link = makeLink($_POST['Name']);

		// into EVENT
		$sql = '	INSERT INTO EVENT (EventID, Title, AdmissionCharge, AltruID, AltruButton, Link, EventNoteID, Publish)
					VALUES (null, :Title, :AdmissionCharge, :AltruID, :AltruButton, :Link, :EventNoteID, 0)';
        
        $statement = $conn->prepare($sql);
        $statement->bindValue(":Title", $_POST['Name'], PDO::PARAM_STR);
        $statement->bindValue(":AdmissionCharge", $_POST['AdmissionCharge'], PDO::PARAM_STR);
        $statement->bindValue(":AltruID", $_POST['AltruID'], PDO::PARAM_STR);
        if(isset($_POST['AltruButton']) && $_POST['AltruButton'] === '1') { $statement->bindValue(":AltruButton", 1, PDO::PARAM_INT); }
        else { $statement->bindValue(":AltruButton", 0, PDO::PARAM_INT); }
        $statement->bindValue(":Link", $link, PDO::PARAM_STR);
        $statement->bindValue(":EventNoteID", $_POST['EventID'], PDO::PARAM_STR);

        try { 
        	$statement->execute();
        	$newID = $conn->lastInsertId();
        }
        catch (Exception $e) { 
        	header('HTTP/1.0 500 Server Boo Boo: '.$e->getMessage()); 
        	die(); 
        }

        // into EVENT_DATE_TIMES
		$sql = '	INSERT INTO EVENT_DATE_TIMES (EventID, StartDate, EndDate, StartTime, EndTime)
					VALUES (:EventID, :StartDate, :EndDate, :StartTime, :EndTime)';
		
		$statement = $conn->prepare($sql);
		$statement->bindValue(":EventID", $newID, PDO::PARAM_INT);
		$statement->bindValue(":StartDate", $_POST['StartDate'], PDO::PARAM_STR);
		$statement->bindValue(":EndDate", $_POST['StartDate'], PDO::PARAM_STR);
        $statement->bindValue(":StartTime", $_POST['StartTime'], PDO::PARAM_STR);
        $statement->bindValue(":EndTime", $_POST['EndTime'], PDO::PARAM_STR);

        try { $statement->execute(); } 
        catch (Exception $e) { header('HTTP/1.0 500 Server Boo Boo: ' .$e->getMessage()); }

        $conn = null;
        return true;
    }

    function makeLink($string){
		// strip all but forward slashes, newlines, letters and numbers
		// make it all lowercase
		$link = strtolower (preg_replace('/[^A-Za-z0-9\n\/ \-]/', '', $string));
		// replace spaces, new lines and forward slashes with dashes
		$link = str_replace(array(" ", "\n", "/"), "-", $link);
		// sometimes it makes a double slash so replace those with single slash
		$link = str_replace("--", "-", $link);
		// check if last character is a dash, if so, trim it off
		if(substr($link, -1, 1)=="-"){
			$link = substr($link, 0, -1);
		}

		return $link;
    }


?>