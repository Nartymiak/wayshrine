wayshrine = function() {

    var pwAttempt = 0;

    var settings = {
        chatInterval: 2500,
        useBlob: false && window.URL // set to true if want to try to use blob
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
        imageFileName: null,
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

    var editor = {};

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
            view('eventFinalDrafts', 'eventFinalDrafts');
            view('printTable', 'printTable');
            view('generalChat', 'generalChat', function(){
                beginChatInterval();
                appendChatCtrl();
            });
            appendMenuCtrl();
            appendFormCtrl();
            appendUploadImgCtrl();
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
            addChat('EventNoteID='+props.eventNoteID+'&EventID='+props.eventID+'&UserID='+props.userID+'&LineText='+$('#generalChatLine').val());
            $("#generalChatLine").val("");
        });
        $("#generalChatLine").keypress(function(event) {
            if (event.which == 13) {
                event.preventDefault();

                addChat('EventNoteID='+props.eventNoteID+'&EventID='+props.eventID+'&UserID='+props.userID+'&LineText='+$('#generalChatLine').val());
                $(this).val("");
            }
        });
    }

    var appendFormCtrl = function(){

        // user clicks on submit
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

            var draftDateTimeSection = $('.draftDateTimeSection'),
                currentEntry = $(this).parents('.draftDateTimeRow:first'),
                newEntry = $(currentEntry.clone()).appendTo(draftDateTimeSection);

            draftDateTimeSection.find('.draftDateTimeRow .hasAddBtn:not(:last) .addTime')
                .removeClass('addTime').addClass('removeTime')
                .removeClass('btn-success').addClass('btn-danger')
                .html('<span class="glyphicon glyphicon-minus"></span>');

        }).on('click', '.removeTime', function(e){
            $(this).parents('.draftDateTimeRow:first').remove();
            e.preventDefault();
            return false;
        });
    }

    var appendUploadImgCtrl = function(){ 
        window.URL  = window.URL || window.webkitURL;
        //binds to onchange event of your input field
        $('#workSpace').on('change', '.inputFilePreview', function() {     
            var files = this.files, errors = "";
            $("#debug").empty();
            $("#imgPreview").empty();
            $("#imgFileName").val('');
            if (!files) { errors += "No file(s) selected or file upload not supported by your browser."; }
            else {
                for(var i=0; i<files.length; i++) {
                    var er = imgFileError(files[i]);
                    if(!er){
                        props.imageFileName = files[i].name;
                        previewImg(files[i], 'imgPreview');
                        $("#imgFileName").val(props.imageFileName);
                    }else { 
                        errors += er; 
                    }
                }
            }
            // Handle errors
            if (errors) { $('#debug').html('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-warning-sign"></span> <strong>Warning!</strong> '+errors+'</div>'); }
        });

        // user clicks on image form submit
        $('#workSpace').on('click', '.uploadImgBtn', function(e){
            e.preventDefault();
            var controller = $(this).attr('data-ctrl');
            var form = $(this).closest("form");
            var inputFiles = $('.inputFilePreview').prop('files')[0];
            uploadImg(form, inputFiles, controller, function(){
                if(props.imageFileName != null){
                    $('#imgPreview').html('<img src="http://www.nbmaa.org/images/event-page-images/'+props.imageFileName +'">');
                }
            });
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
        if((workSpace.onView === 'note' && props.noteID !== null) || (workSpace.onView === 'draft' && props.eventID !== null)){
            $('.chatLine').each(function(){
                if($(this).attr('cl-note-id') === props.noteID || $(this).attr('cl-draft-id') === props.eventID){
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

        if(editor){
            editor.updateElement();
        }

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
                    addChat('EventNoteID='+props.eventNoteID+'&EventID='+props.eventID+'&UserID='+props.userID+'&LineText=Updated text in <span class="chatLink" chat-link-id="'+ props.noteID +'">' + props.noteName + '</span> in NOTES'); 
                } else if(form.attr('id')==='eventDraftForm'){
                    addChat('EventNoteID='+props.eventNoteID+'&EventID='+props.eventID+'&UserID='+props.userID+'&LineText=Updated text in <span class="chatLink" chat-link-id="'+ props.eventID +'">' + props.eventName + '</span> in DRAFTS');
                }
            },
            error: function( xhr, status, errorThrown ) {
                $("#debug").empty();
                $("#debug").html('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-warning-sign"></span> <strong>Oops! Something went wrong. Your information was not saved.</strong><br> Code: ' +errorThrown+' </div>');
            },
            complete: function( xhr, status ) {
                if(cb){ cb() };
            }
        });
    }

    function previewImg (file, previewID) {
        var reader = new FileReader();
        var elPreview = document.getElementById(previewID);

        reader.addEventListener("load", function () {
            var image = new Image();
            image.addEventListener("load", function () {
                var imageInfo = image.width +' × '+ image.height +' | '+ file.type +' | '+ Math.round(file.size/1024) +'KB';
                // Show image and info
                elPreview.innerHTML = "";
                elPreview.appendChild( this );
                elPreview.insertAdjacentHTML("beforeend", '<p>' + imageInfo + '</p>' );
                if (settings.useBlob) {
                    // Free some memory
                    window.URL.revokeObjectURL(image.src);
                }
            });
            image.src = settings.useBlob ? window.URL.createObjectURL(file) : reader.result;
        });
        reader.readAsDataURL(file);  
    }

    var uploadImg = function(form, files, controller, cb){

        var formData = new FormData(form[0]);
        
        if(files){
            var errors = false;
            for(var i=0;i<files.length;i++){
                var er = imgFileError(files);
                if(er){ errors += er;}
            }     
            if(!errors){
                for(var j=0;j<files.length;j++){
                    formData.append("imgFiles[]", files[i]);
                }
            } else {
                $("#debug").html('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-warning-sign"></span> <strong>Oops! </strong>There was problem with the file(s) you selected.<br> Code: ' +errors+' </div>');
                return;
            }
        }
        $.ajax({
            url: 'php/fns/'+controller+'.php',
            beforeSend: function(xhr){
                xhr.setRequestHeader('Toke', jwt.unParsed);
            },
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,                        
            dataType : 'text',
            success: function(data){
                $("#debug").html('<div class="alert alert-success" role="alert">'+data+'</div>');
                // add auto chat
                addChat('EventNoteID='+props.eventNoteID+'&EventID='+props.eventID+'&UserID='+props.userID+'&LineText=Updated image for <span class="chatLink" chat-link-id="'+ props.eventID +'">' + props.eventName + '</span> in DRAFT'); 
            },
            error: function( xhr, status, errorThrown ) {
                $("#debug").html('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-warning-sign"></span> <strong>Oops! Something went wrong. Your information was not saved.</strong><br> Code: ' +errorThrown+' </div>');
            },
            complete: function( xhr, status ) {
                if(cb){ cb() };
            }
        });

    }

    function imgFileError(file){
        var errors = '';
        if(!file){  return 'No file selected<br>'; }
        else {
            if(file.name.length < 1) { errors += 'File name is invalid. '; }
            if(file.size > 150000) { errors += 'File size is over 150kb. '; }
            if(file.type != 'image/jpg' && file.type != 'image/gif' && file.type != 'image/jpeg') { errors += 'File is not a .jpg or .gif. '; }
        }
        if(errors != ''){ return errors; }
        else {return false; }
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
                    $('#loginError').html('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-warning-sign"></span> Incorrect credentials (attempts: '+pwAttempt+')</div>');
                    jwt.exists = false;
                } else {
                    pwAttempt = 0;
                    $('#loginError').empty();
                    $('#login').empty();
                    jwt.exists  = true;
                    jwt.unParsed = data.jwt;
                    jwt.parsed = parseJWT(data.jwt);
                    props.userID = jwt.parsed.data.userID;
                    props.username = jwt.parsed.data.userName;
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

    var pastePlainText = function(e){
        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
        e.preventDefault();
        document.execCommand('insertText', false, bufferText);
    }

    /**
     * ===================================================================================
     * = PUBLIC FUNCTIONS
     * ===================================================================================
     */

    el.openNoteWorkSpace = function(id, name){
        props.noteID = id;
        props.showing = name;
        props.noteName = name;
        props.eventID = null;
        props.eventName = null;
        view('noteWorkSpace', 'workSpace', function(){
            workSpace.onView = 'note';
            openWorkSpace();
            // text editors
            $('#noteAdmissionCharge').summernote('destroy');
            $('#noteAdmissionCharge').summernote({ height: 75, disableDragAndDrop: true, toolbar: [['misc', ['codeview']]], callbacks: { onPaste: function (e) { pastePlainText(e); }}});
            //ckeditor
            if (editor) { editor = null }
        });
    }

    el.openDraftWorkSpace = function(draftID, noteID, name){
        props.eventID = draftID;
        props.noteID = noteID;
        props.showing = name;
        props.eventName = name;
        props.imageFileName = null;
        props.noteName = null;
        view('draftWorkSpace', 'workSpace', function(){
            view('noteSummary', 'noteSummary');
            workSpace.onView = 'draft';
            openWorkSpace();
            // text editors
            $('#draftAdmissionCharge').summernote('destroy');
            if (editor) { editor = null }
            editor = CKEDITOR.replace( 'draftDescription');
            editor.on('configLoaded', onConfigLoaded);
            function onConfigLoaded(e) {
                var conf = e.editor.config;
                var lt = conf.lite = conf.lite || {};
                lt.userName = props.username;
                lt.userId= props.id;
            }

            $('#imgCaption').summernote('destroy');
            $('#draftAdmissionCharge').summernote({ height: 75, disableDragAndDrop: true, toolbar: [['misc', ['codeview']]], callbacks: { onPaste: function (e) { pastePlainText(e); }}});
            $('#imgCaption').summernote({ height: 75, disableDragAndDrop: true, toolbar: [['style', ['bold', 'italic', 'clear']], ['misc', ['codeview']]], callbacks: { onPaste: function (e) { pastePlainText(e); }}});
        });
    }

    el.openFinalDraftWorkSpace = function(draftID, noteID, name){
        props.eventID = draftID;
        props.noteID = noteID;
        props.showing = name;
        props.eventName = name;
        props.imageFileName = null;
        props.noteName = null;
        view('finalDraftWorkSpace', 'workSpace', function(){
            view('noteSummary', 'noteSummary');
            workSpace.onView = 'draft';
            openWorkSpace();
            // text editors
            $('#draftAdmissionCharge').summernote('destroy');
            $('#draftDescription').summernote('destroy');
            $('#imgCaption').summernote('destroy');
            $('#draftAdmissionCharge').summernote({ height: 75, disableDragAndDrop: true, toolbar: [['misc', ['codeview']]], callbacks: { onPaste: function (e) { pastePlainText(e); }}});
            $('#draftDescription').summernote({ height: 600, disableDragAndDrop: true, toolbar: [['style', ['style', 'bold', 'italic', 'underline', 'clear']], ['para', ['ul', 'ol', 'paragraph']], ['insert', ['link','linkDialogShow', 'unlink']], ['misc', ['fullscreen', 'codeview', 'help']]], callbacks: { onPaste: function (e) { pastePlainText(e); }}});
            $('#imgCaption').summernote({ height: 75, disableDragAndDrop: true, toolbar: [['style', ['bold', 'italic', 'clear']], ['misc', ['codeview']]], callbacks: { onPaste: function (e) { pastePlainText(e); }}});
            if (editor) { editor = null }
        });
    }


    init();
    return this;
}
