<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:
  Codificación: UTF-8
  +----------------------------------------------------------------------+
  | http://www.issabel.org                                               |
  +----------------------------------------------------------------------+
  | Copyright (c) 2020 Issabel                                           |
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
  $Id: index.php,v 1.0 2020/04/25 German Venturino
*/

class Applet_IssabelNetwork
{
        function handleJSON_getContent($smarty, $module_name, $appletlist)
        {
        $respuesta = array(
            'status'    =>  'success',
            'message'   =>  '(no message)',
        );
        $smarty->assign("LABEL_REMOTE", _tr('Remote Administration'));
        $output2=array();
        $cmd = "/usr/share/issabel/privileged/issabel_network_applet -checkremote";
        exec($cmd, $output2, $retval);

        if ($output2[0] == "1") {
        //if (0755 === (fileperms('/bin/bash') & 0777)) {
            $smarty->assign("REMOTE_CHECKED", "checked");
        }
        //$smarty->assign("REMOTE_CHECKED", "checked");

        exec('/usr/bin/issabel-helper issabel_network_applet', $output, $retval);
        $part = array();
        foreach ($output as $linea) {
            $datos = explode(",", $linea);
            $servicio = array(
                'name'               =>  _tr($datos[0]),
                'status'             =>  _tr($datos[1]),
                'led'                =>  _tr($datos[2]),
            );
            $part[] = $servicio;
        }
        $smarty->assign(array(
            'part'                  =>  $part,
        ));
        
        $local_templates_dir = dirname($_SERVER['SCRIPT_FILENAME'])."/modules/$module_name/applets/IssabelNetwork/tpl";
        $respuesta['html'] = $smarty->fetch("$local_templates_dir/issabelnetwork.tpl");
        $json = new Services_JSON();
        Header('Content-Type: application/json');
        return $json->encode($respuesta);
        }


    function handleJSON_updateStatus($smarty, $module_name, $appletlist)
    {
        exec('/usr/bin/issabel-helper issabel_network_applet', $output, $retval);
        foreach ($output as $linea) {
            $datos = explode(",", $linea);
            $servicio = array(
                'name'               =>  $datos[0],
                'status'             =>  $datos[1],
                'led'                =>  $datos[2],
            );
            $part[] = $servicio;
        }
 
        $json = new Services_JSON();
        Header('Content-Type: application/json');
        return $json->encode($part);
    }

   function handleJSON_EnableRemote($smarty, $module_name, $appletlist)
    {   
       exec('/usr/bin/issabel-helper issabel_network_applet -enableremote', $output, $retval);
        $output2=array();
        $cmd = "/usr/share/issabel/privileged/issabel_network_applet -checkremote";
        exec($cmd, $output2, $retval);
       $json = new Services_JSON();
        Header('Content-Type: application/json');
        return $json->encode($output2[0]);
  }

   function handleJSON_DisableRemote($smarty, $module_name, $appletlist)
    {
       exec('/usr/bin/issabel-helper issabel_network_applet -disableremote', $output, $retval);
        $output2=array();
        $cmd = "/usr/share/issabel/privileged/issabel_network_applet -checkremote";
        exec($cmd, $output2, $retval);
       $json = new Services_JSON();
        Header('Content-Type: application/json');
        return $json->encode($output2[0]);
  }

}

?>
