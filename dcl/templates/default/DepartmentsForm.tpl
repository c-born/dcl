{dcl_validator_init}
<form class="form-horizontal" method="post" action="{$URL_MAIN_PHP}">
	<input type="hidden" name="menuAction" value="{$menuAction}">
	{if $id}<input type="hidden" name="id" value="{$id}">{/if}
	<fieldset>
		<legend>{$TXT_FUNCTION|escape}</legend>
		{dcl_form_control id=active controlsize=4 label=$smarty.const.STR_DEPT_ACTIVE required=true}
		{$CMB_ACTIVE}
		{/dcl_form_control}
		{dcl_form_control id=short controlsize=4 label=$smarty.const.STR_DEPT_SHORT required=true}
		{dcl_input_text id=short maxlength=10 value=$VAL_SHORT|trim}
		{/dcl_form_control}
		{dcl_form_control id=name controlsize=4 label=$smarty.const.STR_CMMN_NAME required=true}
		{dcl_input_text id=name maxlength=20 value=$VAL_NAME}
		{/dcl_form_control}
	</fieldset>
	<fieldset>
		<div class="row">
			<div class="col-sm-offset-2">
				<input class="btn btn-primary" type="button" onclick="validateAndSubmitForm(this.form);" value="{$smarty.const.STR_CMMN_SAVE}">
				<input class="btn btn-link" type="button" onclick="location.href = '{$URL_MAIN_PHP}?menuAction=Department.Index';" value="{$smarty.const.STR_CMMN_CANCEL}">
			</div>
		</div>
	</fieldset>
</form>
<script type="text/javascript">
	$(function() {
		$("#short").focus();
	});

	function validateAndSubmitForm(form)
	{
		var aValidators = [
				new ValidatorString(form.elements["short"], "{$smarty.const.STR_DEPT_SHORT}"),
				new ValidatorString(form.elements["name"], "{$smarty.const.STR_CMMN_NAME}")
		];

		for (var i in aValidators)
		{
			if (!aValidators[i].isValid())
			{
				alert(aValidators[i].getError());
				if (typeof(aValidators[i]._Element.focus) == "function")
					aValidators[i]._Element.focus();
				return;
			}
		}

		form.submit();
	}
</script>