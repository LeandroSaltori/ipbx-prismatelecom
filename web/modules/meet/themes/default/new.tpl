<script src="https://meet.jit.si/external_api.min.js"></script>

{if $mb_message ne ''}
<br/>
{/if}

{if $videomode eq 'input'}
<div class='panel panel-neon'>
<div class='panel-heading'>
<div class='panel-title'>
{$STARTNEW}
</div>
</div>
<div class='panel-body'>
<form method="POST" role='form'>
<input type='hidden' name='action' value='create'>
<div class='form-group'>
<div>{$invite.LABEL}:
<a href='?edittemplate=1' title='{$CONFEMAIL}'><i class='fa fa-cog'></i></a>
</div>
{$invite.INPUT}
<input class="button col-md-2 col-sm-12 col-md-offset-1 col-sm-offset-0" type="submit" name="save" value="{$STARTCONF}">
</div>
</form>
</div>
</div>
<div class='panel panel-neon'>
<div class='panel-heading'>
<div class='panel-title'>
{$JOINEXISTING}
</div>
</div>
<div class='panel-body'>
<form method="POST" role='form'>
<input type='hidden' name='action' value='join'>
<div class='form-group'>
<div>
{$join.LABEL}:
</div>
{$join.INPUT}
<input class="button col-md-2 col-sm-12 col-md-offset-1 col-sm-offset-0" type="submit" name="save" value="{$JOINCONF}">
</div>
</form>
</div>
</div>
<div class='alert alert-neon text-right'>Powered by <a href='http://jitsi.org' rel='external'>Jitsi</a></div>
{elseif $videomode eq 'template'}

<div class='panel panel-neon'>
<div class='panel-heading'>
<div class='panel-title'>
{$EDITTEMPLATE}
</div>
</div>
<div class='panel-body'>
<form method="POST" role='form'>
<input type='hidden' name='action' value='savetemplate'>

<div class='row'>
<div class='col-md-12'>{$SUBJECT}:</div>
</div>
<div class='row'>
<div class='col-md-8'>
<input type=text name='emailsubject' class='form-control col-md-8'  value='{$emailsubject}'>
</div>
</div>
<div class='panel'></div>
<div class='row'>
<div class='col-md-12'>{$CONTENT}:</div>
<div class='col-md-8'>
<textarea id='emailcontent' name='emailcontent' class='form-control col-md-8' rows='10'>{$emailcontent}</textarea>
</div>
</div>
<br/>
<div class='row'>
<div class='col-md-12'>
<input class="button" type="submit" name="savetemplate" value="{$SAVE}">
<input class="button" type="submit" name="cancel" value="{$CANCEL}">
</div>
</div>
</form>
</div>
</div>

{else}
<div id='meet' data-room='{$roomid}' data-username='{$username}' data-language='{$LANGUAGE}'></div>
{/if}
