<?php
	include_once('../class/jwt.php');
    include_once('../fns/checkToken.php');

	$headers = getallheaders();
	

	if(checkToken()){
?>

        <h3 style="text-align:right;">
            <span id="viewWorkSpaceButton" class="glyphicon glyphicon-menu-left rotate" aria-hidden="true"></span>
        </h3>

<?php   
    }
?>