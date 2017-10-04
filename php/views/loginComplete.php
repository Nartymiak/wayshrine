<?php
    include_once('../class/jwt.php');
    require_once('../config.php');

    $headers = getallheaders();
    

    if ($headers['Toke']) {

        try {
            $secretKey = base64_decode($config->getKey());             
            $token = JWT::decode($headers['Toke'], $secretKey, array('HS512'));

?>

            <h2>Welcome <?php echo $token->data->userName; ?></h2>

<?php
        } catch (Exception $e) {
            //the token was not able to be decoded.
            header('HTTP/1.0 401 Unauthorized');
        }

    } else {
        //The request lacks the authorization token
        header('HTTP/1.0 400 Bad Request');
        echo 'Token not found in request';
    }

?>