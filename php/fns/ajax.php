<?php
    include_once('../../../fns/db_fns.php');
   
    if(!empty($_POST['Email'])){
       if (newEntry()){

        mailMessageTo('artymiakn@nbmaa.org');

        echo '  <div class="row cadnSection">
                    <div style="padding: 8px;box-sizing: border-box;margin-bottom:8px;" class="row alert-success">
                            <span class="col-sm-10">
                                <strong>Thank you ' .$_POST['Fname']. '!</strong><br> We have received your information.
                            </span>
                            <span class="col-sm-2 text-right">
                                <span style="font-size: 1.75em;" class="glyphicon glyphicon-thumbs-up"></span>
                            </span>
                    </div>
                    <div style="padding: 8px;box-sizing: border-box;margin-bottom:8px;" class="row alert-success">
                        <div class="col-sm-12">
                            <p><strong>Please remember to complete your registration by sending your check payable to <strong style="text-decoration:underline;">CADN.</strong> Mail to:</strong><br><br>Sheila Kinscherf, CADN Treasurer<br>77 Twilight Drive<br>Madison, CT 06443</p>
                        </div>
                    </div>
                    <div style="padding: 8px;box-sizing: border-box;margin-bottom:8px;" class="row alert-success" id="selection-box" >
                        <div class="row">
                            <div class="col-sm-12">
                                <h3>You have chosen:</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <p><strong>Spinout sessions</strong></p>
                                <ol>
                                    <li>' .$_POST['SpinOutFirst']. '</li>
                                    <li>' .$_POST['SpinOutSecond']. '</li>
                                    <li>' .$_POST['SpinOutThird']. '</li>
                                </ol>
                            </div>
                            <div class="col-sm-6">
                                <p><strong>Lunch Tour</strong></p>
                                <ol>
                                    <li>' .$_POST['LunchTourFirst']. '</li>
                                    <li>' .$_POST['LunchTourSecond']. '</li>
                                    <li>' .$_POST['LunchTourThird']. '</li>
                                </ol>
                            </div>
                        </div>
                        <div class="row text-center" style="margin-top:10px;"><a id="print-button" class="btn btn-primary" role="button" href="javascript:window.print()">print</a></div> 
                    </div>
                </div>
        ';
       } else {
        echo '<div style="padding: 15px;box-sizing: border-box;" class="alert-danger">There was an error processing your request, please try again.</div>';
       }
    } else {
        echo '<div style="padding: 15px;box-sizing: border-box;" class="alert-danger">you have not entered a valid email.</div>';
    }

    function newEntry(){

        $conn = pdo_connect();

        $sql = 'INSERT INTO CADN2017_SUBMISSION
                VALUES      (null, :Fname, :Lname, :Phone, :Email, :Museum, :Address1, :Address2, :City, :State, :Zip, :SpinOutFirst, :SpinOutSecond, :SpinOutThird, :LunchTourFirst, :LunchTourSecond, :LunchTourThird, :SpecialRequest, null, null)';

        // prepare the statement object
        $statement = $conn->prepare($sql);

        $statement->bindValue(":Fname", $_POST['Fname'], PDO::PARAM_STR);
        $statement->bindValue(":Lname", $_POST['Lname'], PDO::PARAM_STR);
        $statement->bindValue(":City", $_POST['City'], PDO::PARAM_STR);
        $statement->bindValue(":Museum", $_POST['Museum'], PDO::PARAM_STR);
        $statement->bindValue(":Email", $_POST['Email'], PDO::PARAM_STR);
        $statement->bindValue(":Phone", $_POST['Phone'], PDO::PARAM_STR);
        $statement->bindValue(":Address1", $_POST['Address1'], PDO::PARAM_STR);
        $statement->bindValue(":Address2", $_POST['Address2'], PDO::PARAM_STR);
        $statement->bindValue(":City", $_POST['City'], PDO::PARAM_STR);
        $statement->bindValue(":State", $_POST['State'], PDO::PARAM_STR);
        $statement->bindValue(":Zip", $_POST['Zip'], PDO::PARAM_STR);
        $statement->bindValue(":SpinOutFirst", $_POST['SpinOutFirst'], PDO::PARAM_STR);
        $statement->bindValue(":SpinOutSecond", $_POST['SpinOutSecond'], PDO::PARAM_STR);
        $statement->bindValue(":SpinOutThird", $_POST['SpinOutThird'], PDO::PARAM_STR);
        $statement->bindValue(":LunchTourFirst", $_POST['LunchTourFirst'], PDO::PARAM_STR);
        $statement->bindValue(":LunchTourSecond", $_POST['LunchTourSecond'], PDO::PARAM_STR);
        $statement->bindValue(":LunchTourThird", $_POST['LunchTourThird'], PDO::PARAM_STR);
        $statement->bindValue(":SpecialRequest", $_POST['SpecialRequest'], PDO::PARAM_STR);

        $statement->execute();

        $conn = null;
        return true;

    }

    function mailMessageTo($to){

        $subject = "A CADN entry has been submitted";

        $message = '
        <html>
            <head>
                <title>CADN Entry</title>
            </head>
            <body>
                <p>' .$_POST['Fname']. ' has selected the following: </p>
                <p><strong>Spinout sessions</strong></p>
                <ol>
                    <li>' .$_POST['SpinOutFirst']. '</li>
                    <li>' .$_POST['SpinOutSecond']. '</li>
                    <li>' .$_POST['SpinOutThird']. '</li>
                </ol>
                <p><strong>Lunch Tour</strong></p>
                <ol>
                    <li>' .$_POST['LunchTourFirst']. '</li>
                    <li>' .$_POST['LunchTourSecond']. '</li>
                    <li>' .$_POST['LunchTourThird']. '</li>
                </ol>
                <p>Login at <a href="http://www.nbmaa.org/cadn/entries.php">http://www.nbmaa.org/cadn/entries.php</a> to find out more!</p>
            </body>
        </html>
        ';

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        mail($to,$subject,$message,$headers);

    }

?>