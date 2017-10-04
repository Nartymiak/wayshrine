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

        $errors = array('event' => array(), 'dateTimes' => array());
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

        if(!empty($errors['dateTimes'])){
            var_dump($errors['dateTimes']);
        }

        $conn = null;
        return true;
    }
?>