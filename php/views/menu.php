<?php
	include_once('../class/jwt.php');
	require_once('../config.php');

	$headers = getallheaders();
	

	if ($headers['Toke']) {

        try {
            /*
            * decode the jwt using the key from config
            */
            $secretKey = base64_decode($config->getKey());
                
            $token = JWT::decode($headers['Toke'], $secretKey, array('HS512'));
?>

                <!-- stuff goes here -->


<?php   } catch (Exception $e) {
            /*
            * the token was not able to be decoded.
            * this is likely because the signature was not able to be verified (tampered token)
            */
            header('HTTP/1.0 401 Unauthorized');
        }

    } else {
        /*
        * The request lacks the authorization token
        */
        header('HTTP/1.0 400 Bad Request');
        echo 'Token not found in request';
    }

?>