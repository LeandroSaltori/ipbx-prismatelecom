$(document).ready(function (){

    if($('#emailcontent').length>0) {
       $('#emailcontent').jqte();
    }

    if($('#meet').length>0) {

        var width  = '100%';
        var height = $(window).height();
        height = height - 140;

        var domain = "meet.jit.si";
        var roomid = $('#meet').data('room');
        var room = roomid;

        var username = $('#meet').data('username');
        var language = $('#meet').data('language');
        var htmlElement = document.querySelector('#meet');

        var options = {
            roomName: room,
            width: width,
            height: height,
            parentNode: htmlElement,
            configOverwrite: { defaultLanguage: language},
            interfaceConfigOverwrite: { DEFAULT_REMOTE_DISPLAY_NAME: 'Invitado', LANG_DETECTION: false}
        }

        var api = new JitsiMeetExternalAPI(domain, options);
        api.on("readyToClose",function() { window.location.href='index.php?menu=meet'; })
        api.on("videoConferenceJoined",function() {
            api.executeCommand('displayName', username);
        });

    }

});

