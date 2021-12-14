<link rel="stylesheet" media="screen" type="text/css" href="modules/{$module_name}/applets/IssabelNetwork/tpl/css/toastr.min.css" />
<link rel="stylesheet" media="screen" type="text/css" href="modules/{$module_name}/applets/IssabelNetwork/tpl/css/issabelnetwork.css" />
<script type='text/javascript' src='modules/{$module_name}/applets/IssabelNetwork/js/javascript.js'></script>
<script src="modules/{$module_name}/applets/IssabelNetwork/js/toastr.min.js"></script>
    <script type="text/javascript">
      {literal}
      toastr.options.timeOut = 12000; // How long the toast will display without user interaction
      toastr.options.extendedTimeOut = 12000; // How long the toast will display after a user hovers over it
      toastr.options.hideMethod = 'slideUp';
      //toastr.options.closeMethod = 'slideDown';
      toastr.options.onclick = function() { toastr.clear(); }
      toastr.options.progressBar = true;
      //toastr.options.preventDuplicates = true;
      toastr.options.newestOnTop = false;
      {/literal}
   </script>
<table style="width:100%">
<tr>
<td>
<div style='width:50%;'>
  <p id="text_remote"><b>{$LABEL_REMOTE}</b></p>
</div>
<div style='float:left;'>
<div class="wrapper" id="remoteadmintog">
  <input id="remoteadmin" type="checkbox" {$REMOTE_CHECKED} /><label class="toggle" for="remoteadmin"><span class="toggle--handler"></span></label>
</div>
</div>
</td>
</tr>
<tr>
<td colspan=2>
<div class="containerled">
{foreach from=$part item=servicio}
  <div class="led-box">
    <script type="text/javascript">
      {if {$servicio.led} == "led-red"}
        toastr.error("{$servicio.status}", 'Issabel Network - ' + "{$servicio.name}");
      {elseif {$servicio.led} == "led-yellow"}
        toastr.warning("{$servicio.status}", 'Issabel Network - ' + "{$servicio.name}");
      {/if}
    </script>
    <div id="led_{$servicio.name}" class="{$servicio.led}"></div>
    <p id="text_{$servicio.name}" >{$servicio.name} ({$servicio.status})</p>
  </div>
{/foreach}
</div>
</td>
</tr>
</table>
