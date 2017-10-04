wayshrine = function() {

    var pwAttempt = 0;

    var settings = {
        chatInterval: 2500
    }

    var jwt = {
        exists: false,
        unParsed: null,
        parsed: null
    }

    var props = {
        userID: null,
        userTypes: null,
        noteID: null,
        noteName: null,
        eventID: null,
        eventName: null,
        showing: 'general'
    }

    var chat = {
        general: $('#generalChatView'),
        intervalID: null,
        lastMessageID: null
    }

    var workSpace = {
        openClass: 'col-sm-6',
        closeClass: 'col-sm-0',
        open: false,
        onView: null
    }

    var eventLists = {
        openClass: 'col-sm-9',
        closeClass: 'col-sm-3'
    }

    var el = this;

    /**
     * ===================================================================================
     * = PRIVATE FUNCTIONS
     * ===================================================================================
     */


    var init = function(){
        appendLoginCtrl();
        draw();
    }

    var draw = function(){

        if(!jwt.exists){
            view('login','login');
        }else{
            view('loginComplete', 'header');
            view('menu', 'menu');
            view('eventNotes', 'eventNotes');
            view('eventDrafts', 'eventDrafts');
            view('eventFinalCopies', 'eventFinalCopies');
            view('generalChat', 'generalChat', function(){
                beginChatInterval();
                appendChatCtrl();
            });
            appendMenuCtrl();
            appendFormCtrl();
        }
    }

    var view = function(page, location, cb){

        propsString = JSON.stringify(props);

        location = $('#'+location+'');
  
        $.ajax({
            url: 'php/views/'+page+'.php',
            beforeSend: function(xhr){
                xhr.setRequestHeader('Toke', jwt.unParsed);
            },
            data : {props:propsString},
            method: 'POST',                    
            dataType : 'text',
            success: function(data){
                    location.empty();
                    location.html(data);
            },
            error: function( xhr, status, errorThrown ) {
                console.log(errorThrown);
            },
            complete: function( xhr, status ) {
                if(cb){ cb() };
            }
        });
    }

    var appendChatCtrl = function(){
        $('#generalChatForm').submit(function(e) {
            e.preventDefault();
            addChat($('#generalChatForm').serialize());
            $("#generalChatLine").val("");
        });
        $("#generalChatLine").keypress(function(event) {
            if (event.which == 13) {
                event.preventDefault();
                addChat($('#generalChatForm').serialize());
                $(this).val("");
            }
        });
    }

    var appendFormCtrl = function(){

        // user clicks on submit in notes
        $('#workSpace').on('click', '.submit', function(e){
            e.preventDefault();
            var controller = $(this).attr('data-ctrl');
            var form = $(this).closest("form");
            submitForm(form, controller);
        });
        // user clicks on send to draft in notes
        $('#workSpace').on('click', '.sendNoteToDraft', function(e){
            e.preventDefault();
            var controller = $(this).attr('data-ctrl');
            var form = $(this).closest("form");
            submitForm(form, controller);
        });
        //dynamic add date & time
        $('#workSpace').on('click', '.addTime', function(e){
            e.preventDefault();

            var draftDatTimeSection = $('.draftDatTimeSection'),
                currentEntry = $(this).parents('.draftDateTimeRow:first'),
                newEntry = $(currentEntry.clone()).appendTo(draftDatTimeSection);

            newEntry.find('label').remove();
            draftDatTimeSection.find('.draftDateTimeRow .hasAddBtn:not(:last) .addTime')
                .removeClass('addTime').addClass('removeTime')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="glyphicon glyphicon-minus"></span>');

        }).on('click', '.removeTime', function(e){
            $(this).parents('.draftDateTimeRow:first').remove();
            e.preventDefault();
            return false;
        });
    }

    var appendLoginCtrl = function(){

        $('#login').on('click', '#loginButton', function(e){
            var username = $("#loginID").val();
            var password = $("#loginPW").val();

            e.preventDefault();
            login(username, password);
        });
    }

    var appendMenuCtrl = function(){
        //apply some logic
        $('#menu').on('click', '#viewWorkSpaceButton', function(){
          if(!workSpace.open){
            openWorkSpace();
          }else{
            closeWorkSpace();
          }
        });
    }

    var addChat = function(serializedForm){
        $.ajax({
            url: 'php/fns/addChat.php',
            type: 'POST',
            data: serializedForm,                         
            dataType : "text",
            success: function(data){
            },
            error: function( xhr, status, errorThrown ) {
                console.log('error');
            },
            complete: function( xhr, status ) {
            }
        });
    }

    var beginChatInterval = function(){

        if(chat.intervalID!==null){ clearInterval(chat.intervalID); }
        updateChat();
        window.setInterval(function(){
            if(chat.general !== null){
                chat.general.empty();
                updateChat();
            }
        }, settings.chatInterval);
    }

    var updateChat = function(noScroll){
        view('getGeneralChat', 'generalChatView', function(){
            // update the messages title
            $('#messagesFor').html(' ('+props.showing+')'); 
            // notify user there is a new chat 
            if(noScroll !== true){
                var lcID = $('.chatText').last().attr('cl-id');
                if(chat.lastMessageID !== lcID){
                    chat.lastMessageID = lcID;
                    $('.talk-bubble').last().addClass('throb');
                    updateChatScroll();
                }
            }
            sortChat();
        });
    }

    var updateChatScroll = function(){
        var element = document.getElementById("generalChatView");
            element.scrollTop = element.scrollHeight;
    }


    var sortChat = function(){
        if((workSpace.onView === 'note' && props.noteID !== null) || (workSpace.onView === 'event' && props.event.ID !== null)){
            $('.chatLine').each(function(){
                if($(this).attr('cl-'+workSpace.onView+'-id') === props.noteID){
                    $(this).css('display','block');
                } else { $(this).css('display', 'none') };
            });
        } else {
            $('.chatLine').each(function(){
                $(this).css('display','block');
             });
        }
    }

    var submitForm = function(form, controller, cb){
        $.ajax({
            url: 'php/fns/'+controller+'.php',
            beforeSend: function(xhr){
                xhr.setRequestHeader('Toke', jwt.unParsed);
            },
            type: 'POST',
            data: form.serialize(),                         
            dataType : "text",
            success: function(data){
                $("#debug").empty();
                $("#debug").html('<div class="alert alert-success" role="alert">'+data+'</div>');
                // add auto chat
                if(form.attr('id')==='eventNoteForm'){ 
                    addChat('EventNoteID='+props.noteID+'&UserID='+props.userID+'&LineText=Updated <span class="chatLink" chat-link-id="'+ props.noteID +'">' + props.noteName + '</span> in NOTES'); 
                }
            },
            error: function( xhr, status, errorThrown ) {
                $("#debug").empty();
                $("#debug").html('<div class="alert alert-danger" role="alert"><strong>Oops! Something went wrong. Your information was not saved.</strong><br> Code: ' +errorThrown+' </div>');
            },
            complete: function( xhr, status ) {
                if(cb){ cb() };
            }
        });
    }

    var login = function(username, password){

        $.ajax({
            url: 'php/fns/login.php',
            type: 'POST',
            data: {
                'username':username,
                'password':password
                },                         
            dataType : "json",
            success: function(data){
                if(data.jwt === false){
                    pwAttempt ++;
                    $('#loginError').empty();
                    $('#loginError').html('<div class="alert alert-danger">Incorrect credentials (attempts: '+pwAttempt+')</div>');
                    jwt.exists = false;
                } else {
                    pwAttempt = 0;
                    $('#loginError').empty();
                    $('#login').empty();
                    jwt.exists  = true;
                    jwt.unParsed = data.jwt;
                    jwt.parsed = parseJWT(data.jwt);
                    props.userID = jwt.parsed.data.userID;
                    props.userTypes = jwt.parsed.data.userTypes;
                    draw();
                }

            },
            error: function( xhr, status, errorThrown ) {
            },
            complete: function( xhr, status ) {
            }
        });
    }

    var openWorkSpace = function(){

      if(!workSpace.open){
        $('#workSpace').css('display', 'block');
        $('#viewWorkSpaceButton').addClass('rotate');
        $('#viewWorkSpaceButton').removeClass('unrotate');
        $('#workSpace').removeClass(workSpace.closeClass);
        $('#eventLists').removeClass(eventLists.openClass);
        $('#eventLists').addClass(eventLists.closeClass);
        $('#workSpace').addClass(workSpace.openClass);
        workSpace.open = true;
      }
    }

    var closeWorkSpace = function(){
      if(workSpace.open){
        $('#workSpace').css('display', 'none');
        $('#viewWorkSpaceButton').removeClass('rotate');
        $('#viewWorkSpaceButton').addClass('unrotate');
        $('#eventLists').removeClass(eventLists.closeClass);
        $('#workSpace').removeClass(workSpace.openClass);
        $('#eventLists').addClass(eventLists.openClass);
        $('#workSpace').addClass(workSpace.closeClass);
        $('#noteChat').css('display', 'none');
        workSpace.open = false;
        workSpace.onView = null;
        props.showing = 'general';
      }
    }

    var parseJWT = function(token) {
        var base64Url = token.split('.')[1];
        var base64 = base64Url.replace('-', '+').replace('_', '/');
        return JSON.parse(window.atob(base64));
    };

    /**
     * ===================================================================================
     * = PUBLIC FUNCTIONS
     * ===================================================================================
     */

    el.openNoteWorkSpace = function(id, name){
        props.noteID = id;
        props.showing = name;
        props.noteName = name;
        view('noteWorkSpace', 'workSpace', function(){
            workSpace.onView = 'note';
            openWorkSpace();
        });
    }

    el.openDraftWorkSpace = function(id, name){
        props.eventID = id;
        props.showing = name;
        props.eventName = name;
        view('draftWorkSpace', 'workSpace', function(){
            workSpace.onView = 'draft';
            openWorkSpace();
            // text editors
            $('#draftAdmissionCharge').summernote('destroy');
            $('#draftDescription').summernote('destroy');
            $('#draftAdmissionCharge').summernote({
                height: 75,
                disableDragAndDrop: true,
                toolbar: [['misc', ['codeview']]]
            });
            $('#draftDescription').summernote({
                height: 600,
                disableDragAndDrop: true,
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link','linkDialogShow', 'unlink']],
                    ['misc', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    }

    init();
    return this;
}
