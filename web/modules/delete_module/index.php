<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:
  Codificación: UTF-8
  +----------------------------------------------------------------------+
  | Issabel Version 4.0                                                  |
  | http://www.issabel.org                                               |
  +----------------------------------------------------------------------+
  | Copyright (c) 2017 Issabel Foundation                                |
  | Copyright (c) 2006 Palosanto Solutions S. A.                         |
  +----------------------------------------------------------------------+
  | The contents of this file are subject to the General Public License  |
  | (GPL) Version 2 (the "License"); you may not use this file except in |
  | compliance with the License. You may obtain a copy of the License at |
  | http://www.opensource.org/licenses/gpl-license.php                   |
  |                                                                      |
  | Software distributed under the License is distributed on "AS IS"     |
  | basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See  |
  | the License for the specific language governing rights and           |
  | limitations under the License.                                       |
  +----------------------------------------------------------------------+
  | The Initial Developer of the Original Code is PaloSanto Solutions    |
  +----------------------------------------------------------------------+
  $Id: index.php, Tue 31 Dec 2019 01:00:35 PM EST, nicolas@issabel.com
*/
function _moduleContent(&$smarty, $module_name)
{
    //include issabel framework
    include_once "libs/paloSantoGrid.class.php";
    include_once "libs/paloSantoValidar.class.php";
    include_once "libs/paloSantoConfig.class.php";
    include_once "libs/misc.lib.php";
    include_once "libs/paloSantoForm.class.php";

    //include module files
    include_once "modules/$module_name/configs/default.conf.php";
    include_once "modules/$module_name/libs/paloSantoDeleteModule.class.php";
    global $arrConf;

    //include lang local module
    global $arrLangModule;
    $lang=get_language();
    $lang_file="modules/$module_name/lang/$lang.lang";
    $base_dir=dirname($_SERVER['SCRIPT_FILENAME']);
    if (file_exists("$base_dir/$lang_file"))
        include_once($lang_file);
    else
        include_once("modules/$module_name/lang/en.lang");

    //folder path for custom templates
    $base_dir=dirname($_SERVER['SCRIPT_FILENAME']);
    $templates_dir=(isset($arrConfig['templates_dir']))?$arrConfig['templates_dir']:'themes';
    $local_templates_dir="$base_dir/modules/$module_name/".$templates_dir.'/'.$arrConf['theme'];

    require_once('libs/paloSantoDB.class.php');
    $pDB_acl = new paloDB($arrConf['issabel_dsn']['acl']);
    if(!empty($pDB_acl->errMsg)) {
        echo "ERROR DE DB: $pDB_acl->errMsg <br>";
    }

    $pDB_menu = new paloDB($arrConf['issabel_dsn']['menu']);
    if(!empty($pDB_menu->errMsg)) {
        echo "ERROR DE DB: $pDB_menu->errMsg <br>";
    }

    $content="";
    $delete = isset($_POST['delete'])?$_POST['delete']:'';
    if($delete!='') $accion = 'delete_module';
    else $accion = "report_delete_module";
    switch($accion)
    {
        case 'delete_module':
            $content .= delete_module($smarty, $module_name, $local_templates_dir, $arrLangModule, $pDB_acl, $pDB_menu);
            break;
        default:
            $content .= report_delete_module($smarty, $module_name, $local_templates_dir, $arrLangModule, $pDB_acl);
            break;
    }

    return $content;
}

