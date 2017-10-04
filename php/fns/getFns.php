<?php
    include_once('../../../fns/db_fns.php');

	// return userID and email if logged in, else return false
	function loginUser($username, $password){

   		$conn = pdo_connect();

	 	$sql = 'SELECT 	UserID, Password, Email
	 			FROM 	USER
	 			WHERE 	Email = :Email';

	 	// prepare the statement object
		$statement = $conn->prepare($sql);
		$statement->bindValue(":Email", $username, PDO::PARAM_STR);

	    $statement->execute();
	        
		//Fetch all of the results.
	    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

	    $conn = null;

	    if(empty($result)){
	    	$result = 'bad email';
	    	return $result;
	    } else if(crypt($password, $result[0]['Password']) === $result[0]['Password']){
	    	return array('userID' => $result[0]['UserID'], 'userName' => $result[0]['Email']);
	    } else {
	    	return false;
	    }
	        
	}

	function getEventNotes(){

   		$conn = pdo_connect();

	 	$sql = 'SELECT 	EventID, AltruID, Name, StartDate, StartTime, EndTime, NoteWho, NoteWhat, NoteWhere,
	 					NoteWhy, ImageSuggestions, Sponsors, CreatedOn
	 			FROM 	EVENT_NOTES';
		
		$statement = $conn->prepare($sql);
	    $statement->execute();

	    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

	    $conn = null;
	    return $result;

	}

	function getEventDrafts(){

		// create the connection
		$conn = pdo_connect();

		// write the generic statement
		$sql = '	SELECT 		Title, StartDate
		      		FROM    	EVENT
		      		LEFT JOIN 	EVENT_DATE_TIMES ON EVENT.EventID = EVENT_DATE_TIMES.EventID
		      		WHERE 		EVENT.Publish = "0"
		      		GROUP BY 	EVENT.EventID
		      		ORDER BY EVENT_DATE_TIMES.StartDate DESC';

		// prepare the statement object
		$statement = $conn->prepare($sql);

		$statement->execute();

		//Fetch all of the results.
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		// sort result by date
		$conn = null;
		return $result;
    }

	function getEventFinalCopies(){

		// create the connection
		$conn = pdo_connect();

		// write the generic statement
		$sql = '	SELECT 		Title, StartDate
		      		FROM    	EVENT
		      		LEFT JOIN 	EVENT_DATE_TIMES ON EVENT.EventID = EVENT_DATE_TIMES.EventID
		      		WHERE 		EVENT.Publish = "1"
		      		GROUP BY 	EVENT.EventID
		      		ORDER BY EVENT_DATE_TIMES.StartDate DESC';

		// prepare the statement object
		$statement = $conn->prepare($sql);

		$statement->execute();

		//Fetch all of the results.
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		// sort result by date
		$conn = null;
		return $result;
    }

    function getEventDraft($id){
  		// create the connection
		$conn = pdo_connect();

		// write the generic statement
		$sql = 'SELECT 	EventID, AltruID, Name, StartDate, StartTime, EndTime, NoteWhen, NoteWho, NoteWhat, NoteWhere,
	 					NoteWhy, ImageSuggestions, Sponsors, CreatedOn
	 			FROM 	EVENT_NOTES
	 			WHERE 	EventID = :id';

		// prepare the statement object
		$statement = $conn->prepare($sql);
		$statement->bindValue(":id", $id, PDO::PARAM_STR);

		$statement->execute();

		//Fetch all of the results.
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		// sort result by date
		$conn = null;
		return $result;	
    }

    function getChatLines($id){
    	if($id){
			$conn = pdo_connect();

			// write the generic statement
			$sql = 'SELECT 	ChatLineID, USER.UserID, USER.Fname, USER.Lname, LineText, EventNoteID, EventID, CreatedOn
		 			FROM 	CHAT_LINE
		 			LEFT JOIN 	USER ON .CHAT_LINE.UserID = USER.UserID
		 			WHERE 	EventNoteID = :id
		 			ORDER BY CreatedOn DESC';

			// prepare the statement object
			$statement = $conn->prepare($sql);
			$statement->bindValue(":id", $id, PDO::PARAM_STR);

			$statement->execute();

			//Fetch all of the results.
			$result = $statement->fetchAll(PDO::FETCH_ASSOC);

			// sort result by date
			$conn = null;
			return $result;	
    	}

    }

	function checkEmail($email){
		
		$result;

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  			$result = false; 
  			return $result;
  		}else{
			$conn = pdo_connect();

	 		$sql = 'SELECT 	U.Email as email
	 				FROM 	USER U, USER_TYPE_USER UT
	 				WHERE 	U.UserID = UT.UserID AND UT.UserTypeID = 1 AND U.Email = :email';

		 	// prepare the statement object
			$statement = $conn->prepare($sql);
			$statement->bindValue(":email", $email, PDO::PARAM_STR);

		    $statement->execute();
		        
			//Fetch all of the results.
		    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

		    $conn = null;

		    if($email === $result[0]['email']){
		    	return $result[0]['email'];
		    } else {
		    	return false;
		    }
		}

	}

    // use only when developing. 
	function addUser($email, $password, $fname, $lname){

    	$salt = "$6$" .base64_encode(openssl_random_pseudo_bytes(64));

    	if($password = crypt($password, $salt)){

	        $conn = pdo_connect();

	        $sql = 'INSERT INTO USER
	                VALUES      (null, :Email, :Password, :Fname, :Lname, null)';

	        // prepare the statement object
	        $statement = $conn->prepare($sql);

	        $statement->bindValue(":Email", $email, PDO::PARAM_STR);
	        $statement->bindValue(":Password", $password, PDO::PARAM_STR);
	        $statement->bindValue(":Fname", $fname, PDO::PARAM_STR);
	        $statement->bindValue(":Lname", $lname, PDO::PARAM_STR);
	        
	        $statement->execute();

	        $id = $conn->lastInsertId();
	        
	       	// link user id with user type in USER_TYPE_USER table
	        $sql = 'INSERT INTO USER_TYPE_USER
	                VALUES      (3, :id)';

	        // prepare the statement object
	        $statement = $conn->prepare($sql);

	        $statement->bindValue(":id", $id, PDO::PARAM_INT);
	        
	        $statement->execute();
	        
	        $conn = null;
	        
	        return true;
	    
	    }else{

	    	return false;
	    }
    }

?>
