<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    header("Pragma: no-cache"); // HTTP 1.0.
    header("Expires: 0"); // Proxies.

    if(empty($_GET)){
        $startDate = date("Y-m-d");
        $endDate = date("Y-m-d", strtotime('+3 months'));
    }else{
        $startDate = $_GET['startDate'];
        $endDate  = $_GET['endDate'];
    }
    $events = getPrintFinalDrafts($startDate, $endDate);
?>
    <!DOCTYPE html>
    	<html>
    		<head>
    			<!-- wayshrine version 2.07-->
    			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    			<meta name=viewport content="width=device-width, initial-scale=1">
    			<meta http-equiv="cache-control" content="max-age=0" />
    			<meta http-equiv="cache-control" content="no-cache" />
    			<meta http-equiv="expires" content="0" />
    			<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    			<meta http-equiv="pragma" content="no-cache" />
    			<title>NBMAA Wayshrine | Print Drafts</title>
    			<link rel="stylesheet" href="http://www.nbmaa.org/nbmaa4/frameworks/bootstrap-3.3.7/css/bootstrap-yeti.min.css">
    			<link rel="stylesheet" href="http://www.nbmaa.org/nbmaa4/frameworks/bootstrap-3.3.7/css/bootstrap-table.min.css">
    			<link rel="stylesheet" href="http://www.nbmaa.org/nbmaa4/plugins/daterangepicker/daterangepicker.css">
    			<link rel="stylesheet" href="http://www.nbmaa.org/nbmaa4/plugins/summernote/summernote.css">
    			<link rel="stylesheet" href="http://www.nbmaa.org/nbmaa4/css/nbmaa4.css">
    			<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
                <link href="http://fonts.googleapis.com/css?family=Lato:100,400,700,100italic,400italic,700italic" rel="stylesheet" type="text/css">
    			<!-- libraries, frameworks, plugins -->
    			<script type="text/javascript" src="http://www.nbmaa.org/nbmaa4/libraries/jquery-3.2.0.min.js"></script>
    			<script type="text/javascript" src="http://www.nbmaa.org/nbmaa4/frameworks/bootstrap-3.3.7/js/bootstrap.min.js"></script>
    			<script type="text/javascript" src="http://www.nbmaa.org/nbmaa4/frameworks/bootstrap-3.3.7/js/bootstrap-table.min.js"></script>
    			<script type="text/javascript" src="http://www.nbmaa.org/nbmaa4/frameworks/bootstrap-3.3.7/js/bootstrap-table-en-US.min.js"></script>
    			<script type="text/javascript" src="http://www.nbmaa.org/nbmaa4/plugins/moment.min.js"></script>
    			<script type="text/javascript" src="http://www.nbmaa.org/nbmaa4/plugins/daterangepicker/daterangepicker.js"></script>
    			<script type="text/javascript" src="http://www.nbmaa.org/nbmaa4/plugins/summernote/summernote.min.js"></script>
    			<script type="text/javascript" src="http://www.nbmaa.org/nbmaa4/plugins/ckeditor/ckeditor.js"></script>

                <style>
                    body{
                    	font-family: 'Lato', sans-serif;
                    	font-weight: 400;
                    }
                    h1, h2, h3 {
                    	line-height: 1;
                    	font-family: 'Montserrat', sans-serif;
                    }
                    p {
                        font-weight: 400;
                    }
                    del { display: none; }
                    ins { text-decoration-line: none; }
                    .printshow { display: none; }
                    @media print {
                        .printshow { display: block; }
                        .noprint { display: none; }
                        .printEvent {
                            padding: 1.5em;
                            padding-right: 20em;
                            page-break-inside: avoid;
                        }
                    }
                </style>
    		</head>
    		<body>
    			<div id="app" class="container-fluid" style="max-width: 1400px">
    				<div class="row">
    					<div class ="col-sm-12" id="login"></div>
    				</div>
    				<div class="row">
    					<div id="header" class="col-sm-12"></div>
    				</div>
    				<div class="row">
    					<div id="menu" class="col-sm-12"></div>
    				</div>
    				<div class="row">
                        <div id="print" class="col-sm-12">

                            <h2>Final Drafts <span class="noprint">| <a href="javascript:window.print()" class="glyphicon glyphicon-print"></a></span></h2>
                            <input type="text" name="daterange" value="01/01/2015 - 01/31/2015" />
                            <div id="events">

