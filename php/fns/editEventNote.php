<?php
	include_once('../../../fns/db_fns.php');
    include_once('checkToken.php');

	if(!empty($_POST)){

		foreach($_POST as $post){
			if($post == ''){
				$post = NULL;
			}
		}

		if(editEvent()){
            echo "Excellent! " .$_POST['Name']. " has been updated.";
        }
    }

   	function editEvent(){

        $conn = pdo_connect();

        $sql = 'UPDATE 	EVENT_NOTES 
        		SET  	Name = :Name,
                        EventTypeID = :EventTypeID,
                        ExhibitionID = :ExhibitionID, 
        				StartDate = :StartDate, 
        				StartTime = :StartTime,
                        EndTime = :EndTime,
                        RegistrationEndDate = :RegistrationEndDate,
                        AdmissionCharge = :AdmissionCharge,
                        AltruButton = :AltruButton,
                        AltruLink = :AltruLink,
                        NoteWhen = :NoteWhen,
        				NoteWho = :NoteWho, 
        				NoteWhat = :NoteWhat, 
        				NoteWhere = :NoteWhere, 
        				NoteWhy = :NoteWhy,
                        ImageSuggestions = :ImageSuggestions,
        				Sponsors = :Sponsors
                WHERE  	EventID = :EventID';

        // prepare the statement object
        $statement = $conn->prepare($sql);

        $statement->bindValue(":Name", $_POST['Name'], PDO::PARAM_STR);
        $statement->bindValue(":EventTypeID", $_POST['EventTypeID'], PDO::PARAM_INT);
        $statement->bindValue(":ExhibitionID", $_POST['ExhibitionID'], PDO::PARAM_INT);
        $statement->bindValue(":StartDate", $_POST['StartDate'], PDO::PARAM_STR);
        $statement->bindValue(":StartTime", $_POST['StartTime'], PDO::PARAM_STR);
        if($_POST['EndTime'] === NULL) { $statement->bindValue(":EndTime", NULL, PDO::PARAM_STR); }
        else { $statement->bindValue(":EndTime", $_POST['EndTime'], PDO::PARAM_STR); }
        if($_POST['RegistrationEndDate'] === NULL) { $statement->bindValue(":RegistrationEndDate", NULL, PDO::PARAM_STR); }
        else { $statement->bindValue(":RegistrationEndDate", $_POST['RegistrationEndDate'], PDO::PARAM_STR); }
        $statement->bindValue(":AdmissionCharge", $_POST['AdmissionCharge'], PDO::PARAM_STR);
        $statement->bindValue(":AltruLink", $_POST['AltruLink'], PDO::PARAM_STR);
        if(isset($_POST['AltruButton']) && $_POST['AltruButton'] === '1') { $statement->bindValue(":AltruButton", 1, PDO::PARAM_INT); }
        else { $statement->bindValue(":AltruButton", 0, PDO::PARAM_INT); }
        $statement->bindValue(":NoteWhen", $_POST['NoteWhen'], PDO::PARAM_STR);
        $statement->bindValue(":NoteWho", $_POST['NoteWho'], PDO::PARAM_STR);
        $statement->bindValue(":NoteWhat", $_POST['NoteWhat'], PDO::PARAM_STR);
        $statement->bindValue(":NoteWhere", $_POST['NoteWhere'], PDO::PARAM_STR);
        $statement->bindValue(":NoteWhy", $_POST['NoteWhy'], PDO::PARAM_STR);
        $statement->bindValue(":ImageSuggestions", $_POST['ImageSuggestions'], PDO::PARAM_STR);
        $statement->bindValue(":Sponsors", $_POST['Sponsors'], PDO::PARAM_STR);
        $statement->bindValue(":EventID", $_POST['EventID'], PDO::PARAM_STR);

        $statement->execute();

        $conn = null;
        return true;

    }
?>