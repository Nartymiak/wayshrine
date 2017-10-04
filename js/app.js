//intialize vars
var jwt = null;
var pwAttempt = 0;
// false means closed
var workAreaState = false;
// shared
var props = {
    userID: null,
    noteID: null,
    eventID: null
};

var chat = {
    note: null,
    noteIntervalID : null
};

function initialize(){
    baseLogic();

    if(jwt!== null){
        parseJWT = parseJwt(jwt);
        props.userID = parseJWT.data.userId;
        view('loginComplete', 'header');
        view('menu', 'menu');
        //consecutively load the initial things to look at
        view('noteWorkSpace', 'workSpace');
        view('eventNotes', 'view', null, function(){
          view('eventDrafts', 'view', null, function(){
            view('eventFinalCopies', 'view');
          });
        });

        //apply some logic
        $('#workSpace').on('click', '#viewWorkSpaceButton', function(){
          if(!workAreaState){
            openWorkArea();
          }else{
            closeWorkArea();
          }
        });

        formLogic();

        chatLogic();


    } else {
        view('login','view');
        loginLogic();
    }
}

function baseLogic(){

    $('#app').on('click', '.genLnk', function(e){
        e.preventDefault();
        var mod = $(this).attr('href');
        var loc = $(this).attr('data');
        view(mod,loc);
    });
}

function loginLogic(){

    $('#view').on('click', '#loginButton', function(e){
        var username = $("#loginID").val();
        var password = $("#loginPW").val();

        e.preventDefault();
        login(username, password);
    });
}

function formLogic(){

    $('#workSpace').on('click', '.submit', function(e){
        e.preventDefault();

        var controller = $(this).attr('data-ctrl');
        var form = $(this).closest("form");
        submitForm(form, controller, 
            function(){
                $('#view').empty();
                view('eventNotes', 'view', null, function(){
                  view('eventDrafts', 'view', null, function(){
                    view('eventFinalCopies', 'view');
                  });
                });
            }
        );
    });
}

function appendChatControls(){
    $('#noteChatForm').submit(function(e) {
        e.preventDefault();
        addChat($('#noteChatForm').serialize());
        $("#chatLine").val("");
    });
    $("#chatLine").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            addChat($('#noteChatForm').serialize());
            $(this).val("");
        }
    });
    chat.note = $('#noteChatView');
}

function openWorkArea(){
  if(!workAreaState){
    $('#viewWorkSpaceButton').addClass('rotate');
    $('#viewWorkSpaceButton').removeClass('unrotate');
    $('#workSpace').removeClass('col-sm-1');
    $('#view').removeClass('col-sm-11');
    $('#view').addClass('col-sm-3');
    $('#workSpace').addClass('col-sm-8');
    $('#workSpace form').css('display', 'block');
    $('#noteChat').css('display', 'block');
    workAreaState = true;

  }
}

function closeWorkArea(){
  if(workAreaState){
    $('#viewWorkSpaceButton').removeClass('rotate');
    $('#viewWorkSpaceButton').addClass('unrotate');
    $('#view').removeClass('col-sm-3');
    $('#workSpace').removeClass('col-sm-3');
    $('#view').addClass('col-sm-11');
    $('#workSpace').addClass('col-sm-1');
    $('#workSpace form').css('display', 'none');
    $('#noteChat').css('display', 'none');
    workAreaState = false;

  }
}

function callNoteWorkSpace(id){
  props.noteID = id;
  propsString = JSON.stringify(props);
  $('#workSpace').empty();
  view('noteWorkSpace', 'workSpace', propsString, function(){
    openWorkArea();
    view('chat','noteChat',propsString, function(){appendChatControls()});
  });
}

function submitForm(form, controller, cb){

    console.log(form.serialize());

    $.ajax({
        url: 'php/fns/'+controller+'.php',
        type: 'POST',
        data: form.serialize(),                         
        dataType : "text",
        success: function(data){
            $("#debug").empty();
            $("#debug").html('<div class="alert alert-success" role="alert">'+data+'</div>');
            addChat('EventNoteID='+props.noteID+'&UserID='+props.userID+'&LineText=Updated event');
        },
        error: function( xhr, status, errorThrown ) {
            $("#debug").empty();
            $("#debug").html('<div class="alert alert-danger" role="alert">Oops! Something went wrong. Your information was not saved. Please try again. Code: ' +errorThrown+' </div>');
        },
        complete: function( xhr, status ) {
            if(cb){ cb() };
        }
    });
}

function addChat(serializedForm){
    $.ajax({
        url: 'php/fns/addChat.php',
        type: 'POST',
        data: serializedForm,                         
        dataType : "text",
        success: function(data){
            console.log('success');
        },
        error: function( xhr, status, errorThrown ) {
            console.log('error');
        },
        complete: function( xhr, status ) {
            console.log('complete');
        }
    });
}

function login(username, password){

    $.ajax({
        url: 'php/fns/login.php',
        type: 'POST',
        data: {
            'username':username,
            'password':password
            },                         
        dataType : "json",
        success: function(data){
      
            jwt = data.jwt;
            switch(jwt) {
                case 'bad email':
                    $('#error').empty();
                    $('#error').html('<div class="alert alert-danger">Incorrect credentials (attempt '+pwAttempt+')</div>');
                    jwt = null;
                    break;
                case 'incorrect pw':
                    pwAttempt ++;
                    $('#error').empty();
                    $('#error').html('<div class="alert alert-danger">Incorrect credentials (attempt '+pwAttempt+')</div>');
                    jwt = null;
                    break;
                default:
                    pwAttempt = 0;
                    $('#error').empty();
                    $('#view').empty();
                    // try it again with results
                    initialize();
            }

        },
        error: function( xhr, status, errorThrown ) {
        },
        complete: function( xhr, status ) {
        }
    });
}

function view(page, location, props, cb){
   
    $.ajax({
        url: 'php/views/'+page+'.php',
        beforeSend: function(xhr){
            xhr.setRequestHeader('Toke', jwt);
        },
        data : {props:props},
        method: 'POST',                    
        dataType : 'text',
        success: function(data){
                //$('#'+location).empty();
                $('#'+location).append(data);
            
        },
        error: function( xhr, status, errorThrown ) {
            console.log(errorThrown);
        },
        complete: function( xhr, status ) {
            if(cb){ cb() };
        }
    });
}

function chatLogic(){

    propsString = JSON.stringify(props);

    chat.noteIntervalID = window.setInterval(function(){
        if(chat.note !== null){
            chat.note.empty();
            view('getChat', 'noteChatView', propsString);
        }
    }, 5000);
}

function unSetChat(){
    clearInterval(chat.noteIntervalID);
}

function checkToken(){

    if(jwt === null){
        return;
    } else {

        $.ajax({
            url: 'php/fns/checkToken.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: jwt,                         
            type: 'post',
            success: function(php_script_response){

                $("#app").empty();
                $( "#app").html( php_script_response );
            },
            error: function( xhr, status, errorThrown ) {
                
                $("#debug").empty();
                $( "#debug").html( "Sorry, there was a problem!" + " Error: " + errorThrown + " Status: " + status );
            },
            complete: function( xhr, status ) {
                

            }

        });
    }
}

function parseJwt (token) {
    var base64Url = token.split('.')[1];
    var base64 = base64Url.replace('-', '+').replace('_', '/');
    return JSON.parse(window.atob(base64));
};
