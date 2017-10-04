<?php
	include_once('../class/jwt.php');
    include_once('../fns/getFns.php');
	require_once('../config.php');

	$unencodedArray;
	$jwt;

	$userName = $_POST['username'];
	$password = $_POST['password'];
	
	$login = loginUser($userName, $password);


	if ($login === 'bad email'){
		$jwt = 'bad email';
	
	}else if ($login === false){
		$jwt = 'incorrect pw';
	
	}else if($login){

		$tokenId    = base64_encode(mcrypt_create_iv(32));
	    $issuedAt   = time();
	    $notBefore  = $issuedAt + 10;             //Adding 10 seconds
	    $expire     = $notBefore + 60;            // Adding 60 seconds
	    $serverName = $config->getServerName(); // Retrieve the server name from config file
	    $key        = base64_decode($config->getKey());
	    
	    /*
	     * Create the token as an array
	     */
	    $data = [
	        'iat'  => $issuedAt,         // Issued at: time when the token was generated
	        'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
	        'iss'  => $serverName,       // Issuer
	        'nbf'  => $notBefore,        // Not before
	        'exp'  => $expire,           // Expire
	        'data' => [                  // Data related to the signer user
	            'userId'   => $login['userID'], // userid from the users table
	            'userName' => $userName, // User name
	        ]
	    ];

		$jwt = JWT::encode($data, $key, 'HS512');


	}else{
		$jwt = "unknown error";
	}

	$unencodedArray = ['jwt' => $jwt];
	header('Content-Type: application/json');
    echo json_encode($unencodedArray);

?>