<?php
	include_once('../../../fns/db_fns.php');
    include_once('checkToken.php');

    if(checkToken()){

        try {
            $_POST['ImgCaption'] = cleanSummernoteEmptiness($_POST['ImgCaption']);

            if(!empty($_FILES['inputFilePreview']['name']) || $_POST['ImgFileName'] !== ''){

                if($_POST['ImgFileName'] !== ''){
                    $postFileName = pathinfo($_POST['ImgFileName']);
                    $postFileName = $postFileName['filename']. '.' .$postFileName['extension'];
                } else {
                    $postFileName = null;
                }
                
                if(file_exists('../../../images/event-page-images/' .$postFileName)){
                    updateEventImagesList($postFileName);
                    if(empty($_POST['ImgCaption'])){
                         echo '<strong>Great!</strong> This event has been updated to use '.$postFileName.', a previously uploaded image and to <strong>NOT</strong> have a caption.';
                    } else {
                        echo '<strong>Great!</strong> This event has been updated to use '.$postFileName.', a previously uploaded image and for the caption to read "'.$_POST['ImgCaption'].'".';
                    }

                }else if(file_exists('../../../images/event-page-images/' .$_FILES['inputFilePreview']['name'])){
                    updateEventImagesList($_FILES['files']['name']);
                    if(empty($_POST['ImgCaption'])){
                         echo '<strong>Great!</strong> This event has been updated to use '.$_FILES['inputFilePreview']['name'].', a previously uploaded image and to <strong>NOT</strong> have a caption.';
                    } else {
                        echo '<strong>Great!</strong> This event has been updated to use '.$_FILES['inputFilePreview']['name'].', a previously uploaded image and for the caption to read "'.$_POST['ImgCaption'].'".';
                    }

                }else if(!fileHasErrors()){ 
        	        // get parts
        	        $temp = pathinfo($_FILES['inputFilePreview']['name']);
        	        //format filename
        	        $fileName = toLink(urldecode($postFileName));
        	        $fileExtension = strtolower($temp['extension']);
        	        $filePath = $fileName.'.'.$fileExtension;

                    if(updateEventImagesList($filePath)){
                        try {
                            if(move_uploaded_file($_FILES['inputFilePreview']['tmp_name'], '../../../images/event-page-images/' .$filePath)){
                               echo '<strong>Success!</strong> This event has been updated with the use of '.$filePath.'. A file with this name cannot be uploaded again.';
                           }
                        }
                        catch(Exception $e){
                            header('HTTP/1.0 500 Server Boo Boo: '.$e->getMessage());
                            die();
                        }
                    }
        	    }
            } else {
                $filePath = null;
                if(updateEventImagesList($filePath)){
                    if(empty($_POST['ImgCaption'])){
                         echo '<strong>Ok!</strong> You have update this event to <strong>NOT</strong> have an image or caption.';
                    } else {
                        echo '<strong>Ok!</strong> You have removed this image and updated the caption to read "'.$_POST['ImgCaption']. '."';
                    }
                }
               
            }
        }
        catch(Exception $e){
            header('HTTP/1.0 500 Server Boo Boo: '.$e->getMessage());
        }
	}



	function fileHasErrors(){

        $valid_mime_types = array("image/gif", "image/jpeg");
        $valid_file_extensions = array(".jpg", ".gif");
        $file_extension = strtolower(strrchr($_FILES["inputFilePreview"]["name"], "."));

        try {
            if( 0 < $_FILES['inputFilePreview']['error'] ) {
                throw new Exception("There was a connection error while uploading. Please try again.");
            }
        }

        catch(Exception $e){
            header('HTTP/1.0 500 Server Boo Boo: '.$e->getMessage());
            die();
        }
        
        try {
        	if(!in_array($_FILES["inputFilePreview"]["type"], $valid_mime_types)) {
            	throw new Exception("Your image file is the wrong type. Try to save your file as a .jpg or .gif.");
       		}else if (!in_array($file_extension, $valid_file_extensions)) {
            	throw new Exception("Your image file has the wrong extension. Try to save your file as a .jpg or .gif.");
        	}
        	if($_FILES['inputFilePreview']['size'] > 150000){
            	throw new Exception("Your image file is too large. Try to upload a file that is under 150Kb.");
        	}
        }
        catch(Exception $e){
            header('HTTP/1.0 500 Server Boo Boo: '.$e->getMessage());
            die();
        }

        return false;
    }

    function updateEventImagesList($imgFilePath){

        $conn = pdo_connect();
        
        $sql = 'UPDATE  EVENT 
                SET     ImgFilePath = :ImgFilePath,
                        ImgCaption = :ImgCaption
                WHERE   EventID = :EventID';

        $statement = $conn->prepare($sql);
        $statement->bindValue(":ImgFilePath", $imgFilePath, PDO::PARAM_STR);
        $statement->bindValue(":ImgCaption", $_POST['ImgCaption'], PDO::PARAM_STR);
        $statement->bindValue(":EventID", $_POST['EventID'], PDO::PARAM_STR);

        try { $statement->execute(); }

        catch (Exception $e){ echo $e->getMessage();}

        $conn = null;
        return true;
    }

    function toLink($string){
        // remove file extension (strip characters that are a dot followed by three or four characters which are not a dot or a space)
        $link = preg_replace('/\\.[^.\\s]{3,4}$/', '', $string);
        // replace "&" with "and"
        $link = str_replace("&", "and", $link);
        // strip all but forward slashes, newlines, letters and numbers
        // make it all lowercase
        $link = strtolower ( preg_replace('/[^A-Za-z0-9\n\/ \-]/', '', $link));
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

    function cleanSummernoteEmptiness($string){
        if($string === '<p><br></p>'){
            return null;
        } else {
            return $string;
        }
    }

 ?>