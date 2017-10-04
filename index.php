<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
?>
<!DOCTYPE html>
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<meta name=viewport content="width=device-width, initial-scale=1"> 
			<title>NBMAA Wayshrine | Review and Publish</title>
			<link rel="stylesheet" href="http://www.nbmaa.org/nbmaa4/frameworks/bootstrap-3.3.7/css/bootstrap.min.css">
			<link rel="stylesheet" href="http://www.nbmaa.org/nbmaa4/frameworks/bootstrap-3.3.7/css/bootstrap-table.min.css">
			<link rel="stylesheet" href="http://www.nbmaa.org/nbmaa4/plugins/daterangepicker/daterangepicker.css">
			<link rel="stylesheet" href="http://www.nbmaa.org/nbmaa4/css/nbmaa4.css">
			<link rel="stylesheet" href="css/wayshrine.css">
			<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
			<!-- libraries, frameworks, plugins -->
			<script type="text/javascript" src="http://www.nbmaa.org/nbmaa4/libraries/jquery-3.2.0.min.js"></script>
			<script type="text/javascript" src="http://www.nbmaa.org/nbmaa4/frameworks/bootstrap-3.3.7/js/bootstrap.min.js"></script>
			<script type="text/javascript" src="http://www.nbmaa.org/nbmaa4/frameworks/bootstrap-3.3.7/js/bootstrap-table.min.js"></script>
			<script type="text/javascript" src="http://www.nbmaa.org/nbmaa4/frameworks/bootstrap-3.3.7/js/bootstrap-table-en-US.min.js"></script>
			<script type="text/javascript" src="http://www.nbmaa.org/nbmaa4/plugins/moment.min.js"></script>
			<script type="text/javascript" src="http://www.nbmaa.org/nbmaa4/plugins/daterangepicker/daterangepicker.js"></script>
			<script type="text/javascript" src="js/app.js"></script>
		</head>
		<body>
			<div id="app" class="container">
				<div class="row">
					<div id="header" class="col-sm-12"></div>
				</div>
				<div class="row">
					<div id="menu" class="col-sm-12"></div>
				</div>
				<div class="row">
					<div id="view" class="col-sm-11"></div>
					<div id="workSpace" class="col-sm-1 slideOver"></div>
				</div>
			</div>
			<script type="text/javascript">

					$(document).ready(function() { 
							initialize();					
					});

					function dateFormatter(date){
                        return moment(date, "YYYY-MM-DD").format("MMM DD, YYYY");
					}

					function timeFormatter(time){
                        return moment(time, "hh:mm:ss").format("h:mm A");
					}

					function centerTableCell(value, row, index, field){
						return {
							classes: null,
							css: {"text-align":"center"}
						};
					}

			</script>
		</body>
	</html>