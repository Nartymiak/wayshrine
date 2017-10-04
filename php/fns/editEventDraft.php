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
            echo "Excellent! " .$_POST['Title']. " has been updated.";
        }
    }

   	function editEvent(){

        $errors = array('event' => array(), 'dateTimes' => array(), 'relatedExhibition' => array());
        $dateCount = count($_POST['StartDate']);

        $conn = pdo_connect();

        $sql = 'UPDATE 	EVENT 
        		SET  	Title = :Title, 
        				Description = :Description, 
                        AdmissionCharge = :AdmissionCharge,
                        EventTypeID = :EventTypeID,
        				Sponsors = :Sponsors, 
        				AltruID = :AltruID,
        				AltruButton = :AltruButton,
                        AltruLink = :AltruLink,
                        RegistrationEndDate = :RegistrationEndDate,
                        OkToPub = :OkToPub,
                        Print = :Print,
        				Publish = 0
                WHERE  	EventID = :EventID';

        $statement = $conn->prepare($sql);
        $statement->bindValue(":Title", $_POST['Title'], PDO::PARAM_STR);
        $statement->bindValue(":Description", $_POST['Description'], PDO::PARAM_STR);
        $statement->bindValue(":AdmissionCharge", $_POST['AdmissionCharge'], PDO::PARAM_STR);
        $statement->bindValue(":EventTypeID", $_POST['EventTypeID'], PDO::PARAM_STR);
        $statement->bindValue(":Sponsors", $_POST['Sponsors'], PDO::PARAM_STR);
        $statement->bindValue(":AltruID", $_POST['AltruID'], PDO::PARAM_STR);
        $statement->bindValue(":AltruLink", $_POST['AltruLink'], PDO::PARAM_STR);
        if(isset($_POST['AltruButton']) && $_POST['AltruButton'] === '1') { $statement->bindValue(":AltruButton", 1, PDO::PARAM_INT); }
        else { $statement->bindValue(":AltruButton", 0, PDO::PARAM_INT); }
        if(isset($_POST['RegistrationCheck']) && $_POST['RegistrationCheck'] === '1') { $statement->bindValue(":RegistrationEndDate", $_POST['RegistrationEndDate'], PDO::PARAM_STR); }
        else { $statement->bindValue(":RegistrationEndDate", NULL, PDO::PARAM_STR); }
        if(isset($_POST['OkToPubCheck']) && $_POST['OkToPubCheck'] === '1') { $statement->bindValue(":OkToPub", $_POST['UserID'], PDO::PARAM_INT); }
        else { $statement->bindValue(":OkToPub", 0, PDO::PARAM_INT); }
        if(isset($_POST['PrintCheck']) && $_POST['PrintCheck'] === '1') { $statement->bindValue(":Print", $_POST['Print'], PDO::PARAM_STR); }
        else { $statement->bindValue(":Print", NULL, PDO::PARAM_STR); }


        $statement->bindValue(":EventID", $_POST['EventID'], PDO::PARAM_INT);

        try { $statement->execute(); }

        catch (Exception $e){ array_push($errors['event'], $e->getMessage()); }
        
        if($dateCount > 0){

            $sql = 'DELETE
                    FROM    EVENT_DATE_TIMES
                    WHERE   EventID = :EventID';
            $statement = $conn->prepare($sql);
            $statement->bindValue(":EventID", $_POST['EventID'], PDO::PARAM_INT);

            try { $statement->execute(); }
            catch (Exception $e){ array_push($errors['dateTimes'], $e->getMessage()); }

            $sql = 'INSERT INTO EVENT_DATE_TIMES (EventID, StartDate, EndDate, StartTime, EndTime)
                    VALUES (:EventID, :StartDate, :EndDate, :StartTime, :EndTime)';
            $statement = $conn->prepare($sql);
            
            for($i=0;$i<$dateCount;$i++){

                $statement->bindValue(":EventID", $_POST['EventID'], PDO::PARAM_INT);
                $statement->bindValue(":StartDate", $_POST['StartDate'][$i], PDO::PARAM_STR);
                $statement->bindValue(":EndDate", $_POST['StartDate'][$i], PDO::PARAM_STR);
                $statement->bindValue(":StartTime", $_POST['StartTime'][$i], PDO::PARAM_STR);
                $statement->bindValue(":EndTime", $_POST['EndTime'][$i], PDO::PARAM_STR);
                
                try { $statement->execute(); }
                catch (Exception $e){ array_push($errors['dateTimes'], $e->getMessage()); }
            }
        }

        if($_POST['ExhibitionID'] !== NULL){


            $sql = 'DELETE
                    FROM    EXHIBITION_EVENTS
                    WHERE   EventID = :EventID';
            $statement = $conn->prepare($sql);
            $statement->bindValue(":EventID", $_POST['EventID'], PDO::PARAM_INT);

            try { $statement->execute(); }
            catch (Exception $e){ array_push($errors['relatedExhibition'], $e->getMessage()); }

            $sql = 'INSERT INTO EXHIBITION_EVENTS (ExhibitionID, EventID)
                    VALUES (:ExhibitionID, :EventID)';
            $statement = $conn->prepare($sql);
            $statement->bindValue(":EventID", $_POST['EventID'], PDO::PARAM_INT);
            $statement->bindValue(":ExhibitionID", $_POST['ExhibitionID'], PDO::PARAM_INT);

            try { $statement->execute(); }
            catch (Exception $e){ array_push($errors['relatedExhibition'], $e->getMessage()); }

        } else {
            $sql = 'DELETE
                    FROM    EXHIBITION_EVENTS
                    WHERE   EventID = :EventID';
            $statement = $conn->prepare($sql);
            $statement->bindValue(":EventID", $_POST['EventID'], PDO::PARAM_INT);

            try { $statement->execute(); }
            catch (Exception $e){ array_push($errors['relatedExhibition'], $e->getMessage()); }
        }

        if(!empty($errors['event'])){
            var_dump($errors['event']);
        }

        $conn = null;
        return true;
    }
?>