function delete_module($smarty, $module_name, $local_templates_dir, $arrLangModule, $pDB_acl, $pDB_menu)
{
    $ruta = "/var/www/html/modules";

    $module_level_1 = isset($_POST['module_level_1'])?$_POST['module_level_1']:'';
    $module_level_2 = isset($_POST['module_level_2'])?$_POST['module_level_2']:'';
    $module_level_3 = isset($_POST['module_level_3'])?$_POST['module_level_3']:'';

    $delete_files = isset($_POST['delete_files'])?$_POST['delete_files']:'';
    $delete_menu  = isset($_POST['delete_menu'])?$_POST['delete_menu']:'';

    if(!$delete_files && !$delete_menu)
    {
        $smarty->assign("mb_title", $arrLangModule["ERROR"]);
        $smarty->assign("mb_message", $arrLangModule["You haven't selected any option to delete: Menu or Files"]);
        return report_delete_module($smarty, $module_name, $local_templates_dir, $arrLangModule, $pDB_acl);
    }

    require_once('libs/paloSantoNavigation.class.php');
    global $arrConf;
    $pMenu = new paloMenu($arrConf['issabel_dsn']['menu']);
    $arrMenu = $pMenu->cargar_menu();
    $pNav = new paloSantoNavigation($arrMenu, $smarty);

    $arrBorrar_Level_2 = array();
    $arrBorrar_Level_3 = array();

    list($select_level,$module) = preg_split("/~/",$_POST['module']);

    if($select_level==3)
    {
        $arrBorrar_Level_3[$module] = $module;
    }
    else if($select_level==2)
    {
        $arrBorrar_Level_2[$module] = $module;
        $arrBorrar_Level_3 = $pNav->getArrSubMenu($module);
        if(!$arrBorrar_Level_3)
            $arrBorrar_Level_3 = array();
    }
    else if($select_level==1)
    {
        $arrBorrar_Level_2 = $pNav->getArrSubMenu($module);
        if(!$arrBorrar_Level_2)
            $arrBorrar_Level_2 = array();
        foreach($arrBorrar_Level_2 as $key => $valor)
        {
            $arrTmp = $pNav->getArrSubMenu($key);
            if($arrTmp)
                $arrBorrar_Level_3 = array_merge($arrBorrar_Level_3, $arrTmp);
        }
    }

    $pDeleteModule_ACL = new paloSantoDeleteModule($pDB_acl);
    $pDeleteModule_Menu = new paloSantoDeleteModule($pDB_menu);
    $error = false;

    //Primero borro los de nivel 3
    foreach($arrBorrar_Level_3 as $key3 => $valor3)
    {
        if($delete_menu=='on'){
            if(!$pDeleteModule_Menu->Eliminar_Menu($key3))   $error = true;
            else if(!$pDeleteModule_ACL->Eliminar_Resource($key3))  $error = true;
        }
        if($delete_files=='on'){
            if(file_exists("$ruta/$key3"))
            {
                $output = $retval = NULL;
                exec('/usr/bin/issabel-helper develbuilder --deletemodule '.escapeshellarg($key3).' 2>&1',
                    $output, $retval);
                if ($retval!=0) $error = true;
            }
        }
    }

    if(!$error)
    {
        //Ahora borro nivel 2
        foreach($arrBorrar_Level_2 as $key2 => $valor2)
        {
            if($delete_menu=='on'){
                if(!$pDeleteModule_Menu->Eliminar_Menu($key2))   $error = true;
                else if(!$pDeleteModule_ACL->Eliminar_Resource($key2))  $error = true;
            }
            if($delete_files=='on'){
                if(file_exists("$ruta/$key2"))
                {
                    $output = $retval = NULL;
                    exec('/usr/bin/issabel-helper develbuilder --deletemodule '.escapeshellarg($key2).' 2>&1',
                        $output, $retval);
                    if ($retval!=0) $error = true;
                }
            }
        }

        if(!$error && $select_level==1 && $delete_menu=='on')
        {
            //Finalmente borro nivel 1
            if(!$pDeleteModule_Menu->Eliminar_Menu($module))   $error = true;
            else if(!$pDeleteModule_ACL->Eliminar_Resource($module))  $error = true;
        }

        $smarty->assign("mb_message", $arrLangModule["The module was deleted"]);
        unset($_SESSION['issabel_user_permission']);
    }else{
        $smarty->assign("mb_title", $arrLangModule["ERROR"]);
        $smarty->assign("mb_message", $arrLangModule["The module couldn't be deleted"]);
    }

    return report_delete_module($smarty, $module_name, $local_templates_dir, $arrLangModule, $pDB_acl);
}


function buildTree(Array $data, $parent = '') {
    $tree = array();
    foreach ($data as $d) {
        if ($d['IdParent'] == $parent) {
            $children = buildTree($data, $d['id']);
            // set a trivial key
            if (!empty($children)) {
                $d['_children'] = $children;
            }
            $tree[] = $d;
        }
    }
    return $tree;
}

function getTree($tree, $r = 0, $p = '') {
    global $ret;
    foreach ($tree as $i => $t) {
        if($r==1) { $vert='|'; } else { $vert=''; }
        $level = $r+1;
        $dash = ($t['IdParent'] == '') ? '' : str_repeat('&nbsp;', $r*2).$vert.'-';
        $ret.=sprintf("\t<option value='%s'>%s%s</option>\n", $level."~".$t['id'], $dash, $t['Name']);
        if (isset($t['_children'])) {
            if(count($t['_children'])>0) {
                getTree($t['_children'], ++$r, $t['IdParent']); 
                $r--;
            }
        }
    }
    return $ret;
}

function report_delete_module($smarty, $module_name, $local_templates_dir, $arrLangModule, $pDB_acl)
{
    require_once('libs/paloSantoMenu.class.php');
    global $arrConf;

    $pDB_menu = new paloDB($arrConf['issabel_dsn']['menu']);
    if(!empty($pDB_menu->errMsg))
        echo "ERROR DE DB: $pDB_menu->errMsg <br>";

    $pMenu = new paloMenu($pDB_menu);
    $arrMenu = $pMenu->cargar_menu();
    $tree = buildTree($arrMenu);
    $select_menu = "<select name='module'>".getTree($tree)."</select>";

    $level_1 = "<td align='left'><b>{$arrLangModule["Menu"]}:</b></td>";
    $level_1 .= "<td align='left'>";
    $level_1 .= $select_menu;
    $level_1 .= "</td>";

    $oForm = new paloForm($smarty, array());
    $smarty->assign("DELETE", $arrLangModule["Delete"]);

    $smarty->assign("REQUIRED_FIELD", $arrLangModule["Required field"]);

    //$smarty->assign("Level", $arrLangModule["Level"]);
    $smarty->assign("level_1", $level_1);

    $smarty->assign("Delete_Menu", $arrLangModule['Delete Menu']);
    $smarty->assign("Delete_Files", $arrLangModule['Delete Files']);

    $smarty->assign("CONFIRM_CONTINUE", $arrLangModule["Are you sure you wish to continue?"]);
    $smarty->assign("icon","images/conference.png");

    $html = $oForm->fetchForm("$local_templates_dir/delete_module.tpl", $arrLangModule["Delete Module"], $_POST);

    $contenidoModulo = "<form  method='POST' style='margin-bottom:0;' action='?menu=$module_name'>".$html."</form>";

    return $contenidoModulo;
}
?>