<?php
                            $temp;
                            foreach ($events as $e) {

?>
                                <div class="printEvent row">
                                    <div class="col-sm-2">
                                        <?php if($temp !== $e['Word']){ $class = null; $temp = $e['Word']; } else {$class = 'class="printshow"';}?>
                                        <h3 <?php echo $class; ?>><small><?php  echo $e['Word']; ?></small></h3>
                                    </div>
                                    <div class="col-sm-10">
                                        <h3><?php echo $e['EventTitle']; ?></h3>
                                        <h4 class="dateAndTime"><span class="date"><?php echo $e['StartDate']; ?></span>, <span class="time"><?php echo $e['StartTime'];?></span>-<span class="time"><?php echo $e['EndTime'];?></span></h4>
                                        <div class="printDescription">
                                            <?php echo $e['Description']; ?>
                                            <?php echo $e['AdmissionCharge']; ?>
                                        </div>
                                        <?php if(!empty($e['ExhibitionTitle'])){ echo '<h4><small>Related Exhibition: '.$e['ExhibitionTitle']. '</small></h4>'; }?>
                                        <?php echo '<h4><small>Event ID: '.$e['EventID']. '</small></h4>';?>
                                    </div>
                                </div>
<?php
                            }
?>
                            </div>
                        </div>
        			</div>
                    <div class="row">
    					<div id="csv" class="col-sm-12" style="margin:6em 0;padding:6 em;border-top:1px solid black;border-bottom:1px solid black;">

<?php

                            echo 'type,eventTitle,dateTime,desc,admission,relatedEx,eventID,startDate,time<br>';
                            foreach ($events as $e) {

                                $desc = preg_replace('#<del(.*?)>(.*?)</del>#is', '',   $e['Description']);
                                $desc = preg_replace('#</p>(.*?)<p(.*?)>#is', '!!n',   $desc);
                                $desc = preg_replace('#<em(.*?)>#is', '~',   $desc);
                                $desc = preg_replace('#</em>#is', '~',   $desc);

                                echo '&quot;'.$e['Word']. '&quot;,&quot;'
                                            .htmlspecialchars($e['EventTitle']). '&quot;,&quot;'
                                            .'<span class="dateAndTime"><span class="date">' .$e['StartDate']. '</span>, <span class="time">' .$e['StartTime']. '</span>-<span class="time">' .$e['EndTime']. '</span></span>","'
                                            .htmlspecialchars(strip_tags($desc)). '&quot;,&quot;'
                                            .htmlspecialchars(strip_tags($e['AdmissionCharge'])). '&quot;,&quot;'
                                            .htmlspecialchars($e['ExhibitionTitle']). '&quot;,&quot;'
                                            .'<span class="dateAndTime"><span class="date">' .$e['StartDate']. '</span>&quot;,&quot; <span class="time">' .$e['StartTime']. '</span>-<span class="time">' .$e['EndTime']. '</span></span>","'
                                            .$e['EventID']. '&quot;<br>';
                            }
?>

                        </div>
    				</div>
    				<div class="row">
    					<div id="footer" class="col-sm-12"></div>
    				</div>
        		</div>
    			<script type="text/javascript">

    					$(document).ready(function() {

                            var startDate = getParameterByName('startDate') || moment().format('YYYY-MM-DD');
                            var endDate = getParameterByName('endDate') || moment().add(3, 'months').format('YYYY-MM-DD');

                            $('input[name="daterange"]').daterangepicker({
                                locale: {
                                    format: 'YYYY-MM-DD'
                                },
                                startDate: startDate,
                                endDate: endDate
                            },
                                function(start, end, label) {
                                    window.location.href = window.location.href.split('?')[0] + '?startDate='+start.format('YYYY-MM-DD')+'&endDate='+end.format('YYYY-MM-DD');
                            });

							//var wayshrineApp = wayshrine();
                            $('.date').each(function(){
                                var str = printDateFormatter($(this).html());
                                str = str.replace(/, 2017/g, "");
                                $(this).html(str);
                            });
                            $('.time').each(function(){
                                var str = timeFormatter($(this).html());
                                str = str.replace(/:00/g, "");
                                str = str.replace(/AM/g, "a.m.");
                                str = str.replace(/PM/g, "p.m.");
                                str = str.replace(/12 p.m./g, "noon");
                                $(this).html(str);
                            });
                            $('.dateAndTime').each(function(){
                                var str = $(this).html();
                                var count = (str.match(/a.m./g) || []).length;
                                if(count === 2){
                                    str = str.replace(' a.m.','');
                                }
                                count = (str.match(/p.m./g) || []).length;
                                if(count === 2){
                                    str = str.replace(' p.m.','');
                                }
                                $(this).html(str);
                            });
    					});

    					function dateFormatter(date){
                            return moment(date, "YYYY-MM-DD").format("MMM DD, YYYY");
    					}

    					function printDateFormatter(date){
                            return moment(date, "YYYY-MM-DD").format("dddd, MMMM D, YYYY");
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

                        function getParameterByName(name, url) {
                            if (!url) url = window.location.href;
                            name = name.replace(/[\[\]]/g, "\\$&");
                            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                                results = regex.exec(url);
                            if (!results) return null;
                            if (!results[2]) return '';
                            return decodeURIComponent(results[2].replace(/\+/g, " "));
                        }

    			</script>
    		</body>
    	</html>
