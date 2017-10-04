<?php
    include_once('../class/jwt.php');

    function checkToken(){
        require_once('../config.php');

        $headers = getallheaders();

        if ($headers['Toke']) {

            try {
                
                $secretKey = base64_decode($config->getKey());
                $token = JWT::decode($headers['Toke'], $secretKey, array('HS512'));
                return true;

            } catch (Exception $e) {
                //the token was not able to be decoded.
                header('HTTP/1.0 401 Unauthorized');
                return false;
            }

        } else {
            //The request lacks the authorization token
            header('HTTP/1.0 400 Bad Request');
            echo 'Token not found in request';
            return false;
        }
    }
?>