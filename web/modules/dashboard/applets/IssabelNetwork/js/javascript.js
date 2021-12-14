$(document).ready(function() {
        $("input:checkbox").change(function() { 
            if($(this).is(":checked")) { 
            $.get('index.php', {
                menu:           getCurrentIssabelModule(),
                rawmode:        'yes',
                    applet:         'IssabelNetwork',
                    action:         'EnableRemote'
                }, function(respuesta) {
                    if (respuesta == "0") {
                        document.getElementById($(this)).checked = false;
                    } else {
                        document.getElementById($(this)).checked = true;
                    }
            });
            } else {
            $.get('index.php', {
                menu:           getCurrentIssabelModule(),
                rawmode:        'yes',
                    applet:         'IssabelNetwork',
                    action:         'DisableRemote'
                }, function(respuesta) {
                    if (respuesta == "0") {
                        document.getElementById($(this)).checked = false;
                    } else {
                        document.getElementById($(this)).checked = true;
                    }
                });
            }
        }); 

    if (typeof issabelnetwork_status_timer == 'undefined')
        issabelnetwork_status_timer = null;
        if (issabelnetwork_status_timer != null) clearInterval(issabelnetwork_status_timer);
        issabelnetwork_status_timer = setInterval(function() {
            $.get('index.php', {
                menu:           getCurrentIssabelModule(),
                rawmode:        'yes',
                    applet:         'IssabelNetwork',
                    action:         'updateStatus'
            }, function(respuesta) {
                for(i=0;i<respuesta.length;i++) {
                    current_class = $('#led_'+respuesta[i].name).attr('class');
                    new_class     = respuesta[i].led;

                    if(new_class != current_class) {
                        $('#led_'+respuesta[i].name).removeClass(current_class).addClass(new_class);
                        document.getElementById('text_' + respuesta[i].name).innerHTML = respuesta[i].name + ' (' +respuesta[i].status + ')';

                        if(respuesta[i].led == "led-red") {
                            toastr.error(respuesta[i].status, 'Issabel Network - ' + respuesta[i].name);
                        } else if(respuesta[i].led == "led-yellow") {
                            toastr.warning(respuesta[i].status, 'Issabel Network - ' + respuesta[i].name);
                        } else if (respuesta[i].led == "led-green"){
                            toastr.success(respuesta[i].status, 'Issabel Network - ' + respuesta[i].name);
                        }
                    }
                }
            });
    }, 16000);
});
