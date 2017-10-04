<?php
    include_once('../../../fns/db_fns.php');


	// return userID and email if logged in, else return false
	function loginUser($username, $password){

		$result = array();

   		$conn = pdo_connect();

	 	$sql = 'SELECT 		USER.UserID, Password, Email, UserTypeID
	 			FROM 		USER
	 			LEFT JOIN 	USER_TYPE_USER ON USER.UserID = USER_TYPE_USER.UserID
	 			WHERE 		Email = :Email';

	 	// prepare the statement object
		$statement = $conn->prepare($sql);
		$statement->bindValue(":Email", $username, PDO::PARAM_STR);

	    $statement->execute();
	        
		//Fetch all of the results.
	    $sqlResult = $statement->fetchAll(PDO::FETCH_ASSOC);


	    $conn = null;

	    if(empty($sqlResult)){
	    	return false;
	    
	    } else if(crypt($password, $sqlResult[0]['Password']) === $sqlResult[0]['Password']){
	    	
	    	foreach ($sqlResult as $s){ array_push($result, array('userID' => $s['UserID'], 'userName' => $s['Email'], 'UserTypeID' => $s['UserTypeID'])); }
	    	return $result;
	    
	    } else {
	    	return false;
	    }     
	}

    function exists($name){

    	$conn = pdo_connect();
		
		// write the generic statement
		$sql = '	SELECT 	EventID
		      		FROM    EVENT
		      		WHERE 	Title = :Name';

        $statement = $conn->prepare($sql);
        $statement->bindValue(":Name", $name, PDO::PARAM_STR);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $conn = null;
        return $result = $result[0];
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
		$sql = '	SELECT 		EVENT.EventID, Title, StartDate
		      		FROM    	EVENT
		      		LEFT JOIN 	EVENT_DATE_TIMES ON EVENT.EventID = EVENT_DATE_TIMES.EventID
		      		WHERE 		EVENT.Publish = "0"
		      		GROUP BY 	EVENT.EventID
		      		ORDER BY 	EVENT_DATE_TIMES.StartDate DESC';

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

    function getNoteWorkspace($id){
  		// create the connection
		$conn = pdo_connect();

		// write the generic statement
		$sql = 'SELECT 	EventID, AltruID, Name, StartDate, StartTime, EndTime, AdmissionCharge, AltruButton, NoteWhen, NoteWho, NoteWhat, NoteWhere,
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

 	function getDraftWorkspace($id){
  		// create the connection
		$conn = pdo_connect();

		// write the generic statement
		$sql = 'SELECT 		EVENT.EventID, Title, Description, Blurb, AdmissionCharge, RegistrationEndDate, 
							EventTypeID, ContactPerson, ImgFilePath, ImgCaption, Sponsors, AltruID, AltruButton, 
							Link, EventNoteID, Publish, StartDate, StartTime, EndTime
	 			FROM 		EVENT
		      	LEFT JOIN 	EVENT_DATE_TIMES ON EVENT.EventID = EVENT_DATE_TIMES.EventID
	 			WHERE 		EVENT.EventID = :id';

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

    function getWorkSpaceChatLines($id){
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

    function getGeneralChatLines(){

		$conn = pdo_connect();

		// write the generic statement
		$sql = 'SELECT 	ChatLineID, USER.UserID, USER.Fname, USER.Lname, LineText, EventNoteID, EventID, CreatedOn
	 			FROM 	CHAT_LINE
	 			LEFT JOIN 	USER ON .CHAT_LINE.UserID = USER.UserID
	 			ORDER BY CreatedOn ASC';

		// prepare the statement object
		$statement = $conn->prepare($sql);
		$statement->execute();

		//Fetch all of the results.
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		// sort result by date
		$conn = null;
		return $result;	


    }

    function getEventTypes(){
		$conn = pdo_connect();

		// write the generic statement
		$sql = 'SELECT 	KeywordID, Word
	 			FROM 	KEYWORD
	 			ORDER BY Word ASC';

		// prepare the statement object
		$statement = $conn->prepare($sql);
		$statement->execute();

		//Fetch all of the results.
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		// sort result by date
		$conn = null;
		return $result;	
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
	                VALUES      (4, :id)';

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
