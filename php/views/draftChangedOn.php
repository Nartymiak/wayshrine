<?php
include_once('../fns/getFns.php');
include_once('../fns/checkToken.php');

if(checkToken()){

    if(!$_POST['props']){


    }else{

        // begin payload
        $json = json_decode($_POST['props']);
        $id = $json->{'eventID'};
        $userTypes = $json->{'userTypes'};
        $changedOn = getEventChangedOn($id);
        ?>

        <h4><small>Last saved: <span id="lastSavedTime"><?php echo $changedOn[0]['ChangedOn'];?></span></small></h4>

        <script>
            var str = $('#lastSavedTime').html();
            str = moment(str, "YYYY-MM-DD hh:mm:ss").format("dddd, MMMM D, YYYY | h:mm:ss a");
            $('#lastSavedTime').html(str);
        </script>
        <?php

    }
}
?>
