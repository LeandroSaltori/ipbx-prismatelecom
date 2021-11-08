<?php
require_once "libs/paloSantoForm.class.php";

function _moduleContent(&$smarty, $module_name)
{
    //include module files
    require_once "modules/$module_name/configs/default.conf.php";
    include_once "libs/paloSantoForm.class.php";

    load_language_module($module_name);

    //global variables
    global $arrConf;
    global $arrConfModule;
    $arrConf = array_merge($arrConf,$arrConfModule);

    //folder path for custom templates
    $base_dir = dirname($_SERVER['SCRIPT_FILENAME']);
    $templates_dir = (isset($arrConf['templates_dir'])) ? $arrConf['templates_dir'] : 'themes';
    $local_templates_dir = "$base_dir/modules/$module_name/".$templates_dir.'/'.$arrConf['theme'];

    // Create email template table for Issabel Meet
    createTableIfNotExists($smarty);

    // Connects to Database
    $pDB = new paloDB($arrConf['dsn_conn_database']);

    // Select email fields/content
    $query = "SELECT * FROM emailconfig WHERE id=1";
    $result = $pDB->fetchTable($query, true, array());
    if(count($result)>0) {
        $emailrow = $result[0];
        $smarty->assign("emailsubject",   $emailrow['subject']);
        $smarty->assign("emailcontent",   $emailrow['content']);
    }

    // Create random room id for new conference 
    $jitsi_room_id = "ISSABEL-".generateRandomString(20); 

    // Smarty variables for template data
    $sNombreUsuario = leerNombreUsuario();
    $smarty->assign("roomid",    $jitsi_room_id);
    $smarty->assign("username",  $sNombreUsuario);
    $smarty->assign("videomode", "input");

    // General translated strings
    $smarty->assign("SAVE",         _tr("Save"));
    $smarty->assign("CANCEL",       _tr("Cancel"));
    $smarty->assign("STARTCONF",    _tr("Start"));
    $smarty->assign("JOINCONF",     _tr("Join"));
    $smarty->assign("CANCEL",       _tr("Cancel"));
    $smarty->assign("JOINEXISTING", _tr("Join existing Video Conference"));
    $smarty->assign("STARTNEW",     _tr("Start new Video Conference"));
    $smarty->assign("CONFEMAIL",    _tr("Email Template Configuration"));
    $smarty->assign("EDITTEMPLATE", _tr("Edit Email Template"));
    $smarty->assign("SUBJECT",      _tr("Subject"));
    $smarty->assign("CONTENT",      _tr("Content"));

    $arrFormElements = array("invite"  => array( "LABEL"                => _tr("Send invitation emails to"),
                                                 "REQUIRED"               => "yes",
                                                 "INPUT_TYPE"             => "TEXTAREA",
                                                 "VALIDATION_TYPE"        => "text",
                                                 "INPUT_EXTRA_PARAM"      => array("class"=>'col-md-6',"id"=>'invite'),
                                                 "VALIDATION_EXTRA_PARAM" => ""),
                             "join"    => array( "LABEL"                => _tr("Conference ID"),
                                                 "REQUIRED"               => "yes",
                                                 "INPUT_TYPE"             => "TEXT",
                                                 "INPUT_EXTRA_PARAM"      => array("class"=>'col-md-6',"id"=>'join'),
                                                 "VALIDATION_TYPE"        => "text",
                                                 "VALIDATION_EXTRA_PARAM" => "")
    );

    $oForm = new paloForm($smarty, $arrFormElements);

    $lang = get_language();
    $smarty->assign("LANGUAGE",$lang);

    if(isset($_GET['edittemplate'])) {
        $smarty->assign("videomode","template");
    }

    if(isset($_POST['cancel'])) {
        $smarty->assign("videomode","input");
    } else {

        if(isset($_POST['action'])) {
            if($_POST['action']=='create') {
                $emails = preg_split('/[\ \n\,]+/',$_POST['invite']);
                invite_via_email($emails,$emailrow,$jitsi_room_id);
                $smarty->assign("videomode", "video");
            } else if($_POST['action']=='join') {
                $smarty->assign("videomode", "video");
                $jitsi_room_id = $_POST['join'];
                $smarty->assign("roomid", $jitsi_room_id);
            } else if($_POST['action']=='savetemplate') {
                $query = "UPDATE emailconfig SET subject=?,content=? WHERE id=1";
                $arrParam = array($_POST['emailsubject'],$_POST['emailcontent']);
                $result = $pDB->genQuery($query,$arrParam);

                if( $result == FALSE ) {
                    $smarty->assign("mb_title",   _tr('Error').":");
                    $smarty->assign("mb_message", $pDB->errMsg);
                } else {
                    $smarty->assign("mb_title",   _tr('MESSAGE').":");
                    $smarty->assign("mb_message", _tr("Successfuly updated!"));
                }           

                $smarty->assign("videomode","input");
            }
        }
    }

    $contenidoModulo=$oForm->fetchForm("$local_templates_dir/new.tpl", _tr("New Video Conference"),$_POST);
    
    return $contenidoModulo;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Procedimiento para leer el nombre del usuario a partir del ACL
function leerNombreUsuario() {
        global $arrConf;
        $pDB_acl = new paloDB($arrConf['issabel_dsn']['acl']);
        $pACL = new paloACL($pDB_acl);
        $userid = $pACL->getIdUser($_SESSION['issabel_user']);
        $tuplaUser = $pACL->getUsers($userid);
        if (count($tuplaUser) < 1) {
            throw new FailedEventUpdateException(_tr('Invalid user'));
        }
        return $tuplaUser[0][1];
}

function invite_via_email($to,$emailrow,$roomid) {

    require_once 'PHPMailer/class.phpmailer.php';

    $msg   = $emailrow['content'];
    $sTema = $emailrow['subject'];

    $link = "http://issabel.video/$roomid";

    // Variable substitution for mail body and subject
    $msg   = preg_replace("/{LINK}/",   $link,   $msg);
    $msg   = preg_replace("/{ROOMID}/", $roomid, $msg);
    $sTema = preg_replace("/{LINK}/",   $link,   $sTema);
    $sTema = preg_replace("/{ROOMID}/", $roomid, $sTema);

    $sNombreUsuario = leerNombreUsuario();
    $sHostname = trim(file_get_contents("/proc/sys/kernel/hostname")); // TODO: mejorar petición de nombre de host
    $sRemitente = 'noreply@'.$sHostname;
    $sContenidoCorreo = $msg;

    $oMail = new PHPMailer();
    $oMail->CharSet = 'UTF-8';
    $oMail->Host = 'localhost';
    $oMail->Body = $sContenidoCorreo;
    $oMail->IsHTML(true); // Correo HTML
    $oMail->WordWrap = 50;
    $oMail->From     = $sRemitente;
    $oMail->FromName = $sNombreUsuario;

    // Depende de carga de idiomas hecha por _generarContenidoCorreoEvento()
    $oMail->Subject = $sTema;

    foreach ($to as $sDireccionEmail) {
        $sNombre = '';
        $sEmail = $sDireccionEmail;
        $regs = NULL;
        if (preg_match('/"?(.*?)"?\s*<(\S+)>/', $sDireccionEmail, $regs)) {
            $sNombre = $regs[1];
            $sEmail = $regs[2];
        }
        if ($oMail->ValidateAddress($sEmail)) {
            $oMail->ClearAddresses();
            $oMail->AddAddress($sEmail, $sNombre);
            $oMail->Send();
        }
    }
}

function createTableIfNotExists(&$smarty) {

    global $arrConf;
    global $arrConfModule;
    $arrConf = array_merge($arrConf,$arrConfModule);

    //conexion resource
    $pDB = new paloDB($arrConf['dsn_conn_database']);

    $query = "SELECT name FROM sqlite_master WHERE type='table' AND name='emailconfig'";
    $result = $pDB->fetchTable($query, true, array());
    if(count($result)==0) {
        // Debo crear la tabla
        $query = "CREATE TABLE emailconfig (id integer primary key, subject varchar(255) not null, content text)";
        $result = $pDB->genQuery($query,array());

        if( $result == false ){
            $smarty->assign("mb_title",   _tr('Error').":");
            $smarty->assign("mb_message", $pDB->errMsg);
            return false;
        }
        $msg   = "Howdy!<br/><br/>You have been invited to a Video Conference, please <a href='{LINK}'>click here</a> to join or go to http://issabel.video and enter the Room ID {ROOMID}";
        $msg   = str_replace("'","''",$msg);
        
        $query = "INSERT INTO emailconfig (subject,content) VALUES ('Issabel Meet Invitation','$msg')";
        $result = $pDB->genQuery($query,array());
    }
}
