<?php
    include_once('../fns/getFns.php');
    include_once('../fns/checkToken.php');

    $eventNotes = getEventNotes();
    $jsonArr = array();
  
    foreach ($eventNotes as $e) {

        array_push($jsonArr, array(
            'ID' =>  $e['EventID'],
            'InDraft' => makeCheckMark(inDraft($e['EventID'])),
            'Exists' => makeCheckMark(exists($e['Name'])),
            'Name' =>  $e['Name'],
            'StartDate' =>  $e['StartDate'],
            'StartTime' =>  $e['StartTime'],
            'EndTime' =>  makeCheckMark($e['EndTime']),
            'NoteWho' =>  makeCheckMark($e['NoteWho']),
            'NoteWhat' =>  makeCheckMark($e['NoteWhat']),
            'NoteWhere' =>  makeCheckMark($e['NoteWhere']),
            'NoteWhy' =>  makeCheckMark($e['NoteWhy']),
            'ImageSuggestions' =>  makeCheckMark($e['ImageSuggestions']),
            'Sponsors' =>  makeCheckMark($e['Sponsors'])
            )
        );
        
    }

    header('Content-Type: application/json');
    echo json_encode($jsonArr);


    function makeCheckMark($var){
    
        if(empty($var) || $var == '<p><br></p>'){ 
            return '<span style="color:red" class="glyphicon glyphicon-remove" aria-hidden="true"></span>'; 
        } else {
             return '<span style="color:green" class="glyphicon glyphicon-ok" aria-hidden="true"></span>'; 
        }
    }

?>