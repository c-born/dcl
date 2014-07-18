<link rel="stylesheet" href="{$DIR_JS}fancybox/jquery.fancybox-1.3.1.css" type="text/css" media="screen" />
<div class="panel panel-info">
	<div class="panel-heading"><h3>[{$VAL_PROJECTID}] {$VAL_NAME|escape}</h3></div>
	<div class="panel-body">
		{include file="ProjectOptionsControl.tpl"}
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-6">
					<h4>Details</h4>
					<ul class="list-unstyled">
						<li><span class="glyphicon glyphicon-user"></span> {$smarty.const.STR_PRJ_LEAD|escape}: {$VAL_REPORTTO|escape}</li>
						<li><span class="glyphicon glyphicon-user"></span> {$smarty.const.STR_PRJ_OPENBY|escape}: {$VAL_CREATEDBY|escape} ({$VAL_CREATEDON|escape})</li>
						<li><span class="glyphicon glyphicon-cog"></span> {$smarty.const.STR_PRJ_STATUS|escape}: {$VAL_STATUS|escape}</li>
						<li><span class="glyphicon glyphicon-cog"></span> {$smarty.const.STR_PRJ_TOTTASKS|escape}: {$VAL_TOTALTASKS|escape}</li>
						<li><span class="glyphicon glyphicon-cog"></span> {$smarty.const.STR_PRJ_TASKSCOMP|escape}: {$VAL_TASKSCLOSED|escape}</li>
						<li><span class="glyphicon glyphicon-cog"></span> {$smarty.const.STR_PRJ_PCTCOMP|escape}: {$VAL_PCTCOMP|escape}</li>
						<li><span class="glyphicon glyphicon-user"></span> {$smarty.const.STR_PRJ_TOTRESINCOMPWO|escape}: {$VAL_RESOURCES|escape}</li>
					</ul>
				</div>
				<div class="col-xs-6">
					<h4>Hours</h4>
					<ul class="list-unstyled">
						<li><span class="glyphicon glyphicon-calendar"></span> {$smarty.const.STR_PRJ_DEADLINE|escape}: {$VAL_PROJECTDEADLINE|escape}</li>
						<li><span class="glyphicon glyphicon-calendar"></span> {$smarty.const.STR_PRJ_ETC|escape}: {$VAL_ETCDATE|escape}</li>
						<li><span class="glyphicon glyphicon-calendar"></span> {$smarty.const.STR_PRJ_LASTACT|escape}: {$VAL_LASTACTIVITY|escape}</li>
						{if $VAL_FINALCLOSE}<li><span class="glyphicon glyphicon-calendar"></span> {$smarty.const.STR_PRJ_CLOSEDON|escape}: {$VAL_FINALCLOSE|escape}</li>{/if}
						<li><span class="glyphicon glyphicon-time"></span> {$smarty.const.STR_PRJ_HOURSPROJ|escape}: {$VAL_ESTHOURS|escape}</li>
						<li><span class="glyphicon glyphicon-time"></span> {$smarty.const.STR_PRJ_HOURSPM|escape}: {$VAL_HOURSPM|escape}</li>
						<li><span class="glyphicon glyphicon-time"></span> {$smarty.const.STR_PRJ_HOURSAPP|escape}: {$VAL_TOTALHOURS|escape}</li>
						<li><span class="glyphicon glyphicon-time"></span> {$smarty.const.STR_PRJ_HOURSREM|escape}: {$VAL_ETCHOURS|escape}</li>
					</ul>
				</div>
			</div>
			{if $VAL_PROJECTS && count($VAL_PROJECTS) > 0}
				<h4>{$smarty.const.STR_PRJ_PARENTPRJ|escape}</h4>
				<div class="btn-group">
				{section name=project loop=$VAL_PROJECTS}
					<a class="btn btn-default" href="{$VAL_MENULINK}?menuAction=Project.Detail&id={$VAL_PROJECTS[project].project_id}">{$VAL_PROJECTS[project].name|escape}</a>
				{/section}
				</div>
			{/if}
			<div class="row">
				<div class="col-xs-12">
					<h4>{$smarty.const.STR_PRJ_DESCRIPTION|escape}</h4>
					<p>{$VAL_DESCRIPTION|escape|dcl_link}</p>
				</div>
			</div>
		</div>
	</div>
</div>
<form style="display:none;" method="post" action="{$URL_MAIN_PHP}" id="frmProject" name="frmProject" onsubmit="return processSubmit(this);">
	<input type="hidden" name="menuAction" value="">
	<input type="hidden" name="projectid" value="{$VAL_PROJECTID}">
	<input type="hidden" name="whatid1" value="{$VAL_PROJECTID}">
	<input type="hidden" name="typeid" value="{$VAL_WATCHTYPE}">
	<input type="hidden" name="type" value="{$VAL_WIKITYPE}">
	<input type="hidden" name="id" value="{$VAL_PROJECTID}">
	<input type="hidden" name="name" value="FrontPage">
</form>
{include file="AttachmentsProjectsControl.tpl"}
{include file="ProjectChildrenControl.tpl"}
<p>&nbsp;</p>
{include file="ProjectTasksControl.tpl"}
<script type="text/javascript" src="{$DIR_JS}fancybox/jquery.fancybox-1.3.1.pack.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("a.dcl-lightbox").fancybox({
			type: "iframe"

		});
	});

	function forceSubmit(sAction)
	{
		var f = document.frmProject;
		f.elements['menuAction'].value = sAction;
		processSubmit(f);
	}

	function processSubmit(f){
		var sAction = f.elements['menuAction'].value;
		if (sAction == 'WorkOrder.BatchDetail' || sAction == 'boTimecards.batchadd' || sAction == 'WorkOrder.BatchReassign' || sAction == 'Project.BatchMove')
		{
			if (!submitActionIfValid(sAction))
			{
				alert('You must select at least one work order.');
				return false;
			}
		}
		return true;
	}

	function toggleCheckGroup(btnSender)
	{
		var bChk = btnSender.checked;
		var bOK = false;
		var e=btnSender.form.elements;
		for (var i=0;i<e.length;i++){
			if (!bOK && e[i] == btnSender)
				bOK = true;
			else if (bOK && (e[i].type != "checkbox" || e[i].value == "_groupcheck_"))
				return;
			else if (bOK && e[i].type == "checkbox" && e[i].value != "_groupcheck_")
				e[i].checked = bChk;
		}
	}

	function submitActionIfValid(sAction){
		var bHasChecks = false;
		var f = document.forms['frmWorkorders'];
		for (var i = 0; i < f.elements.length && !bHasChecks; i++){
			bHasChecks = (f.elements[i].type == "checkbox" && f.elements[i].checked)
		}
		if (bHasChecks){
			f.elements['menuAction'].value = sAction;
			f.submit();
		}

		return bHasChecks;
	}
</script>