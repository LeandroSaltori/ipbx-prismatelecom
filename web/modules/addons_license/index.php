<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:
  Codificación: UTF-8
  +----------------------------------------------------------------------+
  | Issabel Version 4.0.0                                                |
  +----------------------------------------------------------------------+
  | Copyright (c) 2017 Issabel Foundation                                |
  +----------------------------------------------------------------------+
  | The contents of this file are subject to the General Public License  |
  | (GPL) Version 2 (the "License"); you may not use this file except in |
  | compliance with the License. You may obtain a copy of the License at |
  | http://www.opensource.org/licenses/gpl-license.php                   |
  |                                                                      |
  | Software distributed under the License is distributed on an "AS IS"  |
  | basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See  |
  | the License for the specific language governing rights and           |
  | limitations under the License.                                       |
  +----------------------------------------------------------------------+
*/

include_once "libs/paloSantoGrid.class.php";
include_once "libs/paloSantoForm.class.php";
include_once "libs/paloSantoDB.class.php";

function _moduleContent(&$smarty, $module_name)
{
    //include module files
    include_once "modules/$module_name/configs/default.conf.php";
    load_language_module($module_name);

    //global variables
    global $arrConf;
    global $arrConfModule;
    $arrConf = array_merge($arrConf,$arrConfModule);

    //folder path for custom templates
    $base_dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $templates_dir=(isset($arrConf['templates_dir']))?$arrConf['templates_dir']:'themes';
    $local_templates_dir="$base_dir/modules/$module_name/".$templates_dir.'/'.$arrConf['theme'];

    //actions
    $accion = getAction();
    $content = "";
  
    switch($accion){
        case 'register':
            $content = registerLicense($smarty, $module_name, $local_templates_dir, $arrConf);
            break;
        case 'showregister':
            $content = showRegisterLicense($smarty, $module_name, $local_templates_dir, $arrConf);
            break;
         default:
            $content = reportLicense($smarty, $module_name, $local_templates_dir, $arrConf);
            break;
    }
    return $content;
}

function license_details() {

    $respuesta = array();
    $addons = array();
    $info = array();
    exec('/usr/bin/issabel-helper license info', $respuesta, $retorno);
    if($retorno==0) {
        foreach($respuesta as $linea) {
             $addonname   = trim(substr($linea,0,31));
             $addoninfo   = trim(substr($linea,31));
             $info[$addonname]=$addoninfo;
        }
    }

    $respuesta = array();
    exec('/usr/bin/issabel-helper license check', $respuesta, $retorno);
    if($retorno==0) {
        foreach($respuesta as $linea) {
             $addonname   = trim(substr($linea,0,31));
             $addonserial = trim(substr($linea,31,18));
             $addonexp    = trim(substr($linea,50,20));
             $addons[]=array('addon'=>$addonname,'serial'=>$addonserial,'expiration'=>$addonexp,'info'=>$info[$addonname]);
        }
        return $addons;
    } else {
        return array();
    }

}

function showRegisterLicense($smarty, $module_name, $local_templates_dir, $arrConf) {

    $arrForm = createFieldForm();
    $oForm = new paloForm($smarty, $arrForm);

    $smarty->assign("REGISTER", _tr("Register"));
    $smarty->assign("CANCEL", _tr("Cancel"));

    $htmlForm = $oForm->fetchForm("$local_templates_dir/form.tpl", '', $_POST);
    $contenidoModulo = "<form  method='POST' style='margin-bottom:0;' action='?menu=$module_name'>".$htmlForm."</form>";
    return $contenidoModulo;

}

function reportLicense($smarty, $module_name, $local_templates_dir, $arrConf) {

    $field_type = null;
    $field_pattern = null;
    //begin grid parameters
    $oGrid  = new paloSantoGrid($smarty);
    $oGrid->addNew("new",_tr("Register Addon License"));

    $addons  = license_details();

    $totalRegisteredAddons =  count($addons);    
    $limit  = 20;
    $total  = $totalRegisteredAddons;
    $oGrid->setLimit($limit);
    $oGrid->setTotal($total);
    $oGrid->setTitle(_tr("License Details"));
    $oGrid->setIcon("modules/$module_name/images/security_define_ports.png");
    $oGrid->pagingShow(true);
    $offset = $oGrid->calculateOffset();
    $url = array(
        "menu"         =>  $module_name,
        "filter_type"  =>  $field_type,
        "filter_txt"   =>  $field_pattern
    );
    $oGrid->setURL($url);

    $arrData = null;
 
    $arrResult = array_slice($addons, $offset, ($total-$offset) < $limit ? ($total-$offset) : $limit);

    $arrColumns = array(_tr("Addon"),_tr("Serial"),_tr('Expiration'),_tr('Info'));
    $oGrid->setColumns($arrColumns);
    if( is_array($arrResult) && $total>0 ){
        foreach($arrResult as $key => $value){
            if(preg_match("/\(ex/",$value['expiration'])) {
                $parts = preg_split("/ /",$value['expiration']);
                $value['expiration']='<p title="'._tr('Expired').'" style="color:red;">'.$parts[0].'</p>';
            }
            $arrTmp[0] = $value['addon'];
            $arrTmp[1] = $value['serial'];
            $arrTmp[2] = $value['expiration'];
            $arrTmp[3] = $value['info'];
            $arrData[] = $arrTmp;
        }
    }
    $oGrid->setData($arrData);

    $contenidoModulo = $oGrid->fetchGrid();
    if (strpos($contenidoModulo, '<form') === FALSE) {
        $contenidoModulo = "<form  method='POST' style='margin-bottom:0;' action=$url>$contenidoModulo</form>";
    }

    return $contenidoModulo;
}

function registerLicense($smarty, $module_name, $local_templates_dir, $arrConf) {

    $str_msj_error = "Error";

    $error=0;

    if($_POST['serial']<>'') {
        $serial = trim($_POST['serial']);
        exec("/usr/bin/issabel-helper license register ".escapeshellarg($serial), $respuesta, $retorno);
        if($retorno==1) { $error=1; }
    }

    if( $error == 0 ){
        $smarty->assign("mb_title", _tr("Message"));
        $smarty->assign("mb_message", _tr("Addon license registered correctly"));
    }
    else{
        $err = implode("<br>",$respuesta);
        $smarty->assign("mb_title", _tr("ERROR"));
        $smarty->assign("mb_message", $err);
    }

    return reportLicense($smarty, $module_name, $local_templates_dir, $pDB, $arrConf);
}

function createFieldForm() {

    $arrFormElements = array(
        "serial"  => array( "LABEL"                  => _tr("Serial"),
                            "REQUIRED"               => "yes",
                            "INPUT_TYPE"             => "TEXT",
                            "INPUT_EXTRA_PARAM"      => '',
                            "VALIDATION_TYPE"        => "text",
                            "VALIDATION_EXTRA_PARAM" => "" 
                          )
    );
    return $arrFormElements;
}


function getAction() {

    if(getParameter("new")) {
        return "showregister";
    } else if(getParameter("register")) {
        return "register";
    } else {
        return "report";
    }
}
?>
